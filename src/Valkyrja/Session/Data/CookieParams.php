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

/**
 * Class CookieParams.
 *
 * @author Melech Mizrachi
 */
class CookieParams
{
    public function __construct(
        public string $path = '/',
        public string|null $domain = null,
        public int $lifetime = 0,
        public bool $secure = false,
        public bool $httpOnly = false,
        public SameSite $sameSite = SameSite::NONE,
    ) {
    }
}
