<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Cli\Interaction\Data\Contract;

interface CliInteractionConfigContract
{
    public bool $isQuiet {
        get;
        set;
    }
    public bool $isInteractive {
        get;
        set;
    }
    public bool $isSilent {
        get;
        set;
    }
}
