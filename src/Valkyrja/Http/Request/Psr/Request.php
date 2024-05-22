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

namespace Valkyrja\Http\Request\Psr;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use Valkyrja\Http\Request\Contract\Request as ValkyrjaRequest;
use Valkyrja\Http\Stream\Psr\Stream;
use Valkyrja\Http\Stream\Stream as ValkyrjaStream;
use Valkyrja\Http\Uri\Psr\Uri;
use Valkyrja\Http\Uri\Uri as ValkyrjaUri;

use function is_array;

/**
 * Class Request.
 *
 * @author Melech Mizrachi
 */
class Request implements RequestInterface
{
    public function __construct(
        protected ValkyrjaRequest $request,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getProtocolVersion(): string
    {
        return $this->request->getProtocolVersion();
    }

    /**
     * @inheritDoc
     */
    public function withProtocolVersion(string $version): RequestInterface
    {
        $new = clone $this;

        $new->request = $this->request->withProtocolVersion($version);

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function getHeaders(): array
    {
        return $this->request->getHeaders();
    }

    /**
     * @inheritDoc
     */
    public function hasHeader(string $name): bool
    {
        return $this->request->hasHeader($name);
    }

    /**
     * @inheritDoc
     */
    public function getHeader(string $name): array
    {
        return $this->request->getHeader($name);
    }

    /**
     * @inheritDoc
     */
    public function getHeaderLine(string $name): string
    {
        return $this->request->getHeaderLine($name);
    }

    /**
     * @inheritDoc
     */
    public function withHeader(string $name, $value): RequestInterface
    {
        $new = clone $this;

        $value = is_array($value) ? $value : [$value];

        $new->request = $this->request->withHeader($name, ...$value);

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function withAddedHeader(string $name, $value): RequestInterface
    {
        $new = clone $this;

        $value = is_array($value) ? $value : [$value];

        $new->request = $this->request->withAddedHeader($name, ...$value);

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function withoutHeader(string $name): RequestInterface
    {
        $new = clone $this;

        $new->request = $this->request->withoutHeader($name);

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function getBody(): StreamInterface
    {
        $stream = $this->request->getBody();

        return new Stream($stream);
    }

    /**
     * @inheritDoc
     */
    public function withBody(StreamInterface $body): RequestInterface
    {
        $new = clone $this;

        $mode = '';

        if ($body->isReadable()) {
            $mode = 'r';
        }

        if ($body->isWritable()) {
            $mode .= 'w';
        }
        $stream       = new ValkyrjaStream($body->getContents(), $mode . 'b');
        $new->request = $this->request->withBody($stream);

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function getRequestTarget(): string
    {
        return $this->request->getRequestTarget();
    }

    /**
     * @inheritDoc
     */
    public function withRequestTarget(string $requestTarget): RequestInterface
    {
        $new = clone $this;

        $new->request = $this->request->withRequestTarget($requestTarget);

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function getMethod(): string
    {
        return $this->request->getMethod();
    }

    /**
     * @inheritDoc
     */
    public function withMethod(string $method): RequestInterface
    {
        $new = clone $this;

        $new->request = $this->request->withMethod($method);

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function getUri(): UriInterface
    {
        $uri = $this->request->getUri();

        return new Uri($uri);
    }

    /**
     * @inheritDoc
     */
    public function withUri(UriInterface $uri, bool $preserveHost = false): RequestInterface
    {
        $new = clone $this;

        $uri          = new ValkyrjaUri($uri->__toString());
        $new->request = $this->request->withUri($uri, $preserveHost);

        return $new;
    }
}
