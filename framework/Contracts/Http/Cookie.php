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

namespace Valkyrja\Contracts\Http;

/**
 * Interface Cookie
 *
 * @package Valkyrja\Contracts\Http
 *
 * @author  Melech Mizrachi
 */
interface Cookie
{
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
    );

    /**
     * Get the cookie's name.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Set the cookie's name.
     *
     * @param string $name The name
     *
     * @return \Valkyrja\Contracts\Http\Cookie
     */
    public function setName(string $name): Cookie;

    /**
     * Get the cookie's value.
     *
     * @return string
     */
    public function getValue(): string;

    /**
     * Set the cookie's value
     *
     * @param string $value The value
     *
     * @return \Valkyrja\Contracts\Http\Cookie
     */
    public function setValue(string $value): Cookie;

    /**
     * Get expire time for the cookie.
     *
     * @return int
     */
    public function getExpire(): int;

    /**
     * Set expire time for the cookie.
     *
     * @param int $expire The expire time
     *
     * @return \Valkyrja\Contracts\Http\Cookie
     */
    public function setExpire(int $expire): Cookie;

    /**
     * Get cookie's path.
     *
     * @return string
     */
    public function getPath(): string;

    /**
     * Set cookie's path.
     *
     * @param string $path The path
     *
     * @return \Valkyrja\Contracts\Http\Cookie
     */
    public function setPath(string $path): Cookie;

    /**
     * Get cookie's domain.
     *
     * @return string
     */
    public function getDomain(): string;

    /**
     * Set cookie's domain.
     *
     * @param string $domain The domain
     *
     * @return \Valkyrja\Contracts\Http\Cookie
     */
    public function setDomain(string $domain): Cookie;

    /**
     * Is the cookie to be set on a secure server?
     *
     * @return bool
     */
    public function isSecure(): bool;

    /**
     * Set whether the cookie is set on a secure server.
     *
     * @param bool $secure
     *
     * @return \Valkyrja\Contracts\Http\Cookie
     */
    public function setSecure(bool $secure): Cookie;

    /**
     * Is the cookie http only?
     *
     * @return bool
     */
    public function isHttpOnly(): bool;

    /**
     * Set whether the cookie is http only.
     *
     * @param bool $httpOnly
     *
     * @return \Valkyrja\Contracts\Http\Cookie
     */
    public function setHttpOnly(bool $httpOnly): Cookie;

    /**
     * Is this a raw cookie?
     *
     * @return bool
     */
    public function isRaw(): bool;

    /**
     * Set whether the cookie is raw.
     *
     * @param bool $raw
     *
     * @return \Valkyrja\Contracts\Http\Cookie
     */
    public function setRaw(bool $raw): Cookie;
}
