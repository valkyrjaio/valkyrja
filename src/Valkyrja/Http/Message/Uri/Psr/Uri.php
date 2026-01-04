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

namespace Valkyrja\Http\Message\Uri\Psr;

use Override;
use Psr\Http\Message\UriInterface;
use Valkyrja\Http\Message\Uri\Contract\UriContract;
use Valkyrja\Http\Message\Uri\Enum\Scheme;

class Uri implements UriInterface
{
    public function __construct(
        protected UriContract $uri,
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getScheme(): string
    {
        return $this->uri->getScheme()->value;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getAuthority(): string
    {
        return $this->uri->getAuthority();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getUserInfo(): string
    {
        return $this->uri->getUserInfo();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getHost(): string
    {
        return $this->uri->getHost();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getPort(): int|null
    {
        return $this->uri->getPort();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getPath(): string
    {
        return $this->uri->getPath();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getQuery(): string
    {
        return $this->uri->getQuery();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getFragment(): string
    {
        return $this->uri->getFragment();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withScheme(string $scheme): UriInterface
    {
        $new = clone $this;

        $new->uri = $this->uri->withScheme(Scheme::from($scheme));

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withUserInfo(string $user, string|null $password = null): UriInterface
    {
        $new = clone $this;

        $new->uri = $this->uri->withUserInfo($user, $password);

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withHost(string $host): UriInterface
    {
        $new = clone $this;

        $new->uri = $this->uri->withHost($host);

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withPort(int|null $port): UriInterface
    {
        $new = clone $this;

        $new->uri = $this->uri->withPort($port);

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withPath(string $path): UriInterface
    {
        $new = clone $this;

        $new->uri = $this->uri->withPath($path);

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withQuery(string $query): UriInterface
    {
        $new = clone $this;

        $new->uri = $this->uri->withQuery($query);

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withFragment(string $fragment): UriInterface
    {
        $new = clone $this;

        $new->uri = $this->uri->withFragment($fragment);

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->uri->__toString();
    }
}
