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

use Valkyrja\Session\Manager\Contract\SessionContract;

interface SessionConfigContract
{
    /** @var class-string<SessionContract> */
    public string $defaultSession {
        get;
    }

    /**
     * The id to give the session. Every session manager uses it.
     *
     * @var non-empty-string|null
     */
    public string|null $sessionId {
        get;
    }

    /**
     * The name to give the session. Every session manager uses it.
     *
     * @var non-empty-string|null
     */
    public string|null $sessionName {
        get;
    }
}
