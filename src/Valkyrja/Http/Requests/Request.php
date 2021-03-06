<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Http\Requests;

use InvalidArgumentException;
use JsonException;
use Valkyrja\Http\Constants\ContentType;
use Valkyrja\Http\Constants\Header;
use Valkyrja\Http\Exceptions\InvalidMethod;
use Valkyrja\Http\Exceptions\InvalidPath;
use Valkyrja\Http\Exceptions\InvalidPort;
use Valkyrja\Http\Exceptions\InvalidProtocolVersion;
use Valkyrja\Http\Exceptions\InvalidQuery;
use Valkyrja\Http\Exceptions\InvalidScheme;
use Valkyrja\Http\Exceptions\InvalidStream;
use Valkyrja\Http\Exceptions\InvalidUploadedFile;
use Valkyrja\Http\Request as Contract;
use Valkyrja\Http\Stream;
use Valkyrja\Http\UploadedFile;
use Valkyrja\Http\Uri;
use Valkyrja\Support\Type\Arr;
use Valkyrja\Support\Type\Str;

/**
 * Representation of an incoming, server-side HTTP request.
 * Per the HTTP specification, this interface includes properties for
 * each of the following:
 * - Protocol version
 * - HTTP method
 * - URI
 * - Headers
 * - Message body
 * Additionally, it encapsulates all data as it has arrived to the
 * application from the CGI and/or PHP environment, including:
 * - The values represented in $_SERVER.
 * - Any cookies provided (generally via $_COOKIE)
 * - Query string arguments (generally via $_GET, or as parsed via parse_str())
 * - Upload files, if any (as represented by $_FILES)
 * - Deserialized body parameters (generally from $_POST)
 * $_SERVER values MUST be treated as immutable, as they represent application
 * state at the time of request; as such, no methods are provided to allow
 * modification of those values. The other values provide such methods, as they
 * can be restored from $_SERVER or the request body, and may need treatment
 * during the application (e.g., body parameters may be deserialized based on
 * content type).
 * Additionally, this interface recognizes the utility of introspecting a
 * request to derive and match additional parameters (e.g., via URI path
 * matching, decrypting cookie values, deserializing non-form-encoded body
 * content, matching authorization headers to users, etc). These parameters
 * are stored in an "attributes" property.
 * Requests are considered immutable; all methods that might change state MUST
 * be implemented such that they retain the internal state of the current
 * message and return an instance that contains the changed state.
 *
 * @author Melech Mizrachi
 */
class Request implements Contract
{
    use RequestTrait;

    /**
     * The server params.
     *
     * @var array
     */
    protected array $server = [];

    /**
     * The attributes.
     *
     * @var array
     */
    protected array $attributes = [];

    /**
     * The query params.
     *
     * @var array
     */
    protected array $query = [];

    /**
     * The cookies.
     *
     * @var array
     */
    protected array $cookies = [];

    /**
     * The parsed body.
     *
     * @var array
     */
    protected array $parsedBody = [];

    /**
     * The parsed json.
     *
     * @var array
     */
    protected array $parsedJson = [];

    /**
     * The files.
     *
     * @var array
     */
    protected array $files = [];

    /**
     * NativeServerRequest constructor.
     *
     * @param Uri|null     $uri        [optional] The uri
     * @param string|null  $method     [optional] The method
     * @param Stream|null  $body       [optional] The body stream
     * @param array|null   $headers    [optional] The headers
     * @param array|null   $server     [optional] The server
     * @param array|null   $cookies    [optional] The cookies
     * @param array|null   $query      [optional] The query string
     * @param array|null   $parsedBody [optional] The parsed body
     * @param string|null  $protocol   [optional] The protocol version
     * @param UploadedFile ...$files   [optional] The files
     *
     * @throws InvalidArgumentException
     * @throws InvalidMethod
     * @throws InvalidPath
     * @throws InvalidPort
     * @throws InvalidProtocolVersion
     * @throws InvalidQuery
     * @throws InvalidScheme
     * @throws InvalidStream
     * @throws InvalidUploadedFile
     * @throws JsonException
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
        string $protocol = null,
        UploadedFile ...$files
    ) {
        $this->initialize($uri, $method, $body, $headers);

        if (
            $this->hasHeader(Header::CONTENT_TYPE)
            && Str::contains($this->getHeaderLine(Header::CONTENT_TYPE), ContentType::APPLICATION_JSON)
        ) {
            $this->parsedJson = Arr::fromString((string) $this->stream);

            if (! $parsedBody) {
                $parsedBody = $this->parsedJson;
            }
        }

        $this->server     = $server ?? [];
        $this->cookies    = $cookies ?? [];
        $this->query      = $query ?? [];
        $this->parsedBody = $parsedBody ?? [];
        $this->protocol   = $protocol ?? '1.1';
        $this->files      = $files ?? [];
    }

    /**
     * Retrieve server parameters.
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
     * Retrieve a specific server value.
     * Retrieves a server value sent by the client to the server.
     *
     * @param string $name    The server name to retrieve
     * @param mixed  $default [optional] Default value to return if the param does not exist
     *
     * @return mixed
     */
    public function getServerParam(string $name, $default = null)
    {
        return $this->server[$name] ?? $default;
    }

    /**
     * Determine if a specific server exists.
     *
     * @param string $name The server name to check for
     *
     * @return bool
     */
    public function hasServerParam(string $name): bool
    {
        return isset($this->server[$name]);
    }

    /**
     * Retrieve cookies.
     * Retrieves cookies sent by the client to the server.
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
     * The data IS NOT REQUIRED to come from the $_COOKIE superglobal, but MUST
     * be compatible with the structure of $_COOKIE. Typically, this data will
     * be injected at instantiation.
     * This method MUST NOT update the related Cookie header of the request
     * instance, nor related values in the server params.
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * updated cookie values.
     *
     * @param array $cookies Array of key/value pairs representing cookies.
     *
     * @return static
     */
    public function withCookieParams(array $cookies): self
    {
        $new = clone $this;

        $new->cookies = $cookies;

        return $new;
    }

    /**
     * Retrieve a specific cookie value.
     * Retrieves a cookie value sent by the client to the server.
     *
     * @param string      $name    The cookie name to retrieve
     * @param string|null $default [optional] Default value to return if the param does not exist
     *
     * @return string|null
     */
    public function getCookieParam(string $name, string $default = null): ?string
    {
        return $this->cookies[$name] ?? $default;
    }

    /**
     * Determine if a specific cookie exists.
     *
     * @param string $name The cookie name to check for
     *
     * @return bool
     */
    public function hasCookieParam(string $name): bool
    {
        return isset($this->cookies[$name]);
    }

    /**
     * Retrieve query string arguments.
     * Retrieves the deserialized query string arguments, if any.
     * Note: the query params might not be in sync with the URI or server
     * params. If you need to ensure you are only getting the original
     * values, you may need to parse the query string from
     * `getUri()->getQuery()` or from the `QUERY_STRING` server param.
     *
     * @return array
     */
    public function getQueryParams(): array
    {
        return $this->query;
    }

    /**
     * Retrieve only the specified query string arguments.
     *
     * @param string[] $names The param names to retrieve
     *
     * @return array
     */
    public function onlyQueryParams(array $names): array
    {
        return $this->onlyParams($this->query, $names);
    }

    /**
     * Retrieve all query string arguments except the ones specified.
     *
     * @param string[] $names The param names to not retrieve
     *
     * @return array
     */
    public function exceptQueryParams(array $names): array
    {
        return $this->exceptParams($this->query, $names);
    }

    /**
     * Return an instance with the specified query string arguments.
     * These values SHOULD remain immutable over the course of the incoming
     * request. They MAY be injected during instantiation, such as from PHP's
     * $_GET superglobal, or MAY be derived from some other value such as the
     * URI. In cases where the arguments are parsed from the URI, the data
     * MUST be compatible with what PHP's parse_str() would return for
     * purposes of how duplicate query parameters are handled, and how nested
     * sets are handled.
     * Setting query string arguments MUST NOT change the URI stored by the
     * request, nor the values in the server params.
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * updated query string arguments.
     *
     * @param array $query Array of query string arguments, typically from
     *                     $_GET.
     *
     * @return static
     */
    public function withQueryParams(array $query): self
    {
        $new = clone $this;

        $new->query = $query;

        return $new;
    }

    /**
     * Retrieve a specific query param value.
     * Retrieves a query param value sent by the client to the server.
     *
     * @param string $name    The query param name to retrieve
     * @param mixed  $default [optional] Default value to return if the param does not exist
     *
     * @return mixed
     */
    public function getQueryParam(string $name, $default = null)
    {
        return $this->query[$name] ?? $default;
    }

    /**
     * Determine if a specific query param exists.
     *
     * @param string $name The query param name to check for
     *
     * @return bool
     */
    public function hasQueryParam(string $name): bool
    {
        return isset($this->query[$name]);
    }

    /**
     * Retrieve normalized file upload data.
     * This method returns upload metadata in a normalized tree, with each leaf
     * an instance of Psr\Http\Message\UploadedFileInterface.
     * These values MAY be prepared from $_FILES or the message body during
     * instantiation, or MAY be injected via withUploadedFiles().
     *
     * @return UploadedFile[] An array tree of UploadedFileInterface instances; an empty
     *                        array MUST be returned if no data is present.
     */
    public function getUploadedFiles(): array
    {
        return $this->files;
    }

    /**
     * Create a new instance with the specified uploaded files.
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * updated body parameters.
     *
     * @param UploadedFile ...$uploadedFiles An array tree of UploadedFileInterface
     *                                       instances.
     *
     * @return static
     */
    public function withUploadedFiles(UploadedFile ...$uploadedFiles): self
    {
        $new = clone $this;

        $new->files = $uploadedFiles;

        return $new;
    }

    /**
     * Retrieve any parameters provided in the request body.
     * If the request Content-Type is either application/x-www-form-urlencoded
     * or multipart/form-data, and the request method is POST, this method MUST
     * return the contents of $_POST.
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
     * Retrieve only the specified request body params.
     *
     * @param string[] $names The param names to retrieve
     *
     * @return array
     */
    public function onlyParsedBody(array $names): array
    {
        return $this->onlyParams($this->parsedBody, $names);
    }

    /**
     * Retrieve all request body params except the ones specified.
     *
     * @param string[] $names The param names to not retrieve
     *
     * @return array
     */
    public function exceptParsedBody(array $names): array
    {
        return $this->exceptParams($this->parsedBody, $names);
    }

    /**
     * Return an instance with the specified body parameters.
     * These MAY be injected during instantiation.
     * If the request Content-Type is either application/x-www-form-urlencoded
     * or multipart/form-data, and the request method is POST, use this method
     * ONLY to inject the contents of $_POST.
     * The data IS NOT REQUIRED to come from $_POST, but MUST be the results of
     * deserializing the request body content. Deserialization/parsing returns
     * structured data, and, as such, this method ONLY accepts arrays or
     * objects, or a null value if nothing was available to parse.
     * As an example, if content negotiation determines that the request data
     * is a JSON payload, this method could be used to create a request
     * instance with the deserialized parameters.
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * updated body parameters.
     *
     * @param array $data The deserialized body data. This will
     *                    typically be in an array or object.
     *
     * @throws InvalidArgumentException if an unsupported argument type is provided.
     *
     * @return static
     */
    public function withParsedBody(array $data): self
    {
        $new = clone $this;

        $new->parsedBody = $data;

        return $new;
    }

    /**
     * Retrieve a specific body param value.
     * Retrieves a body param value sent by the client to the server.
     *
     * @param string $name    The body param name to retrieve
     * @param mixed  $default [optional] Default value to return if the param does not exist
     *
     * @return mixed
     */
    public function getParsedBodyParam(string $name, $default = null)
    {
        return $this->parsedBody[$name] ?? $default;
    }

    /**
     * Determine if a specific body param exists.
     *
     * @param string $name The body param name to check for
     *
     * @return bool
     */
    public function hasParsedBodyParam(string $name): bool
    {
        return isset($this->parsedBody[$name]);
    }

    /**
     * Retrieve any parameters provided in the request body.
     * If the request Content-Type is either application/json
     * and the request method is POST, this method MUST
     * return the decoded contents of the body.
     *
     * @return array The decoded json, if any.
     *               These will typically be an array or object.
     */
    public function getParsedJson(): array
    {
        return $this->parsedJson;
    }

    /**
     * Retrieve only the specified request body params.
     *
     * @param string[] $names The param names to retrieve
     *
     * @return array
     */
    public function onlyParsedJson(array $names): array
    {
        return $this->onlyParams($this->parsedJson, $names);
    }

    /**
     * Retrieve all request body params except the ones specified.
     *
     * @param string[] $names The param names to not retrieve
     *
     * @return array
     */
    public function exceptParsedJson(array $names): array
    {
        return $this->exceptParams($this->parsedJson, $names);
    }

    /**
     * Retrieve a specific json param value.
     * Retrieves a json param value sent by the client to the server.
     *
     * @param string $name    The json param name to retrieve
     * @param mixed  $default [optional] Default value to return if the param does not exist
     *
     * @return mixed
     */
    public function getParsedJsonParam(string $name, $default = null)
    {
        return $this->parsedJson[$name] ?? $default;
    }

    /**
     * Determine if a specific json param exists.
     *
     * @param string $name The json param name to check for
     *
     * @return bool
     */
    public function hasParsedJsonParam(string $name): bool
    {
        return isset($this->parsedJson[$name]);
    }

    /**
     * Retrieve attributes derived from the request.
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
     * Retrieve only the specified attributes.
     *
     * @param string[] $names The attribute names to retrieve
     *
     * @return array
     */
    public function onlyAttributes(array $names): array
    {
        return $this->onlyParams($this->attributes, $names);
    }

    /**
     * Retrieve all attributes except the ones specified.
     *
     * @param string[] $names The attribute names to not retrieve
     *
     * @return array
     */
    public function exceptAttributes(array $names): array
    {
        return $this->exceptParams($this->attributes, $names);
    }

    /**
     * Retrieve a single derived request attribute.
     * Retrieves a single derived request attribute as described in
     * getAttributes(). If the attribute has not been previously set, returns
     * the default value as provided.
     * This method obviates the need for a hasAttribute() method, as it allows
     * specifying a default value to return if the attribute is not found.
     *
     * @param string     $name    The attribute name.
     * @param mixed|null $default Default value to return if the attribute does not
     *                            exist.
     *
     * @return mixed
     *
     * @see getAttributes()
     */
    public function getAttribute(string $name, $default = null)
    {
        return $this->attributes[$name] ?? $default;
    }

    /**
     * Return an instance with the specified derived request attribute.
     * This method allows setting a single derived request attribute as
     * described in getAttributes().
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * updated attribute.
     *
     * @param string $name  The attribute name.
     * @param mixed  $value The value of the attribute.
     *
     * @return static
     *
     * @see getAttributes()
     */
    public function withAttribute(string $name, $value): self
    {
        $new = clone $this;

        $new->attributes[$name] = $value;

        return $new;
    }

    /**
     * Return an instance that removes the specified derived request attribute.
     * This method allows removing a single derived request attribute as
     * described in getAttributes().
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that removes
     * the attribute.
     *
     * @param string $name The attribute name.
     *
     * @return static
     *
     * @see getAttributes()
     */
    public function withoutAttribute(string $name): self
    {
        $new = clone $this;

        unset($new->attributes[$name]);

        return $new;
    }

    /**
     * Is this an AJAX request?
     *
     * @return bool
     */
    public function isXmlHttpRequest(): bool
    {
        return 'XMLHttpRequest' === $this->getHeaderLine('X-Requested-With');
    }

    /**
     * Retrieve only the specified params.
     *
     * @param array    $params The params to sort through
     * @param string[] $names  The query param names to retrieve
     *
     * @return array
     */
    protected function onlyParams(array $params, array $names): array
    {
        $onlyParams = [];

        foreach ($names as $name) {
            if (isset($params[$name])) {
                $onlyParams[$name] = $params[$name];
            }
        }

        return $onlyParams;
    }

    /**
     * Retrieve all params except the ones specified.
     *
     * @param array    $params The params to sort through
     * @param string[] $names  The query param names to not retrieve
     *
     * @return array
     */
    protected function exceptParams(array $params, array $names): array
    {
        foreach ($names as $name) {
            if (isset($params[$name])) {
                unset($params[$name]);
            }
        }

        return $params;
    }
}
