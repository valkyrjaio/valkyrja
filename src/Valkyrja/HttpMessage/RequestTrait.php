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

/**
 * Representation of an outgoing, client-side request.
 *
 * Per the HTTP specification, this interface includes properties for
 * each of the following:
 *
 * - Protocol version
 * - HTTP method
 * - URI
 * - Headers
 * - Message body
 *
 * During construction, implementations MUST attempt to set the Host header from
 * a provided URI if no Host header is provided.
 *
 * Requests are considered immutable; all methods that might change state MUST
 * be implemented such that they retain the internal state of the current
 * message and return an instance that contains the changed state.
 *
 * @author Melech Mizrachi
 */
trait RequestTrait
{
    use MessageTrait;

    public static $HOST_NAME      = 'Host';
    public static $HOST_NAME_NORM = 'host';

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
     * The body.
     *
     * @var resource
     */
    protected $body;

    /**
     * Initialize the Request.
     *
     * @param Uri    $uri     [optional] The uri
     * @param string $method  [optional] The method
     * @param Stream $body    [optional] The body stream
     * @param array  $headers [optional] The headers
     *
     * @throws \ReflectionException
     * @throws \Valkyrja\HttpMessage\Exceptions\InvalidMethod
     * @throws \Valkyrja\HttpMessage\Exceptions\InvalidPath
     * @throws \Valkyrja\HttpMessage\Exceptions\InvalidPort
     * @throws \Valkyrja\HttpMessage\Exceptions\InvalidProtocolVersion
     * @throws \Valkyrja\HttpMessage\Exceptions\InvalidQuery
     * @throws \Valkyrja\HttpMessage\Exceptions\InvalidScheme
     * @throws \Valkyrja\HttpMessage\Exceptions\InvalidStream
     *
     * @return void
     */
    protected function initialize(
        Uri $uri = null,
        string $method = null,
        Stream $body = null,
        array $headers = null
    ): void {
        $this->uri     = $uri ?? new NativeUri();
        $this->method  = $method ?? RequestMethod::GET;
        $this->body    = $body ?? new NativeStream('php://input');
        $this->headers = $headers ?? [];

        $this->validateMethod($this->method);
        $this->validateProtocolVersion($this->protocol);

        if ($this->hasHeader(static::$HOST_NAME) && $this->uri->getHost()) {
            $this->headerNames[static::$HOST_NAME_NORM] = static::$HOST_NAME;
            $this->headers[static::$HOST_NAME]          = [
                $this->uri->getHost(),
            ];
        }
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
     * @throws \Valkyrja\HttpMessage\Exceptions\InvalidMethod for invalid HTTP
     *          methods.
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
     * `true`, this method interacts with the Host header in the following
     * ways:
     *
     * - If the Host header is missing or empty, and the new URI contains
     *   a host component, this method MUST update the Host header in the
     *   returned request.
     * - If the Host header is missing or empty, and the new URI does not
     * contain a host component, this method MUST NOT update the Host header in
     * the returned request.
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
     * @param bool $preserveHost Preserve the original state of the Host
     *                           header.
     *
     * @return static
     */
    public function withUri(Uri $uri, bool $preserveHost = false)
    {
        $new = clone $this;

        $new->uri = $uri;

        if ($preserveHost && $this->hasHeader(static::$HOST_NAME)) {
            return $new;
        }

        if (! $uri->getHost()) {
            return $new;
        }

        $host = $uri->getHost();

        $new->headerNames[static::$HOST_NAME_NORM] = static::$HOST_NAME;

        $new->headers =
            $this->injectHeader(
                static::$HOST_NAME,
                $host,
                $new->headerNames,
                true
            );

        return $new;
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
}
