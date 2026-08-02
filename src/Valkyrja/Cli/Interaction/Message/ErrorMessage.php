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

use Valkyrja\Cli\Interaction\Formatter\ErrorFormatter;

class ErrorMessage extends Message
{
    /**
     * @param non-empty-string $text The text
     */
    public function __construct(string $text)
    {
        parent::__construct(
            text: $text,
            formatter: new ErrorFormatter()
        );
    }
}
