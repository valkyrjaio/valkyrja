<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Based off work by Fabien Potencier for symfony/http-foundation/Request.php
 */

namespace Valkyrja\Http;

use Valkyrja\Contracts\Http\Cookie;
use Valkyrja\Contracts\Http\Cookies as CookiesContract;

/**
 * Class Cookies
 *
 * @package Valkyrja\Http
 *
 * @author  Melech Mizrachi
 *
 * @property \Valkyrja\Contracts\Http\Cookie[] $collection
 *
 * @method get(string $key, $default = false): \Valkyrja\Contracts\Http\Cookie
 */
class Cookies implements CookiesContract
{
    /**
     * Array of cookies.
     *
     * @var array
     */
    protected $cookies = [];

    /**
     * Returns an array with all cookies.
     *
     * @param bool $asString [optional] Get the cookies as a string?
     *
     * @return \Valkyrja\Contracts\Http\Cookie[]
     */
    public function all(bool $asString = true): array
    {
        if (! $asString) {
            return $this->cookies;
        }

        $flattenedCookies = [];

        /** @var array $path */
        foreach ($this->cookies as $path) {
            /** @var array $cookies */
            foreach ($path as $cookies) {
                /** @var \Valkyrja\Contracts\Http\Cookie $cookie */
                foreach ($cookies as $cookie) {
                    $flattenedCookies[] = $cookie;
                }
            }
        }

        return $flattenedCookies;
    }

    /**
     * Set a response cookie.
     *
     * @param \Valkyrja\Contracts\Http\Cookie $cookie The cookie object
     *
     * @return \Valkyrja\Contracts\Http\Cookies
     */
    public function set(Cookie $cookie): CookiesContract
    {
        $this->cookies[$cookie->getDomain()][$cookie->getPath()][$cookie->getName()] = $cookie;

        return $this;
    }

    /**
     * Removes a cookie from the array, but does not unset it in the browser.
     *
     * @param string $name   Cookie name
     * @param string $path   [optional] Cookie path
     * @param string $domain [optional] Cookie domain
     *
     * @return \Valkyrja\Contracts\Http\Cookies
     */
    public function remove(string $name, string $path = '/', string $domain = null): CookiesContract
    {
        if (null === $path) {
            $path = '/';
        }

        unset($this->cookies[$domain][$path][$name]);

        if (empty($this->cookies[$domain][$path])) {
            unset($this->cookies[$domain][$path]);

            if (empty($this->cookies[$domain])) {
                unset($this->cookies[$domain]);
            }
        }

        return $this;
    }
}
