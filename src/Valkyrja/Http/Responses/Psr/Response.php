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

namespace Valkyrja\Http\Responses\Psr;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Valkyrja\Http\Response as ValkyrjaResponse;
use Valkyrja\Http\Streams\Psr\Stream;
use Valkyrja\Http\Streams\Stream as ValkyrjaStream;

/**
 * Class Response.
 *
 * @author Melech Mizrachi
 */
class Response implements ResponseInterface
{
    public function __construct(
        protected ValkyrjaResponse $response,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getProtocolVersion(): string
    {
        return $this->response->getProtocolVersion();
    }

    /**
     * @inheritDoc
     */
    public function withProtocolVersion(string $version): ResponseInterface
    {
        $new = clone $this;

        $new->response = $this->response->withProtocolVersion($version);

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function getHeaders(): array
    {
        return $this->response->getHeaders();
    }

    /**
     * @inheritDoc
     */
    public function hasHeader(string $name): bool
    {
        return $this->response->hasHeader($name);
    }

    /**
     * @inheritDoc
     */
    public function getHeader(string $name): array
    {
        return $this->response->getHeader($name);
    }

    /**
     * @inheritDoc
     */
    public function getHeaderLine(string $name): string
    {
        return $this->response->getHeaderLine($name);
    }

    /**
     * @inheritDoc
     */
    public function withHeader(string $name, $value): ResponseInterface
    {
        $new = clone $this;

        $new->response = $this->response->withHeader($name, $value);

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function withAddedHeader(string $name, $value): ResponseInterface
    {
        $new = clone $this;

        $new->response = $this->response->withAddedHeader($name, $value);

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function withoutHeader(string $name): ResponseInterface
    {
        $new = clone $this;

        $new->response = $this->response->withoutHeader($name);

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function getBody(): StreamInterface
    {
        $stream = $this->response->getBody();

        return new Stream($stream);
    }

    /**
     * @inheritDoc
     */
    public function withBody(StreamInterface $body): ResponseInterface
    {
        $new = clone $this;

        $mode = '';
        if ($body->isReadable()) {
            $mode = 'r';
        }
        if ($body->isWritable()) {
            $mode .= 'w';
        }
        $stream        = new ValkyrjaStream($body->getContents(), $mode . 'b');
        $new->response = $this->response->withBody($stream);

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function getStatusCode(): int
    {
        return $this->response->getStatusCode();
    }

    /**
     * @inheritDoc
     */
    public function withStatus(int $code, string $reasonPhrase = ''): ResponseInterface
    {
        $new = clone $this;

        $new->response = $this->response->withStatus($code, $reasonPhrase);

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function getReasonPhrase(): string
    {
        return $this->response->getReasonPhrase();
    }
}
