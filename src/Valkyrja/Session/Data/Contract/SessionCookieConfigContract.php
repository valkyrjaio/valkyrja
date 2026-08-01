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

namespace Valkyrja\Session\Data\Contract;

use Valkyrja\Http\Message\Enum\SameSite;

interface SessionCookieConfigContract
{
    /** @var non-empty-string */
    public string $cookiePath {
        get;
    }

    /** @var non-empty-string|null */
    public string|null $cookieDomain {
        get;
    }

    public int $cookieLifetime {
        get;
    }

    public bool $cookieSecure {
        get;
    }

    public bool $cookieHttpOnly {
        get;
    }

    public SameSite $cookieSameSite {
        get;
    }
}
