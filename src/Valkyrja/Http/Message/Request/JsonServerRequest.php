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

use JsonException;
use Override;
use Valkyrja\Http\Message\Constant\ContentType;
use Valkyrja\Http\Message\Constant\HeaderName;
use Valkyrja\Http\Message\Enum\ProtocolVersion;
use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Message\File\Contract\UploadedFileContract;
use Valkyrja\Http\Message\Request\Contract\JsonServerRequestContract as Contract;
use Valkyrja\Http\Message\Stream\Contract\StreamContract;
use Valkyrja\Http\Message\Stream\Enum\PhpWrapper;
use Valkyrja\Http\Message\Stream\Stream as HttpStream;
use Valkyrja\Http\Message\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Http\Message\Uri\Contract\UriContract;
use Valkyrja\Http\Message\Uri\Uri as HttpUri;
use Valkyrja\Type\BuiltIn\Support\Arr;

use function array_key_exists;

/**
 * Class JsonServerRequest.
 *
 * @author Melech Mizrachi
 */
class JsonServerRequest extends ServerRequest implements Contract
{
    protected bool $hadParsedBody = true;

    /**
     * Request constructor.
     *
     * @param UriContract                  $uri        [optional] The uri
     * @param RequestMethod                $method     [optional] The method
     * @param StreamContract               $body       [optional] The body stream
     * @param array<string, string[]>      $headers    [optional] The headers
     * @param array<string, mixed>         $server     [optional] The server
     * @param array<string, string|null>   $cookies    [optional] The cookies
     * @param array<array-key, mixed>      $query      [optional] The query string
     * @param array<array-key, mixed>      $parsedBody [optional] The parsed body
     * @param array<array-key, mixed>      $parsedJson [optional] The parsed json
     * @param ProtocolVersion              $protocol   [optional] The protocol version
     * @param UploadedFileContract[]|array $files      [optional] The files
     *
     * @throws InvalidArgumentException
     * @throws JsonException
     */
    public function __construct(
        UriContract $uri = new HttpUri(),
        RequestMethod $method = RequestMethod::GET,
        StreamContract $body = new HttpStream(stream: PhpWrapper::input),
        array $headers = [],
        array $server = [],
        array $cookies = [],
        array $query = [],
        array $parsedBody = [],
        protected array $parsedJson = [],
        ProtocolVersion $protocol = ProtocolVersion::V1_1,
        protected array $files = []
    ) {
        parent::__construct(
            uri: $uri,
            method: $method,
            body: $body,
            headers: $headers,
            server: $server,
            cookies: $cookies,
            query: $query,
            parsedBody: $parsedBody,
            protocol: $protocol,
            files: $files
        );

        if (
            $this->hasHeader(name: HeaderName::CONTENT_TYPE)
            && str_contains($this->getHeaderLine(name: HeaderName::CONTENT_TYPE), ContentType::APPLICATION_JSON)
        ) {
            $bodyContents = (string) $body;

            if (empty($bodyContents)) {
                return;
            }

            $this->parsedJson = Arr::fromString($bodyContents);

            if (empty($parsedBody)) {
                $this->parsedBody    = $this->parsedJson;
                $this->hadParsedBody = false;
            }
        }
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getParsedJson(): array
    {
        return $this->parsedJson;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function onlyParsedJson(string|int ...$names): array
    {
        return $this->onlyParams($this->parsedJson, ...$names);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function exceptParsedJson(string|int ...$names): array
    {
        return $this->exceptParams($this->parsedJson, ...$names);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withParsedJson(array $data): static
    {
        $new = clone $this;

        if (! $this->hadParsedBody) {
            $new->parsedBody = $data;
        }

        $new->parsedJson = $data;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withAddedParsedJsonParam(string|int $name, mixed $value): static
    {
        $new = clone $this;

        if (! $this->hadParsedBody) {
            $new->parsedBody[$name] = $value;
        }

        $new->parsedJson[$name] = $value;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getParsedJsonParam(string|int $name, mixed $default = null): mixed
    {
        return $this->parsedJson[$name] ?? $default;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function hasParsedJsonParam(string|int $name): bool
    {
        return isset($this->parsedJson[$name])
            || array_key_exists($name, $this->parsedJson);
    }
}
