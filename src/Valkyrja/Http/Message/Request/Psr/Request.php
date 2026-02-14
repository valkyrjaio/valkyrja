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
use Valkyrja\Http\Message\Header\Factory\PsrHeaderFactory;
use Valkyrja\Http\Message\Header\Header;
use Valkyrja\Http\Message\Request\Contract\RequestContract;
use Valkyrja\Http\Message\Stream\Factory\PsrStreamFactory;
use Valkyrja\Http\Message\Stream\Psr\Stream;
use Valkyrja\Http\Message\Uri\Factory\UriFactory;
use Valkyrja\Http\Message\Uri\Psr\Uri;

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
        return PsrHeaderFactory::toPsr($this->request->getHeaders());
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function hasHeader(string $name): bool
    {
        if ($name === '') {
            return false;
        }

        return $this->request->getHeaders()->has($name);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getHeader(string $name): array
    {
        if ($name === '') {
            return [];
        }

        $header = $this->request->getHeaders()->get($name);

        if ($header === null) {
            return [];
        }

        return PsrHeaderFactory::toPsrValues($header);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getHeaderLine(string $name): string
    {
        if ($name === '') {
            return '';
        }

        return $this->request->getHeaders()->getHeaderLine($name);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withHeader(string $name, $value): RequestInterface
    {
        $new = clone $this;

        $value = is_array($value) ? $value : [$value];

        $new->request = $this->request->withHeaders(
            $this->request->getHeaders()->withHeader(new Header($name, ...$value))
        );

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

        $new->request = $this->request->withHeaders(
            $this->request->getHeaders()->withAddedHeaders(new Header($name, ...$value))
        );

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withoutHeader(string $name): RequestInterface
    {
        $new = clone $this;

        if ($name === '') {
            return $new;
        }

        $new->request = $this->request->withHeaders(
            $this->request->getHeaders()->withoutHeader($name)
        );

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

        $stream       = PsrStreamFactory::fromPsr($body);
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

        $new->request = $this->request->withUri(
            UriFactory::fromString($uri->__toString()),
            $preserveHost
        );

        return $new;
    }
}
