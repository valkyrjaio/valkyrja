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
