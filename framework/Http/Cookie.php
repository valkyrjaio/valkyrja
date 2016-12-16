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
use Valkyrja\Http\Exceptions\InvalidSameSiteTypeException;

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
     * Whether the cookie will be available for cross-site requests.
     *
     * @var string
     */
    protected $sameSite;

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
     *
     * @throws \Valkyrja\Http\Exceptions\InvalidSameSiteTypeException
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
    )
    {
        $this->name = $name;
        $this->value = $value;
        $this->expire = $expire;
        $this->path = $path;
        $this->domain = $domain;
        $this->secure = $secure;
        $this->raw = $raw;

        if (! in_array($sameSite, [self::LAX, self::STRICT, null], true)) {
            throw new InvalidSameSiteTypeException('The "sameSite" parameter value is not valid.');
        }

        $this->sameSite = $sameSite;
    }

    /**
     * Returns the cookie as a string.
     *
     * @return string The cookie
     */
    public function __toString(): string
    {
        $str = urlencode($this->name) . '=';

        if ('' === $this->value) {
            $str .= 'deleted; expires=' . gmdate('D, d-M-Y H:i:s T', time() - 31536001) . '; max-age=-31536001';
        }
        else {
            $str .= urlencode($this->value);

            if ($this->expire !== 0) {
                $str .= '; expires=' . gmdate('D, d-M-Y H:i:s T', $this->expire) . '; max-age=' . $this->getMaxAge();
            }
        }

        if ($this->path) {
            $str .= '; path=' . $this->path;
        }

        if ($this->domain) {
            $str .= '; domain=' . $this->domain;
        }

        if (true === $this->secure) {
            $str .= '; secure';
        }

        if (true === $this->httpOnly) {
            $str .= '; httponly';
        }

        if (null !== $this->sameSite) {
            $str .= '; samesite=' . $this->sameSite;
        }

        return $str;
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
     * Set the cookie's value.
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
     * Gets the max age of the cookie.
     *
     * @return int
     */
    public function getMaxAge(): int
    {
        return 0 !== $this->expire
            ? $this->expire - time()
            : 0;
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
     * Get the path the cookie is available to.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Set the path the cookie is available to.
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
     * Get the domain the cookie is available to.
     *
     * @return string
     */
    public function getDomain(): string
    {
        return $this->domain;
    }

    /**
     * Set the domain the cookie is available to.
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
     * Whether the cookie should only be transmitted over a secure HTTPS connection.
     *
     * @return bool
     */
    public function isSecure(): bool
    {
        return $this->secure;
    }

    /**
     * Set whether the cookie should only be transmitted over a secure HTTPS connection.
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
     * Whether the cookie will be made accessible only through the HTTP protocol.
     *
     * @return bool
     */
    public function isHttpOnly(): bool
    {
        return $this->httpOnly;
    }

    /**
     * Set whether the cookie will be made accessible only through the HTTP protocol.
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
     * Whether the cookie value should be sent with no url encoding.
     *
     * @return bool
     */
    public function isRaw(): bool
    {
        return $this->raw;
    }

    /**
     * Set whether the cookie value should be sent with no url encoding.
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

    /**
     * Get whether the cookie will be available for cross-site requests.
     *
     * @return string
     */
    public function getSameSite(): string
    {
        return $this->sameSite;
    }

    /**
     * Set whether the cookie will be available for cross-site requests.
     *
     * @param string $sameSite
     *
     * @return \Valkyrja\Contracts\Http\Cookie
     */
    public function setSameSite(string $sameSite): CookieContract
    {
        $this->sameSite = $sameSite;

        return $this;
    }
}
