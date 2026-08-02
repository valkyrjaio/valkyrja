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

use Valkyrja\Cli\Interaction\Enum\TextColor;

class TextColorFormat extends Format
{
    public function __construct(TextColor $textColor)
    {
        parent::__construct(
            (string) $textColor->value,
            (string) $textColor->getDefault()
        );
    }
}
