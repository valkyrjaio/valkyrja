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

interface SessionTokenConfigContract
{
    /** @var non-empty-string|null */
    public string|null $tokenOptionName {
        get;
    }

    /** @var non-empty-string|null */
    public string|null $tokenHeaderName {
        get;
    }
}
