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
use Valkyrja\Cli\Interaction\Output\Contract\PlainOutputContract;

use function strip_tags;

class PlainOutput extends Output implements PlainOutputContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    protected function outputMessage(MessageContract $message): void
    {
        echo strip_tags($message->getText());
    }
}
