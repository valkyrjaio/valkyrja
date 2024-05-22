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

namespace Valkyrja\Http\Uri\Psr;

use Psr\Http\Message\UriInterface;
use Valkyrja\Http\Uri\Contract\Uri as ValkyrjaUri;

/**
 * Class Uri.
 *
 * @author Melech Mizrachi
 */
class Uri implements UriInterface
{
    public function __construct(
        protected ValkyrjaUri $uri,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getScheme(): string
    {
        return $this->uri->getScheme();
    }

    /**
     * @inheritDoc
     */
    public function getAuthority(): string
    {
        return $this->uri->getAuthority();
    }

    /**
     * @inheritDoc
     */
    public function getUserInfo(): string
    {
        return $this->uri->getUserInfo();
    }

    /**
     * @inheritDoc
     */
    public function getHost(): string
    {
        return $this->uri->getHost();
    }

    /**
     * @inheritDoc
     */
    public function getPort(): ?int
    {
        return $this->uri->getPort();
    }

    /**
     * @inheritDoc
     */
    public function getPath(): string
    {
        return $this->uri->getPath();
    }

    /**
     * @inheritDoc
     */
    public function getQuery(): string
    {
        return $this->uri->getQuery();
    }

    /**
     * @inheritDoc
     */
    public function getFragment(): string
    {
        return $this->uri->getFragment();
    }

    /**
     * @inheritDoc
     */
    public function withScheme(string $scheme): UriInterface
    {
        $new = clone $this;

        $new->uri = $this->uri->withScheme($scheme);

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function withUserInfo(string $user, ?string $password = null): UriInterface
    {
        $new = clone $this;

        $new->uri = $this->uri->withUserInfo($user, $password);

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function withHost(string $host): UriInterface
    {
        $new = clone $this;

        $new->uri = $this->uri->withHost($host);

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function withPort(?int $port): UriInterface
    {
        $new = clone $this;

        $new->uri = $this->uri->withPort($port);

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function withPath(string $path): UriInterface
    {
        $new = clone $this;

        $new->uri = $this->uri->withPath($path);

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function withQuery(string $query): UriInterface
    {
        $new = clone $this;

        $new->uri = $this->uri->withQuery($query);

        return $new;
    }

    /**
     * @inheritDoc
     */
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
