<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Based off work by Fabien Potencier for symfony/http-foundation/Response.php
 */

namespace Valkyrja\Http;

use Valkyrja\Contracts\Http\Cookie as CookieContract;

/**
 * Class Cookie
 *
 * @package Valkyrja\Http
 *
 * @author  Melech Mizrachi
 */
class Cookie implements CookieContract
{
    /**
     * The cookie name.
     *
     * @var string
     */
    protected $name;

    /**
     * The cookie value.
     *
     * @var string
     */
    protected $value;

    /**
     * The cookie expire time.
     *
     * @var int
     */
    protected $expire;

    /**
     * The cookie path.
     *
     * @var string
     */
    protected $path;

    /**
     * The cookie domain.
     *
     * @var string
     */
    protected $domain;

    /**
     * Whether the cookie is secure.
     *
     * @var bool
     */
    protected $secure;

    /**
     * Whether the cookie is http only.
     *
     * @var bool
     */
    protected $httpOnly;

    /**
     * Whether the cookie is raw.
     *
     * @var bool
     */
    protected $raw;

    /**
     * Cookie constructor.
     *
     * @param string $name
     * @param string $value
     * @param int    $expire
     * @param string $path
     * @param string $domain
     * @param bool   $secure
     * @param bool   $httpOnly
     * @param bool   $raw
     */
    public function __construct(
        string $name,
        string $value = null,
        int $expire = 0,
        string $path = '/',
        string $domain = null,
        bool $secure = false,
        bool $httpOnly = true,
        bool $raw = false
    )
    {
        $this->name = $name;
        $this->value = $value;
        $this->expire = $expire;
        $this->path = $path;
        $this->domain = $domain;
        $this->secure = $secure;
        $this->raw = $raw;
    }

    /**
     * Get the cookie's name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the cookie's name.
     *
     * @param string $name The name
     *
     * @return \Valkyrja\Contracts\Http\Cookie
     */
    public function setName(string $name): CookieContract
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the cookie's value.
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Set the cookie's value
     *
     * @param string $value The value
     *
     * @return \Valkyrja\Contracts\Http\Cookie
     */
    public function setValue(string $value): CookieContract
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get expire time for the cookie.
     *
     * @return int
     */
    public function getExpire(): int
    {
        return $this->expire;
    }

    /**
     * Set expire time for the cookie.
     *
     * @param int $expire The expire time
     *
     * @return \Valkyrja\Contracts\Http\Cookie
     */
    public function setExpire(int $expire): CookieContract
    {
        $this->expire = $expire;

        return $this;
    }

    /**
     * Get cookie's path.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Set cookie's path.
     *
     * @param string $path The path
     *
     * @return \Valkyrja\Contracts\Http\Cookie
     */
    public function setPath(string $path): CookieContract
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get cookie's domain.
     *
     * @return string
     */
    public function getDomain(): string
    {
        return $this->domain;
    }

    /**
     * Set cookie's domain.
     *
     * @param string $domain The domain
     *
     * @return \Valkyrja\Contracts\Http\Cookie
     */
    public function setDomain(string $domain): CookieContract
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * Is the cookie to be set on a secure server?
     *
     * @return bool
     */
    public function isSecure(): bool
    {
        return $this->secure;
    }

    /**
     * Set whether the cookie is set on a secure server.
     *
     * @param bool $secure
     *
     * @return \Valkyrja\Contracts\Http\Cookie
     */
    public function setSecure(bool $secure): CookieContract
    {
        $this->secure = $secure;

        return $this;
    }

    /**
     * Is the cookie http only?
     *
     * @return bool
     */
    public function isHttpOnly(): bool
    {
        return $this->httpOnly;
    }

    /**
     * Set whether the cookie is http only.
     *
     * @param bool $httpOnly
     *
     * @return \Valkyrja\Contracts\Http\Cookie
     */
    public function setHttpOnly(bool $httpOnly): CookieContract
    {
        $this->httpOnly = $httpOnly;

        return $this;
    }

    /**
     * Is this a raw cookie?
     *
     * @return bool
     */
    public function isRaw(): bool
    {
        return $this->raw;
    }

    /**
     * Set whether the cookie is raw.
     *
     * @param bool $raw
     *
     * @return \Valkyrja\Contracts\Http\Cookie
     */
    public function setRaw(bool $raw): CookieContract
    {
        $this->raw = $raw;

        return $this;
    }
}
