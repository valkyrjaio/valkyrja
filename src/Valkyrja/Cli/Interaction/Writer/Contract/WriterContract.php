<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Cli\Interaction\Writer\Contract;

use Valkyrja\Cli\Interaction\Message\Contract\MessageContract;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;

interface WriterContract
{
    /**
     * Determine if this writer should write a given message.
     */
    public function shouldWriteMessage(MessageContract $message): bool;

    /**
     * Write a given message.
     *
     * @template O of OutputContract
     *
     * @param O $output The output
     *
     * @return O
     */
    public function write(OutputContract $output, MessageContract $message): OutputContract;
}
