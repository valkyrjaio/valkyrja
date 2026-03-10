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

namespace Valkyrja\Http\Message\Uri;

use Override;
use Valkyrja\Http\Message\Uri\Contract\UriContract;
use Valkyrja\Http\Message\Uri\Enum\Scheme;
use Valkyrja\Http\Message\Uri\Factory\UriFactory;
use Valkyrja\Http\Message\Uri\Throwable\Exception\InvalidPathException;
use Valkyrja\Http\Message\Uri\Throwable\Exception\InvalidPortException;
use Valkyrja\Http\Message\Uri\Throwable\Exception\InvalidQueryException;

use function strtolower;

class Uri implements UriContract
{
    /** @var string */
    protected string $userInfo;

    /**
     * The URI as a string.
     *
     * @var string|null
     */
    private string|null $uriString = null;

    /**
     * @param Scheme $scheme   [optional] The scheme
     * @param string $username [optional] The username
     * @param string $password [optional] The user password
     * @param string $host     [optional] The host
     * @param int    $port     [optional] The port
     * @param string $path     [optional] The path
     * @param string $query    [optional] The query
     * @param string $fragment [optional] The fragment
     *
     * @throws InvalidPathException
     * @throws InvalidPortException
     * @throws InvalidQueryException
     */
    public function __construct(
        protected Scheme $scheme = Scheme::EMPTY,
        protected string $username = '',
        protected string $password = '',
        protected string $host = '',
        protected int $port = 0,
        protected string $path = '',
        protected string $query = '',
        protected string $fragment = ''
    ) {
        $userInfo = $username;

        if ($password !== '') {
            $userInfo .= ':' . $password;
        }

        if ($port === 0) {
            $port = $this->getPortFromScheme($scheme);
        } else {
            UriFactory::validatePort($port);
        }

        $this->port     = $port;
        $this->userInfo = UriFactory::filterUserInfo($userInfo);
        $this->host     = strtolower($host);
        $this->path     = UriFactory::filterPath($path);
        $this->query    = UriFactory::filterQuery($query);
        $this->fragment = UriFactory::filterFragment($fragment);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function isSecure(): bool
    {
        return $this->scheme === Scheme::HTTPS;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getScheme(): Scheme
    {
        return $this->scheme;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getAuthority(): string
    {
        if (empty($this->host)) {
            return '';
        }

        $authority = $this->host;

        if (! empty($this->userInfo)) {
            $authority = $this->userInfo . '@' . $authority;
        }

        if (! UriFactory::isStandardPort($this->scheme, $this->host, $this->port)) {
            $authority .= ':' . ((string) $this->port);
        }

        return $authority;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getUserInfo(): string
    {
        return $this->userInfo;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function hasPort(): bool
    {
        return $this->port !== 0;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getPort(): int
    {
        return UriFactory::isStandardPort($this->scheme, $this->host, $this->port)
            ? 0
            : $this->port;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getHostPort(): string
    {
        $host = $this->host;
        $port = $this->getPort();

        if ($host !== '' && $port !== 0) {
            $host .= ':' . ((string) $port);
        }

        return $host;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getSchemeHostPort(): string
    {
        $hostPort = $this->getHostPort();
        $scheme   = $this->scheme;

        return $hostPort && $scheme !== Scheme::EMPTY
            ? $scheme->value . '://' . $hostPort
            : $hostPort;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getQuery(): string
    {
        return $this->query;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getFragment(): string
    {
        return $this->fragment;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withScheme(Scheme $scheme): static
    {
        $new = clone $this;

        $new->scheme = $scheme;

        if ($this->port === 0) {
            $new->port = $this->getPortFromScheme($scheme);
        }

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withUsername(string $username): static
    {
        return $this->withUserInfo($username, $this->password);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withPassword(string $password): static
    {
        return $this->withUserInfo($this->username, $password);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withUserInfo(string $user, string $password = ''): static
    {
        $info = $user;

        if (empty($user)) {
            $password = '';
        }

        if ($password !== '') {
            $info .= ':' . $password;
        }

        $new = clone $this;

        $new->userInfo = $info;
        $new->username = $user;
        $new->password = $password;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withHost(string $host): static
    {
        $new = clone $this;

        $new->host = $host;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withPort(int $port): static
    {
        UriFactory::validatePort($port);

        $new = clone $this;

        $new->port = $port;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withPath(string $path): static
    {
        $path = UriFactory::filterPath($path);

        $new = clone $this;

        $new->path = $path;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withQuery(string $query): static
    {
        $query = UriFactory::filterQuery($query);

        $new = clone $this;

        $new->query = $query;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withFragment(string $fragment): static
    {
        $fragment = UriFactory::filterFragment($fragment);

        $new = clone $this;

        $new->fragment = $fragment;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->uriString
            ??= UriFactory::toString($this);
    }

    /**
     * Clone the object.
     */
    public function __clone()
    {
        $this->uriString = null;
    }

    /**
     * Get the port from the scheme.
     */
    protected function getPortFromScheme(Scheme $scheme): int
    {
        return match ($scheme) {
            Scheme::HTTPS => 443,
            Scheme::HTTP  => 80,
            default       => 0,
        };
    }
}
