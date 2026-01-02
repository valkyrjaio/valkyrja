<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
