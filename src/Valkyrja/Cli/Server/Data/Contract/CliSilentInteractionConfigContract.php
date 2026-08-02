<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Cli\Server\Data\Contract;

interface CliSilentInteractionConfigContract
{
    /** @var non-empty-string */
    public string $silentOptionName {
        get;
    }

    /** @var non-empty-string */
    public string $silentOptionShortName {
        get;
    }
}
