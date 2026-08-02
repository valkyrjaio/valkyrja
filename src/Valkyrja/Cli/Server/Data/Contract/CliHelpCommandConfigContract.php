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

interface CliHelpCommandConfigContract
{
    /** @var non-empty-string */
    public string $helpCommandName {
        get;
    }

    /** @var non-empty-string */
    public string $helpOptionName {
        get;
    }

    /** @var non-empty-string */
    public string $helpOptionShortName {
        get;
    }
}
