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

use Valkyrja\Cli\Interaction\Enum\Style;

class StyleFormat extends Format
{
    public function __construct(Style $style)
    {
        parent::__construct(
            (string) $style->value,
            (string) $style->getDefault()
        );
    }
}
