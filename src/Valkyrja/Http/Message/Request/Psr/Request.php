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

namespace Valkyrja\Http\Message\Request\Psr;

use Override;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use Valkyrja\Http\Message\Enum\ProtocolVersion;
use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Message\Header\Factory\HeaderFactory;
use Valkyrja\Http\Message\Header\Header;
use Valkyrja\Http\Message\Request\Contract\RequestContract;
use Valkyrja\Http\Message\Stream\Factory\StreamFactory;
use Valkyrja\Http\Message\Stream\Psr\Stream;
use Valkyrja\Http\Message\Uri\Psr\Uri;
use Valkyrja\Http\Message\Uri\Uri as ValkyrjaUri;

use function is_array;

class Request implements RequestInterface
{
    public function __construct(
        protected RequestContract $request,
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getProtocolVersion(): string
    {
        return $this->request->getProtocolVersion()->value;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withProtocolVersion(string $version): RequestInterface
    {
        $new = clone $this;

        $new->request = $this->request->withProtocolVersion(ProtocolVersion::from($version));

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getHeaders(): array
    {
        return HeaderFactory::toPsr($this->request->getHeaders());
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function hasHeader(string $name): bool
    {
        return $this->request->hasHeader($name);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getHeader(string $name): array
    {
        $header = $this->request->getHeader($name);

        if ($header === null) {
            return [];
        }

        return HeaderFactory::toPsrValues($header);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getHeaderLine(string $name): string
    {
        return $this->request->getHeaderLine($name);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withHeader(string $name, $value): RequestInterface
    {
        $new = clone $this;

        $value = is_array($value) ? $value : [$value];

        $new->request = $this->request->withHeader(new Header($name, ...$value));

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withAddedHeader(string $name, $value): RequestInterface
    {
        $new = clone $this;

        $value = is_array($value) ? $value : [$value];

        $new->request = $this->request->withAddedHeader(new Header($name, ...$value));

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withoutHeader(string $name): RequestInterface
    {
        $new = clone $this;

        $new->request = $this->request->withoutHeader($name);

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getBody(): StreamInterface
    {
        $stream = $this->request->getBody();

        return new Stream($stream);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withBody(StreamInterface $body): RequestInterface
    {
        $new = clone $this;

        $stream       = StreamFactory::fromPsr($body);
        $new->request = $this->request->withBody($stream);

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getRequestTarget(): string
    {
        return $this->request->getRequestTarget();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withRequestTarget(string $requestTarget): RequestInterface
    {
        $new = clone $this;

        $new->request = $this->request->withRequestTarget($requestTarget);

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getMethod(): string
    {
        return $this->request->getMethod()->value;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withMethod(string $method): RequestInterface
    {
        $new = clone $this;

        $new->request = $this->request->withMethod(RequestMethod::from($method));

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getUri(): UriInterface
    {
        $uri = $this->request->getUri();

        return new Uri($uri);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withUri(UriInterface $uri, bool $preserveHost = false): RequestInterface
    {
        $new = clone $this;

        $uri          = ValkyrjaUri::fromString($uri->__toString());
        $new->request = $this->request->withUri($uri, $preserveHost);

        return $new;
    }
}
