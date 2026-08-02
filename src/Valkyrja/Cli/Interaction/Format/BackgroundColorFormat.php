<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Cli\Interaction\Format;

use Valkyrja\Cli\Interaction\Enum\BackgroundColor;

class BackgroundColorFormat extends Format
{
    public function __construct(BackgroundColor $backgroundColor)
    {
        parent::__construct(
            (string) $backgroundColor->value,
            (string) $backgroundColor->getDefault()
        );
    }
}
