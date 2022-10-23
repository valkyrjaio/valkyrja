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
use Valkyrja\Http\Constants\RequestMethod;
use Valkyrja\Http\Constants\StreamType;
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
use Valkyrja\Http\Streams\Stream as HttpStream;
use Valkyrja\Http\UploadedFile;
use Valkyrja\Http\Uri;
use Valkyrja\Http\Uris\Uri as HttpUri;
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
class Request extends SimpleRequest implements Contract
{
    /**
     * The attributes.
     *
     * @var array
     */
    protected array $attributes = [];

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
     * Request constructor.
     *
     * @param Uri          $uri        [optional] The uri
     * @param string       $method     [optional] The method
     * @param Stream       $body       [optional] The body stream
     * @param array        $headers    [optional] The headers
     * @param array        $server     [optional] The server
     * @param array        $cookies    [optional] The cookies
     * @param array        $query      [optional] The query string
     * @param array        $parsedBody [optional] The parsed body
     * @param string       $protocol   [optional] The protocol version
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
        Uri $uri = new HttpUri(),
        string $method = RequestMethod::GET,
        Stream $body = new HttpStream(StreamType::INPUT),
        array $headers = [],
        protected array $server = [],
        protected array $cookies = [],
        protected array $query = [],
        protected array $parsedBody = [],
        protected string $protocol = '1.1',
        UploadedFile ...$files
    ) {
        parent::__construct($uri, $method, $body, $headers);

        if (
            $this->hasHeader(Header::CONTENT_TYPE)
            && Str::contains($this->getHeaderLine(Header::CONTENT_TYPE), ContentType::APPLICATION_JSON)
        ) {
            $this->parsedJson = Arr::fromString((string) $body);

            if (! $parsedBody) {
                $this->parsedBody = $this->parsedJson;
            }
        }

        $this->files = $files ?? [];
    }

    /**
     * @inheritDoc
     */
    public function getServerParams(): array
    {
        return $this->server;
    }

    /**
     * @inheritDoc
     */
    public function getServerParam(string $name, mixed $default = null): mixed
    {
        return $this->server[$name] ?? $default;
    }

    /**
     * @inheritDoc
     */
    public function hasServerParam(string $name): bool
    {
        return isset($this->server[$name]);
    }

    /**
     * @inheritDoc
     */
    public function getCookieParams(): array
    {
        return $this->cookies;
    }

    /**
     * @inheritDoc
     */
    public function withCookieParams(array $cookies): static
    {
        $new = clone $this;

        $new->cookies = $cookies;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function getCookieParam(string $name, string $default = null): ?string
    {
        return $this->cookies[$name] ?? $default;
    }

    /**
     * @inheritDoc
     */
    public function hasCookieParam(string $name): bool
    {
        return isset($this->cookies[$name]);
    }

    /**
     * @inheritDoc
     */
    public function getQueryParams(): array
    {
        return $this->query;
    }

    /**
     * @inheritDoc
     */
    public function onlyQueryParams(array $names): array
    {
        return $this->onlyParams($this->query, $names);
    }

    /**
     * @inheritDoc
     */
    public function exceptQueryParams(array $names): array
    {
        return $this->exceptParams($this->query, $names);
    }

    /**
     * @inheritDoc
     */
    public function withQueryParams(array $query): static
    {
        $new = clone $this;

        $new->query = $query;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function getQueryParam(string $name, mixed $default = null): mixed
    {
        return $this->query[$name] ?? $default;
    }

    /**
     * @inheritDoc
     */
    public function hasQueryParam(string $name): bool
    {
        return isset($this->query[$name]);
    }

    /**
     * @inheritDoc
     */
    public function getUploadedFiles(): array
    {
        return $this->files;
    }

    /**
     * @inheritDoc
     */
    public function withUploadedFiles(UploadedFile ...$uploadedFiles): static
    {
        $new = clone $this;

        $new->files = $uploadedFiles;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function getParsedBody(): array
    {
        return $this->parsedBody;
    }

    /**
     * @inheritDoc
     */
    public function onlyParsedBody(array $names): array
    {
        return $this->onlyParams($this->parsedBody, $names);
    }

    /**
     * @inheritDoc
     */
    public function exceptParsedBody(array $names): array
    {
        return $this->exceptParams($this->parsedBody, $names);
    }

    /**
     * @inheritDoc
     */
    public function withParsedBody(array $data): static
    {
        $new = clone $this;

        $new->parsedBody = $data;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function getParsedBodyParam(string $name, mixed $default = null): mixed
    {
        return $this->parsedBody[$name] ?? $default;
    }

    /**
     * @inheritDoc
     */
    public function hasParsedBodyParam(string $name): bool
    {
        return isset($this->parsedBody[$name]);
    }

    /**
     * @inheritDoc
     */
    public function getParsedJson(): array
    {
        return $this->parsedJson;
    }

    /**
     * @inheritDoc
     */
    public function onlyParsedJson(array $names): array
    {
        return $this->onlyParams($this->parsedJson, $names);
    }

    /**
     * @inheritDoc
     */
    public function exceptParsedJson(array $names): array
    {
        return $this->exceptParams($this->parsedJson, $names);
    }

    /**
     * @inheritDoc
     */
    public function getParsedJsonParam(string $name, mixed $default = null): mixed
    {
        return $this->parsedJson[$name] ?? $default;
    }

    /**
     * @inheritDoc
     */
    public function hasParsedJsonParam(string $name): bool
    {
        return isset($this->parsedJson[$name]);
    }

    /**
     * @inheritDoc
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @inheritDoc
     */
    public function onlyAttributes(array $names): array
    {
        return $this->onlyParams($this->attributes, $names);
    }

    /**
     * @inheritDoc
     */
    public function exceptAttributes(array $names): array
    {
        return $this->exceptParams($this->attributes, $names);
    }

    /**
     * @inheritDoc
     */
    public function getAttribute(string $name, mixed $default = null): mixed
    {
        return $this->attributes[$name] ?? $default;
    }

    /**
     * @inheritDoc
     */
    public function withAttribute(string $name, mixed $value): static
    {
        $new = clone $this;

        $new->attributes[$name] = $value;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function withoutAttribute(string $name): static
    {
        $new = clone $this;

        unset($new->attributes[$name]);

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function isXmlHttpRequest(): bool
    {
        return 'XMLHttpRequest' === $this->getHeaderLine(Header::X_REQUESTED_WITH);
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
