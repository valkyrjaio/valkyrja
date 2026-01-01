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

namespace Valkyrja\Http\Message\Response\Psr;

use Override;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Valkyrja\Http\Message\Enum\ProtocolVersion;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Factory\StreamFactory;
use Valkyrja\Http\Message\Response\Contract\ResponseContract as ValkyrjaResponseContract;
use Valkyrja\Http\Message\Response\Response as ValkyrjaResponse;
use Valkyrja\Http\Message\Stream\Psr\Stream;

use function is_array;

class Response implements ResponseInterface
{
    public function __construct(
        protected ValkyrjaResponseContract $response = new ValkyrjaResponse(),
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getProtocolVersion(): string
    {
        return $this->response->getProtocolVersion()->value;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withProtocolVersion(string $version): ResponseInterface
    {
        $new = clone $this;

        $new->response = $this->response->withProtocolVersion(ProtocolVersion::from($version));

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getHeaders(): array
    {
        return $this->response->getHeaders();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function hasHeader(string $name): bool
    {
        return $this->response->hasHeader($name);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getHeader(string $name): array
    {
        return $this->response->getHeader($name);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getHeaderLine(string $name): string
    {
        return $this->response->getHeaderLine($name);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withHeader(string $name, $value): ResponseInterface
    {
        $new = clone $this;

        $value = is_array($value) ? $value : [$value];

        $new->response = $this->response->withHeader($name, ...$value);

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withAddedHeader(string $name, $value): ResponseInterface
    {
        $new = clone $this;

        $value = is_array($value) ? $value : [$value];

        $new->response = $this->response->withAddedHeader($name, ...$value);

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withoutHeader(string $name): ResponseInterface
    {
        $new = clone $this;

        $new->response = $this->response->withoutHeader($name);

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getBody(): StreamInterface
    {
        $stream = $this->response->getBody();

        return new Stream($stream);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withBody(StreamInterface $body): ResponseInterface
    {
        $new = clone $this;

        $stream        = StreamFactory::fromPsr($body);
        $new->response = $this->response->withBody($stream);

        $new->response->getBody()->rewind();

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getStatusCode(): int
    {
        return $this->response->getStatusCode()->value;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withStatus(int $code, string $reasonPhrase = ''): ResponseInterface
    {
        $new = clone $this;

        $new->response = $this->response->withStatus(StatusCode::from($code), $reasonPhrase);

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getReasonPhrase(): string
    {
        return $this->response->getReasonPhrase();
    }
}
