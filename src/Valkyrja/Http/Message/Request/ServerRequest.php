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

use Override;
use Valkyrja\Http\Message\Constant\HeaderName;
use Valkyrja\Http\Message\Enum\ProtocolVersion;
use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Message\File\Collection\Contract\UploadedFileCollectionContract;
use Valkyrja\Http\Message\File\Collection\UploadedFileCollection;
use Valkyrja\Http\Message\Header\Collection\Contract\HeaderCollectionContract;
use Valkyrja\Http\Message\Header\Collection\HeaderCollection;
use Valkyrja\Http\Message\Param\Contract\CookieParamCollectionContract;
use Valkyrja\Http\Message\Param\Contract\ParsedBodyParamCollectionContract;
use Valkyrja\Http\Message\Param\Contract\QueryParamCollectionContract;
use Valkyrja\Http\Message\Param\Contract\ServerParamCollectionContract;
use Valkyrja\Http\Message\Param\CookieParamCollection;
use Valkyrja\Http\Message\Param\ParsedBodyParamCollection;
use Valkyrja\Http\Message\Param\QueryParamCollection;
use Valkyrja\Http\Message\Param\ServerParamCollection;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Stream\Contract\StreamContract;
use Valkyrja\Http\Message\Stream\Enum\PhpWrapper;
use Valkyrja\Http\Message\Stream\Stream;
use Valkyrja\Http\Message\Uri\Contract\UriContract;
use Valkyrja\Http\Message\Uri\Uri;

use function array_filter;
use function in_array;

use const ARRAY_FILTER_USE_KEY;

class ServerRequest extends Request implements ServerRequestContract
{
    /**
     * The attributes.
     *
     * @var array<string, mixed>
     */
    protected array $attributes = [];

    public function __construct(
        UriContract $uri = new Uri(),
        RequestMethod $method = RequestMethod::GET,
        StreamContract $body = new Stream(stream: PhpWrapper::input),
        HeaderCollectionContract $headers = new HeaderCollection(),
        ProtocolVersion $protocol = ProtocolVersion::V1_1,
        protected ServerParamCollectionContract $server = new ServerParamCollection(),
        protected CookieParamCollectionContract $cookies = new CookieParamCollection(),
        protected QueryParamCollectionContract $query = new QueryParamCollection(),
        protected ParsedBodyParamCollectionContract $parsedBody = new ParsedBodyParamCollection(),
        protected UploadedFileCollectionContract $files = new UploadedFileCollection()
    ) {
        $this->protocolVersion = $protocol;

        parent::__construct($uri, $method, $body, $headers);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getServerParams(): ServerParamCollectionContract
    {
        return $this->server;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withServerParams(ServerParamCollectionContract $server): static
    {
        $new = clone $this;

        $new->server = $server;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getCookieParams(): CookieParamCollectionContract
    {
        return $this->cookies;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withCookieParams(CookieParamCollectionContract $cookies): static
    {
        $new = clone $this;

        $new->cookies = $cookies;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getQueryParams(): QueryParamCollectionContract
    {
        return $this->query;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withQueryParams(QueryParamCollectionContract $query): static
    {
        $new = clone $this;

        $new->query = $query;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getUploadedFiles(): UploadedFileCollectionContract
    {
        return $this->files;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withUploadedFiles(UploadedFileCollectionContract $uploadedFiles): static
    {
        $new = clone $this;

        $new->files = $uploadedFiles;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getParsedBody(): ParsedBodyParamCollectionContract
    {
        return $this->parsedBody;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withParsedBody(ParsedBodyParamCollectionContract $params): static
    {
        $new = clone $this;

        $new->parsedBody = $params;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function onlyAttributes(string ...$names): array
    {
        return array_filter(
            $this->attributes,
            static fn (string|int $name): bool => in_array($name, $names, true),
            ARRAY_FILTER_USE_KEY
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function exceptAttributes(string ...$names): array
    {
        return array_filter(
            $this->attributes,
            static fn (string|int $name): bool => ! in_array($name, $names, true),
            ARRAY_FILTER_USE_KEY
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getAttribute(string $name, mixed $default = null): mixed
    {
        return $this->attributes[$name] ?? $default;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withAttribute(string $name, mixed $value): static
    {
        $new = clone $this;

        $new->attributes[$name] = $value;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withoutAttribute(string $name): static
    {
        $new = clone $this;

        unset($new->attributes[$name]);

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function isXmlHttpRequest(): bool
    {
        return $this->headers->get(HeaderName::X_REQUESTED_WITH)?->getValuesAsString() === 'XMLHttpRequest';
    }
}
