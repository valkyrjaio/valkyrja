<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Cli\Interaction\Output;

use Override;
use Valkyrja\Cli\Interaction\Message\Contract\MessageContract;
use Valkyrja\Cli\Interaction\Output\Contract\EmptyOutputContract;

class EmptyOutput extends Output implements EmptyOutputContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    protected function outputMessage(MessageContract $message): void
    {
        // Empty on purpose
    }
}
