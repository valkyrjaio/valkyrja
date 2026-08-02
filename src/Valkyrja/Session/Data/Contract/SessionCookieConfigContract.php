<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
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
