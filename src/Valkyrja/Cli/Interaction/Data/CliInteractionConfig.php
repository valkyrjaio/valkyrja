<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Cli\Interaction\Data;

use Valkyrja\Cli\Interaction\Data\Contract\CliInteractionConfigContract;

class CliInteractionConfig implements CliInteractionConfigContract
{
    public function __construct(
        public bool $isQuiet = false,
        public bool $isInteractive = true,
        public bool $isSilent = false,
    ) {
    }
}
