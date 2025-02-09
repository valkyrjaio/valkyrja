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

namespace Valkyrja\Http\Message\Request;

use Valkyrja\Http\Message\Constant\HeaderName;
use Valkyrja\Http\Message\Enum\ProtocolVersion;
use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Message\Exception\InvalidArgumentException;
use Valkyrja\Http\Message\File\Contract\UploadedFile;
use Valkyrja\Http\Message\Request\Contract\ServerRequest as Contract;
use Valkyrja\Http\Message\Stream\Contract\Stream;
use Valkyrja\Http\Message\Stream\Enum\PhpWrapper;
use Valkyrja\Http\Message\Stream\Stream as HttpStream;
use Valkyrja\Http\Message\Uri\Contract\Uri;
use Valkyrja\Http\Message\Uri\Uri as HttpUri;

use function array_filter;
use function array_key_exists;
use function in_array;

use const ARRAY_FILTER_USE_KEY;

/**
 * Class ServerRequest.
 *
 * @author Melech Mizrachi
 */
class ServerRequest extends Request implements Contract
{
    /**
     * The attributes.
     *
     * @var array<string, mixed>
     */
    protected array $attributes = [];

    /**
     * ServerRequest constructor.
     *
     * @param Uri                        $uri        [optional] The uri
     * @param RequestMethod              $method     [optional] The method
     * @param Stream                     $body       [optional] The body stream
     * @param array<string, string[]>    $headers    [optional] The headers
     * @param array<string, mixed>       $server     [optional] The server
     * @param array<string, string|null> $cookies    [optional] The cookies
     * @param array<array-key, mixed>    $query      [optional] The query string
     * @param array<array-key, mixed>    $parsedBody [optional] The parsed body
     * @param ProtocolVersion            $protocol   [optional] The protocol version
     * @param UploadedFile[]|array       $files      [optional] The files
     *
     * @throws InvalidArgumentException
     */
    public function __construct(
        Uri $uri = new HttpUri(),
        RequestMethod $method = RequestMethod::GET,
        Stream $body = new HttpStream(stream: PhpWrapper::input),
        array $headers = [],
        protected array $server = [],
        protected array $cookies = [],
        protected array $query = [],
        protected array $parsedBody = [],
        ProtocolVersion $protocol = ProtocolVersion::V1_1,
        protected array $files = []
    ) {
        $this->protocolVersion = $protocol;

        parent::__construct($uri, $method, $body, $headers);
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
        return isset($this->server[$name])
            || array_key_exists($name, $this->server);
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
    public function withAddedCookieParam(string $name, ?string $value = null): static
    {
        $new = clone $this;

        $new->cookies[$name] = $value;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function getCookieParam(string $name, ?string $default = null): ?string
    {
        return $this->cookies[$name] ?? $default;
    }

    /**
     * @inheritDoc
     */
    public function hasCookieParam(string $name): bool
    {
        return isset($this->cookies[$name])
            || array_key_exists($name, $this->cookies);
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
    public function onlyQueryParams(string|int ...$names): array
    {
        return $this->onlyParams($this->query, ...$names);
    }

    /**
     * @inheritDoc
     */
    public function exceptQueryParams(string|int ...$names): array
    {
        return $this->exceptParams($this->query, ...$names);
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
    public function withAddedQueryParam(string|int $name, mixed $value): static
    {
        $new = clone $this;

        $new->query[$name] = $value;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function getQueryParam(string|int $name, mixed $default = null): mixed
    {
        return $this->query[$name] ?? $default;
    }

    /**
     * @inheritDoc
     */
    public function hasQueryParam(string|int $name): bool
    {
        return isset($this->query[$name])
            || array_key_exists($name, $this->query);
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
    public function withUploadedFiles(array $uploadedFiles): static
    {
        $new = clone $this;

        $new->files = $uploadedFiles;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function withAddedUploadedFile(UploadedFile $uploadedFile): static
    {
        $new = clone $this;

        $new->files[] = $uploadedFile;

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
    public function onlyParsedBody(string|int ...$names): array
    {
        return $this->onlyParams($this->parsedBody, ...$names);
    }

    /**
     * @inheritDoc
     */
    public function exceptParsedBody(string|int ...$names): array
    {
        return $this->exceptParams($this->parsedBody, ...$names);
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
    public function withAddedParsedBodyParam(string|int $name, mixed $value): static
    {
        $new = clone $this;

        $new->parsedBody[$name] = $value;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function getParsedBodyParam(string|int $name, mixed $default = null): mixed
    {
        return $this->parsedBody[$name] ?? $default;
    }

    /**
     * @inheritDoc
     */
    public function hasParsedBodyParam(string|int $name): bool
    {
        return isset($this->parsedBody[$name])
            || array_key_exists($name, $this->parsedBody);
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
    public function onlyAttributes(string ...$names): array
    {
        return $this->onlyParams($this->attributes, ...$names);
    }

    /**
     * @inheritDoc
     */
    public function exceptAttributes(string ...$names): array
    {
        return $this->exceptParams($this->attributes, ...$names);
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
        return $this->getHeaderLine(HeaderName::X_REQUESTED_WITH) === 'XMLHttpRequest';
    }

    /**
     * Retrieve only the specified params.
     *
     * @param array<array-key, mixed> $params   The params to sort through
     * @param string|int              ...$names The query param names to retrieve
     *
     * @return array<array-key, mixed>
     */
    protected function onlyParams(array $params, string|int ...$names): array
    {
        return array_filter(
            $params,
            static fn (string|int $name): bool => in_array($name, $names, true),
            ARRAY_FILTER_USE_KEY
        );
    }

    /**
     * Retrieve all params except the ones specified.
     *
     * @param array<array-key, mixed> $params   The params to sort through
     * @param string|int              ...$names The query param names to not retrieve
     *
     * @return array<array-key, mixed>
     */
    protected function exceptParams(array $params, string|int ...$names): array
    {
        return array_filter(
            $params,
            static fn (string|int $name): bool => ! in_array($name, $names, true),
            ARRAY_FILTER_USE_KEY
        );
    }
}
