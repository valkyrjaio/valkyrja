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

namespace Valkyrja\Contracts\Http;

/**
 * Interface Cookies.
 *
 * @author Melech Mizrachi
 */
interface Cookies
{
    /**
     * Returns an array with all cookies.
     *
     * @param bool $asString [optional] Get the cookies as a string?
     *
     * @return \Valkyrja\Contracts\Http\Cookie[]
     */
    public function all(bool $asString = true): array;

    /**
     * Set a response cookie.
     *
     * @param \Valkyrja\Contracts\Http\Cookie $cookie The cookie object
     *
     * @return \Valkyrja\Contracts\Http\Cookies
     */
    public function set(Cookie $cookie): self;

    /**
     * Removes a cookie from the array, but does not unset it in the browser.
     *
     * @param string $name   Cookie name
     * @param string $path   [optional] Cookie path
     * @param string $domain [optional] Cookie domain
     *
     * @return \Valkyrja\Contracts\Http\Cookies
     */
    public function remove(string $name, string $path = '/', string $domain = null): self;
}
