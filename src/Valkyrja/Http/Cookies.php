<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Http;

/**
 * Class Cookies.
 *
 * @author Melech Mizrachi
 *
 * @property Cookie[] $collection
 *
 * @method get(string $key, $default = false): Cookie
 */
class Cookies
{
    /**
     * Array of cookies.
     *
     * @var Cookie[]
     */
    protected array $cookies = [];

    /**
     * Returns an array with all cookies.
     *
     * @param bool $asString [optional] Get the cookies as a string?
     *
     * @return Cookie[]
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
                /** @var Cookie $cookie */
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
     * @param Cookie $cookie The cookie object
     *
     * @return Cookies
     */
    public function set(Cookie $cookie): self
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
     * @return Cookies
     */
    public function remove(string $name, string $path = '/', string $domain = null): self
    {
        $path = $path ?? '/';

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
