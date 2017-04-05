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
    const LAX = 'lax';
    const STRICT = 'strict';

    /**
     * Cookie constructor.
     *
     * @param string $name     The cookie's name
     * @param string $value    [optional] The cookie's value
     * @param int    $expire   [optional] The time the cookie should expire
     * @param string $path     [optional] The path the cookie is available to
     * @param string $domain   [optional] The domain the cookie is available to
     * @param bool   $secure   [optional] Whether the cookie should only be transmitted over a secure HTTPS connection
     * @param bool   $httpOnly [optional] Whether the cookie will be made accessible only through the HTTP protocol
     * @param bool   $raw      [optional] Whether the cookie value should be sent with no url encoding
     * @param string $sameSite [optional] Whether the cookie will be available for cross-site requests
     */
    public function __construct(
        string $name,
        string $value = null,
        int $expire = 0,
        string $path = '/',
        string $domain = null,
        bool $secure = false,
        bool $httpOnly = true,
        bool $raw = false,
        string $sameSite = null
    );

    /**
     * Returns the cookie as a string.
     *
     * @return string The cookie
     */
    public function __toString(): string;

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
     * Set the cookie's value.
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
     * Gets the max age of the cookie.
     *
     * @return int
     */
    public function getMaxAge(): int;

    /**
     * Set expire time for the cookie.
     *
     * @param int $expire The expire time
     *
     * @return \Valkyrja\Contracts\Http\Cookie
     */
    public function setExpire(int $expire): Cookie;

    /**
     * Get the path the cookie is available to.
     *
     * @return string
     */
    public function getPath(): string;

    /**
     * Set the path the cookie is available to.
     *
     * @param string $path The path
     *
     * @return \Valkyrja\Contracts\Http\Cookie
     */
    public function setPath(string $path): Cookie;

    /**
     * Get the domain the cookie is available to.
     *
     * @return string
     */
    public function getDomain(): string;

    /**
     * Set the domain the cookie is available to.
     *
     * @param string $domain The domain
     *
     * @return \Valkyrja\Contracts\Http\Cookie
     */
    public function setDomain(string $domain): Cookie;

    /**
     * Whether the cookie should only be transmitted over a secure HTTPS connection.
     *
     * @return bool
     */
    public function isSecure(): bool;

    /**
     * Set whether the cookie should only be transmitted over a secure HTTPS connection.
     *
     * @param bool $secure
     *
     * @return \Valkyrja\Contracts\Http\Cookie
     */
    public function setSecure(bool $secure): Cookie;

    /**
     * Whether the cookie will be made accessible only through the HTTP protocol.
     *
     * @return bool
     */
    public function isHttpOnly(): bool;

    /**
     * Set whether the cookie will be made accessible only through the HTTP protocol.
     *
     * @param bool $httpOnly
     *
     * @return \Valkyrja\Contracts\Http\Cookie
     */
    public function setHttpOnly(bool $httpOnly): Cookie;

    /**
     * Whether the cookie value should be sent with no url encoding.
     *
     * @return bool
     */
    public function isRaw(): bool;

    /**
     * Set whether the cookie value should be sent with no url encoding.
     *
     * @param bool $raw
     *
     * @return \Valkyrja\Contracts\Http\Cookie
     */
    public function setRaw(bool $raw): Cookie;

    /**
     * Get whether the cookie will be available for cross-site requests.
     *
     * @return string
     */
    public function getSameSite(): string;

    /**
     * Set whether the cookie will be available for cross-site requests.
     *
     * @param string $sameSite
     *
     * @return \Valkyrja\Contracts\Http\Cookie
     */
    public function setSameSite(string $sameSite): Cookie;
}
