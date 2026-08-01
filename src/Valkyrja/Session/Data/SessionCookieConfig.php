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

namespace Valkyrja\Session\Data;

use Valkyrja\Http\Message\Enum\SameSite;
use Valkyrja\Session\Data\Contract\SessionCookieConfigContract;

class SessionCookieConfig implements SessionCookieConfigContract
{
    /**
     * @param non-empty-string      $cookiePath     The path the cookie is valid for
     * @param non-empty-string|null $cookieDomain   The domain the cookie is valid for
     * @param int                   $cookieLifetime The lifetime of the cookie
     * @param bool                  $cookieSecure   Whether the cookie needs a secure connection
     * @param bool                  $cookieHttpOnly Whether to hide the cookie from JavaScript
     * @param SameSite              $cookieSameSite The same site policy of the cookie
     */
    public function __construct(
        public readonly string $cookiePath = '/',
        public readonly string|null $cookieDomain = null,
        public readonly int $cookieLifetime = 0,
        public readonly bool $cookieSecure = false,
        public readonly bool $cookieHttpOnly = false,
        public readonly SameSite $cookieSameSite = SameSite::NONE,
    ) {
    }
}
