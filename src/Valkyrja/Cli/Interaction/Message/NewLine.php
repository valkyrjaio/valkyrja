<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Cli\Interaction\Message;

use Valkyrja\Cli\Interaction\Formatter\Contract\FormatterContract;

class NewLine extends Message
{
    public function __construct(
        FormatterContract|null $formatter = null
    ) {
        parent::__construct("\n", $formatter);
    }
}
