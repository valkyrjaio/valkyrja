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

namespace Valkyrja\Http\Message\Header\Value;

use DateTimeInterface;
use Override;
use Valkyrja\Http\Message\Enum\SameSite;
use Valkyrja\Http\Message\Header\Value\Component\Component;
use Valkyrja\Http\Message\Header\Value\Contract\Cookie as Contract;
use Valkyrja\Support\Time;

use function array_filter;
use function gmdate;
use function implode;
use function urlencode;

/**
 * Class Cookie.
 *
 * @author Melech Mizrachi
 */
class Cookie extends Value implements Contract
{
    /**
     * Cookie constructor.
     *
     * @param string        $name     The cookie's name
     * @param string|null   $value    [optional] The cookie's value
     * @param int           $expire   [optional] The time the cookie should expire
     * @param string        $path     [optional] The path the cookie is available to
     * @param string|null   $domain   [optional] The domain the cookie is available to
     * @param bool          $secure   [optional] Whether the cookie should only be
     *                                transmitted over a secure HTTPS connection
     * @param bool          $httpOnly [optional] Whether the cookie will be made
     *                                accessible only through the HTTP protocol
     * @param bool          $raw      [optional] Whether the cookie value should be
     *                                sent with no url encoding
     * @param SameSite|null $sameSite [optional] Whether the cookie will be available
     *                                for cross-site requests
     */
    public function __construct(
        protected string $name,
        protected string|null $value = null,
        protected int $expire = 0,
        protected string $path = '/',
        protected string|null $domain = null,
        protected bool $secure = false,
        protected bool $httpOnly = true,
        protected bool $raw = false,
        protected SameSite|null $sameSite = null,
        protected bool $delete = false
    ) {
        parent::__construct();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function __toString(): string
    {
        $value  = $this->value;
        $expire = $this->expire;
        $maxAge = $this->getMaxAge();

        if ($this->delete || $value === null) {
            $expire = Time::get() - 31536001;
            $maxAge = -31536001;
            $value  = 'delete';
        }

        $arr = [
            new Component(urlencode($this->name), urlencode($value)),
        ];

        if ($expire !== 0) {
            $arr[] = new Component('expires', gmdate(DateTimeInterface::COOKIE, $expire));
            $arr[] = new Component('max-age', (string) $maxAge);
        }

        $arr[] = new Component('path', $this->path);
        $arr[] = $this->domain !== null ? new Component('domain', $this->domain) : '';
        $arr[] = $this->secure ? new Component('secure') : '';
        $arr[] = $this->httpOnly ? new Component('httponly') : '';
        $arr[] = $this->sameSite ? new Component('samesite', $this->sameSite->value) : '';

        $arrToString = array_map('strval', $arr);

        $arrFilteredEmptyStrings = array_filter($arrToString);

        return implode('; ', $arrFilteredEmptyStrings);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function delete(): static
    {
        $new = clone $this;

        $new->delete = true;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getMaxAge(): int
    {
        return $this->expire > 0
            ? $this->expire - Time::get()
            : 0;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withName(string $name): static
    {
        $new = clone $this;

        $new->name = $name;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getValue(): string|null
    {
        return $this->value;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withValue(string|null $value = null): static
    {
        $new = clone $this;

        $new->value = $value;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getExpire(): int
    {
        return $this->expire;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withExpire(int $expire): static
    {
        $new = clone $this;

        $new->expire = $expire;

        return $new;
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
    public function withPath(string $path): static
    {
        $new = clone $this;

        $new->path = $path;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getDomain(): string|null
    {
        return $this->domain;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withDomain(string|null $domain = null): static
    {
        $new = clone $this;

        $new->domain = $domain;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function isSecure(): bool
    {
        return $this->secure;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withSecure(bool $secure): static
    {
        $new = clone $this;

        $new->secure = $secure;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function isHttpOnly(): bool
    {
        return $this->httpOnly;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withHttpOnly(bool $httpOnly = false): static
    {
        $new = clone $this;

        $new->httpOnly = $httpOnly;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function isRaw(): bool
    {
        return $this->raw;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withRaw(bool $raw): static
    {
        $new = clone $this;

        $new->raw = $raw;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getSameSite(): SameSite|null
    {
        return $this->sameSite;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withSameSite(SameSite|null $sameSite = null): static
    {
        $new = clone $this;

        $new->sameSite = $sameSite;

        return $new;
    }
}
