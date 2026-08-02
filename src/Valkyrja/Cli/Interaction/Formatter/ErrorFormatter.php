<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Cli\Interaction\Formatter;

use Valkyrja\Cli\Interaction\Enum\BackgroundColor;
use Valkyrja\Cli\Interaction\Enum\TextColor;
use Valkyrja\Cli\Interaction\Format\BackgroundColorFormat;
use Valkyrja\Cli\Interaction\Format\TextColorFormat;

class ErrorFormatter extends Formatter
{
    public function __construct()
    {
        parent::__construct(
            new TextColorFormat(TextColor::LIGHT_WHITE),
            new BackgroundColorFormat(BackgroundColor::RED),
        );
    }
}
