<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Contracts\Console\Output;

use Valkyrja\Console\Enums\OutputStyle;

/**
 * Interface Output
 *
 * @package Valkyrja\Contract\Console\Output
 *
 * @author  Melech Mizrachi
 */
interface Output
{
    /**
     * Output constructor.
     *
     * @param \Valkyrja\Contracts\Console\Output\OutputFormatter $formatter The output formatter
     */
    public function __construct(OutputFormatter $formatter);

    /**
     * Get the formatter.
     *
     * @return \Valkyrja\Contracts\Console\Output\OutputFormatter
     */
    public function formatter(): OutputFormatter;

    /**
     * Set the formatter.
     *
     * @param \Valkyrja\Contracts\Console\Output\OutputFormatter $formatter
     *
     * @return void
     */
    public function setFormatter(OutputFormatter $formatter): void;

    /**
     * Write messages to the console.
     *
     * @param array                               $messages    The messages
     * @param bool                                $newLine     [optional] Whether to use new lines between each message
     * @param \Valkyrja\Console\Enums\OutputStyle $outputStyle [optional] The output style to use
     *
     * @return void
     */
    public function write(array $messages, bool $newLine = null, OutputStyle $outputStyle = null): void;

    /**
     * Write a message to the console.
     *
     * @param string                              $message     The message
     * @param bool                                $newLine     [optional] Whether to use new lines between each message
     * @param \Valkyrja\Console\Enums\OutputStyle $outputStyle [optional] The output style to use
     *
     * @return void
     */
    public function writeMessage(string $message, bool $newLine = null, OutputStyle $outputStyle = null): void;
}
