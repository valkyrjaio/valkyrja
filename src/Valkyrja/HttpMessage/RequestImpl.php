<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\HttpMessage;

use Valkyrja\Http\RequestMethod;
use Valkyrja\HttpMessage\Exceptions\InvalidMethod;
use Valkyrja\HttpMessage\Exceptions\InvalidRequestTarget;
use Valkyrja\HttpMessage\Exceptions\InvalidUploadedFile;

/**
 * Class RequestImpl.
 *
 * @author Melech Mizrachi
 */
class RequestImpl implements Request
{
    use MessageTrait;

    public const HOST_NAME      = 'Host';
    public const HOST_NAME_NORM = 'host';

    /**
     * The uri.
     *
     * @var \Valkyrja\HttpMessage\Uri
     */
    protected $uri;

    /**
     * The method.
     *
     * @var string
     */
    protected $method;

    /**
     * The request target.
     *
     * @var string
     */
    protected $requestTarget;

    /**
     * The server params.
     *
     * @var array
     */
    protected $server = [];

    /**
     * The attributes.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * The query params.
     *
     * @var array
     */
    protected $query = [];

    /**
     * The cookies.
     *
     * @var array
     */
    protected $cookies = [];

    /**
     * The parsed body.
     *
     * @var array[]
     */
    protected $parsedBody = [];

    /**
     * The files.
     *
     * @var array
     */
    protected $files = [];

    /**
     * The body.
     *
     * @var resource
     */
    protected $body;

    /**
     * RequestImpl constructor.
     *
     * @param \Valkyrja\HttpMessage\Uri    $uri        [optional] The uri
     * @param string                       $method     [optional] The method
     * @param \Valkyrja\HttpMessage\Stream $body       [optional] The body stream
     * @param array                        $headers    [optional] The headers
     * @param array                        $server     [optional] The server
     * @param array                        $cookies    [optional] The cookies
     * @param array                        $query      [optional] The query string
     * @param array                        $parsedBody [optional] The parsed body
     * @param array                        $files      [optional] The files
     * @param string                       $protocol   [optional] The protocol version
     *
     * @throws \ReflectionException
     * @throws \Valkyrja\HttpMessage\Exceptions\InvalidMethod
     * @throws \Valkyrja\HttpMessage\Exceptions\InvalidProtocolVersion
     * @throws \Valkyrja\HttpMessage\Exceptions\InvalidUploadedFile
     */
    public function __construct(
        Uri $uri = null,
        string $method = null,
        Stream $body = null,
        array $headers = null,
        array $server = null,
        array $cookies = null,
        array $query = null,
        array $parsedBody = null,
        array $files = null,
        string $protocol = null
    ) {
        $this->uri        = $uri ?? new UriImpl();
        $this->method     = $method ?? RequestMethod::GET;
        $this->body       = $body ?? new PhpInputStream();
        $this->server     = $server ?? [];
        $this->headers    = $headers ?? [];
        $this->cookies    = $cookies ?? [];
        $this->query      = $query ?? [];
        $this->parsedBody = $parsedBody ?? [];
        $this->files      = $files ?? [];
        $this->protocol   = $protocol ?? '1.1';

        $this->validateMethod($this->method);
        $this->validateProtocolVersion($this->protocol);
        $this->validateUploadedFiles($this->files);

        if ($this->hasHeader(self::HOST_NAME) && $this->uri->getHost()) {
            $this->headerNames[self::HOST_NAME_NORM] = self::HOST_NAME;
            $this->headers[self::HOST_NAME]          = [$this->uri->getHost()];
        }
    }

    /**
     * Create a new request from PHP super globals.
     *
     * @param array $server  [optional] The server
     * @param array $query   [optional] The query
     * @param array $body    [optional] The body
     * @param array $cookies [optional] The cookies
     * @param array $files   [optional] The files
     *
     * @return \Valkyrja\HttpMessage\Request
     */
    public function fromGlobals(
        array $server = null,
        array $query = null,
        array $body = null,
        array $cookies = null,
        array $files = null
    ): Request {
        return new static();
    }

    /**
     * Retrieves the message's request target.
     *
     * Retrieves the message's request-target either as it will appear (for
     * clients), as it appeared at request (for servers), or as it was
     * specified for the instance (see withRequestTarget()).
     *
     * In most cases, this will be the origin-form of the composed URI,
     * unless a value was provided to the concrete implementation (see
     * withRequestTarget() below).
     *
     * If no URI is available, and no request-target has been specifically
     * provided, this method MUST return the string "/".
     *
     * @return string
     */
    public function getRequestTarget(): string
    {
        if (null !== $this->requestTarget) {
            return $this->requestTarget;
        }

        $target = $this->uri->getPath();

        if ($this->uri->getQuery()) {
            $target .= '?' . $this->uri->getQuery();
        }

        if (empty($target)) {
            $target = '/';
        }

        return $target;
    }

    /**
     * Return an instance with the specific request-target.
     *
     * If the request needs a non-origin-form request-target — e.g., for
     * specifying an absolute-form, authority-form, or asterisk-form —
     * this method may be used to create an instance with the specified
     * request-target, verbatim.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * changed request target.
     *
     * @link http://tools.ietf.org/html/rfc7230#section-5.3 (for the various
     *     request-target forms allowed in request messages)
     *
     * @param string $requestTarget The request target
     *
     * @throws \Valkyrja\HttpMessage\Exceptions\InvalidRequestTarget
     *
     * @return static
     */
    public function withRequestTarget(string $requestTarget)
    {
        $this->validateRequestTarget($requestTarget);

        $new = clone $this;

        $new->requestTarget = $requestTarget;

        return $new;
    }

    /**
     * Retrieves the HTTP method of the request.
     *
     * @return string Returns the request method.
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Return an instance with the provided HTTP method.
     *
     * While HTTP method names are typically all uppercase characters, HTTP
     * method names are case-sensitive and thus implementations SHOULD NOT
     * modify the given string.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * changed request method.
     *
     * @param string $method Case-sensitive method.
     *
     * @throws \ReflectionException
     * @throws \Valkyrja\HttpMessage\Exceptions\InvalidMethod for invalid HTTP methods.
     *
     * @return static
     */
    public function withMethod(string $method)
    {
        $this->validateMethod($method);

        $new = clone $this;

        $new->method = $method;

        return $new;
    }

    /**
     * Retrieves the URI instance.
     *
     * This method MUST return a Uri instance.
     *
     * @link http://tools.ietf.org/html/rfc3986#section-4.3
     *
     * @return Uri Returns a Uri instance
     *             representing the URI of the request.
     */
    public function getUri(): Uri
    {
        return $this->uri;
    }

    /**
     * Returns an instance with the provided URI.
     *
     * This method MUST update the Host header of the returned request by
     * default if the URI contains a host component. If the URI does not
     * contain a host component, any pre-existing Host header MUST be carried
     * over to the returned request.
     *
     * You can opt-in to preserving the original state of the Host header by
     * setting `$preserveHost` to `true`. When `$preserveHost` is set to
     * `true`, this method interacts with the Host header in the following ways:
     *
     * - If the Host header is missing or empty, and the new URI contains
     *   a host component, this method MUST update the Host header in the returned
     *   request.
     * - If the Host header is missing or empty, and the new URI does not contain a
     *   host component, this method MUST NOT update the Host header in the returned
     *   request.
     * - If a Host header is present and non-empty, this method MUST NOT update
     *   the Host header in the returned request.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * new Uri instance.
     *
     * @link http://tools.ietf.org/html/rfc3986#section-4.3
     *
     * @param Uri  $uri          New request URI to use.
     * @param bool $preserveHost Preserve the original state of the Host header.
     *
     * @return static
     */
    public function withUri(Uri $uri, bool $preserveHost = false)
    {
        $new = clone $this;

        $new->uri = $uri;

        if ($preserveHost && $this->hasHeader(self::HOST_NAME)) {
            return $new;
        }

        if (! $uri->getHost()) {
            return $new;
        }

        $host = $uri->getHost();

        $new->headerNames[self::HOST_NAME_NORM] = self::HOST_NAME;

        foreach (array_keys($new->headers) as $header) {
            if (strtolower($header) === self::HOST_NAME_NORM) {
                unset($new->headers[$header]);
            }
        }

        $new->headers[self::HOST_NAME] = [$host];

        return $new;
    }

    /**
     * Retrieve server parameters.
     *
     * Retrieves data related to the incoming request environment,
     * typically derived from PHP's $_SERVER superglobal. The data IS NOT
     * REQUIRED to originate from $_SERVER.
     *
     * @return array
     */
    public function getServerParams(): array
    {
        return $this->server;
    }

    /**
     * Retrieve cookies.
     *
     * Retrieves cookies sent by the client to the server.
     *
     * The data MUST be compatible with the structure of the $_COOKIE
     * superglobal.
     *
     * @return array
     */
    public function getCookieParams(): array
    {
        return $this->cookies;
    }

    /**
     * Return an instance with the specified cookies.
     *
     * The data IS NOT REQUIRED to come from the $_COOKIE superglobal, but MUST
     * be compatible with the structure of $_COOKIE. Typically, this data will
     * be injected at instantiation.
     *
     * This method MUST NOT update the related Cookie header of the request
     * instance, nor related values in the server params.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * updated cookie values.
     *
     * @param array $cookies Array of key/value pairs representing cookies.
     *
     * @return static
     */
    public function withCookieParams(array $cookies)
    {
        $new = clone $this;

        $new->cookies = $cookies;

        return $new;
    }

    /**
     * Retrieve query string arguments.
     *
     * Retrieves the deserialized query string arguments, if any.
     *
     * Note: the query params might not be in sync with the URI or server
     * params. If you need to ensure you are only getting the original
     * values, you may need to parse the query string from `getUri()->getQuery()`
     * or from the `QUERY_STRING` server param.
     *
     * @return array
     */
    public function getQueryParams(): array
    {
        return $this->query;
    }

    /**
     * Return an instance with the specified query string arguments.
     *
     * These values SHOULD remain immutable over the course of the incoming
     * request. They MAY be injected during instantiation, such as from PHP's
     * $_GET superglobal, or MAY be derived from some other value such as the
     * URI. In cases where the arguments are parsed from the URI, the data
     * MUST be compatible with what PHP's parse_str() would return for
     * purposes of how duplicate query parameters are handled, and how nested
     * sets are handled.
     *
     * Setting query string arguments MUST NOT change the URI stored by the
     * request, nor the values in the server params.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * updated query string arguments.
     *
     * @param array $query Array of query string arguments, typically from
     *                     $_GET.
     *
     * @return static
     */
    public function withQueryParams(array $query)
    {
        $new = clone $this;

        $new->query = $query;

        return $new;
    }

    /**
     * Retrieve normalized file upload data.
     *
     * This method returns upload metadata in a normalized tree, with each leaf
     * an instance of Psr\Http\Message\UploadedFileInterface.
     *
     * These values MAY be prepared from $_FILES or the message body during
     * instantiation, or MAY be injected via withUploadedFiles().
     *
     * @return array An array tree of UploadedFileInterface instances; an empty
     *               array MUST be returned if no data is present.
     */
    public function getUploadedFiles(): array
    {
        return $this->files;
    }

    /**
     * Create a new instance with the specified uploaded files.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * updated body parameters.
     *
     * @param array $uploadedFiles An array tree of UploadedFileInterface instances.
     *
     * @throws \InvalidArgumentException if an invalid structure is provided.
     *
     * @return static
     */
    public function withUploadedFiles(array $uploadedFiles)
    {
        $this->validateUploadedFiles($uploadedFiles);

        $new = clone $this;

        $new->files = $uploadedFiles;

        return $new;
    }

    /**
     * Retrieve any parameters provided in the request body.
     *
     * If the request Content-Type is either application/x-www-form-urlencoded
     * or multipart/form-data, and the request method is POST, this method MUST
     * return the contents of $_POST.
     *
     * Otherwise, this method may return any results of deserializing
     * the request body content; as parsing returns structured content, the
     * potential types MUST be arrays or objects only. A null value indicates
     * the absence of body content.
     *
     * @return array The deserialized body parameters, if any.
     *               These will typically be an array or object.
     */
    public function getParsedBody(): array
    {
        return $this->parsedBody;
    }

    /**
     * Return an instance with the specified body parameters.
     *
     * These MAY be injected during instantiation.
     *
     * If the request Content-Type is either application/x-www-form-urlencoded
     * or multipart/form-data, and the request method is POST, use this method
     * ONLY to inject the contents of $_POST.
     *
     * The data IS NOT REQUIRED to come from $_POST, but MUST be the results of
     * deserializing the request body content. Deserialization/parsing returns
     * structured data, and, as such, this method ONLY accepts arrays or objects,
     * or a null value if nothing was available to parse.
     *
     * As an example, if content negotiation determines that the request data
     * is a JSON payload, this method could be used to create a request
     * instance with the deserialized parameters.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * updated body parameters.
     *
     * @param array $data The deserialized body data. This will
     *                    typically be in an array or object.
     *
     * @throws \InvalidArgumentException if an unsupported argument type is
     *                                   provided.
     *
     * @return static
     */
    public function withParsedBody(array $data)
    {
        $new = clone $this;

        $new->parsedBody = $data;

        return $new;
    }

    /**
     * Retrieve attributes derived from the request.
     *
     * The request "attributes" may be used to allow injection of any
     * parameters derived from the request: e.g., the results of path
     * match operations; the results of decrypting cookies; the results of
     * deserializing non-form-encoded message bodies; etc. Attributes
     * will be application and request specific, and CAN be mutable.
     *
     * @return array Attributes derived from the request.
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * Retrieve a single derived request attribute.
     *
     * Retrieves a single derived request attribute as described in
     * getAttributes(). If the attribute has not been previously set, returns
     * the default value as provided.
     *
     * This method obviates the need for a hasAttribute() method, as it allows
     * specifying a default value to return if the attribute is not found.
     *
     * @see getAttributes()
     *
     * @param string $name    The attribute name.
     * @param mixed  $default Default value to return if the attribute does not exist.
     *
     * @return mixed
     */
    public function getAttribute(string $name, $default = null)
    {
        return $this->attributes[$name] ?? $default;
    }

    /**
     * Return an instance with the specified derived request attribute.
     *
     * This method allows setting a single derived request attribute as
     * described in getAttributes().
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * updated attribute.
     *
     * @see getAttributes()
     *
     * @param string $name  The attribute name.
     * @param mixed  $value The value of the attribute.
     *
     * @return static
     */
    public function withAttribute(string $name, $value)
    {
        $new = clone $this;

        $new->attributes[$name] = $value;

        return $new;
    }

    /**
     * Return an instance that removes the specified derived request attribute.
     *
     * This method allows removing a single derived request attribute as
     * described in getAttributes().
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that removes
     * the attribute.
     *
     * @see getAttributes()
     *
     * @param string $name The attribute name.
     *
     * @return static
     */
    public function withoutAttribute(string $name)
    {
        $new = clone $this;

        unset($new->attributes[$name]);

        return $new;
    }

    /**
     * Returns the request as a string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return '';
    }

    /**
     * Is this an AJAX request?
     *
     * @return bool
     */
    public function isXmlHttpRequest(): bool
    {
        return 'XMLHttpRequest' === $this->hasHeader('X-Requested-With');
    }

    /**
     * Validate a request target.
     *
     * @param string $requestTarget The request target
     *
     * @throws \Valkyrja\HttpMessage\Exceptions\InvalidRequestTarget
     *
     * @return void
     */
    protected function validateRequestTarget(string $requestTarget): void
    {
        if (preg_match('#\s#', $requestTarget)) {
            throw new InvalidRequestTarget(
                'Invalid request target provided; cannot contain whitespace'
            );
        }
    }

    /**
     * Validate a method.
     *
     * @param string $method The method
     *
     * @throws \ReflectionException
     * @throws \Valkyrja\HttpMessage\Exceptions\InvalidMethod
     *
     * @return void
     */
    protected function validateMethod(string $method): void
    {
        if (! RequestMethod::isValid($method)) {
            throw new InvalidMethod(
                sprintf(
                    'Unsupported HTTP method "%s" provided',
                    $method
                )
            );
        }
    }

    /**
     * Validate uploaded files.
     *
     * @param array $uploadedFiles The uploaded files
     *
     * @throws \Valkyrja\HttpMessage\Exceptions\InvalidUploadedFile
     *
     * @return void
     */
    protected function validateUploadedFiles(array $uploadedFiles): void
    {
        foreach ($uploadedFiles as $file) {
            if (is_array($file)) {
                $this->validateUploadedFiles($file);

                continue;
            }

            if (! $file instanceof UploadedFile) {
                throw new InvalidUploadedFile('Invalid leaf in uploaded files structure');
            }
        }
    }
}
