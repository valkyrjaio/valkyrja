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

interface CliNoInteractionConfigContract
{
    /** @var non-empty-string */
    public string $noInteractionOptionName {
        get;
    }

    /** @var non-empty-string */
    public string $noInteractionOptionShortName {
        get;
    }
}
