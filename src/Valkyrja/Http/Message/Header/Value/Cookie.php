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
use Valkyrja\Http\Message\Enum\SameSite;
use Valkyrja\Http\Message\Header\Value\Component\Component;
use Valkyrja\Http\Message\Header\Value\Contract\Cookie as Contract;
use Valkyrja\Support\Time\Time;

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
        protected ?string $value = null,
        protected int $expire = 0,
        protected string $path = '/',
        protected ?string $domain = null,
        protected bool $secure = false,
        protected bool $httpOnly = true,
        protected bool $raw = false,
        protected ?SameSite $sameSite = null,
        protected bool $delete = false
    ) {
        parent::__construct();
    }

    /**
     * @inheritDoc
     */
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
    public function delete(): static
    {
        $new = clone $this;

        $new->delete = true;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function getMaxAge(): int
    {
        return $this->expire > 0
            ? $this->expire - Time::get()
            : 0;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function withName(string $name): static
    {
        $new = clone $this;

        $new->name = $name;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @inheritDoc
     */
    public function withValue(?string $value = null): static
    {
        $new = clone $this;

        $new->value = $value;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function getExpire(): int
    {
        return $this->expire;
    }

    /**
     * @inheritDoc
     */
    public function withExpire(int $expire): static
    {
        $new = clone $this;

        $new->expire = $expire;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @inheritDoc
     */
    public function withPath(string $path): static
    {
        $new = clone $this;

        $new->path = $path;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function getDomain(): ?string
    {
        return $this->domain;
    }

    /**
     * @inheritDoc
     */
    public function withDomain(?string $domain = null): static
    {
        $new = clone $this;

        $new->domain = $domain;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function isSecure(): bool
    {
        return $this->secure;
    }

    /**
     * @inheritDoc
     */
    public function withSecure(bool $secure): static
    {
        $new = clone $this;

        $new->secure = $secure;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function isHttpOnly(): bool
    {
        return $this->httpOnly;
    }

    /**
     * @inheritDoc
     */
    public function withHttpOnly(bool $httpOnly = false): static
    {
        $new = clone $this;

        $new->httpOnly = $httpOnly;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function isRaw(): bool
    {
        return $this->raw;
    }

    /**
     * @inheritDoc
     */
    public function withRaw(bool $raw): static
    {
        $new = clone $this;

        $new->raw = $raw;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function getSameSite(): ?SameSite
    {
        return $this->sameSite;
    }

    /**
     * @inheritDoc
     */
    public function withSameSite(?SameSite $sameSite = null): static
    {
        $new = clone $this;

        $new->sameSite = $sameSite;

        return $new;
    }
}
