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
