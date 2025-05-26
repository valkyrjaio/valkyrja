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

namespace Valkyrja\Console\Output\Contract;

use Valkyrja\Console\Enum\OutputStyle;
use Valkyrja\Console\Formatter\Contract\Formatter;

/**
 * Interface Output.
 *
 * @author Melech Mizrachi
 */
interface Output
{
    /**
     * Get the formatter.
     *
     * @return Formatter
     */
    public function getFormatter(): Formatter;

    /**
     * Set the formatter.
     *
     * @param Formatter $formatter
     *
     * @return void
     */
    public function setFormatter(Formatter $formatter): void;

    /**
     * Write messages to the console.
     *
     * @param string[]         $messages    The messages
     * @param bool             $newLine     [optional] Whether to use new lines between each message
     * @param OutputStyle|null $outputStyle [optional] The output style to use
     *
     * @return void
     */
    public function write(array $messages, bool|null $newLine = null, OutputStyle|null $outputStyle = null): void;

    /**
     * Write a message to the console.
     *
     * @param string           $message     The message
     * @param bool             $newLine     [optional] Whether to use new lines between each message
     * @param OutputStyle|null $outputStyle [optional] The output style to use
     *
     * @return void
     */
    public function writeMessage(
        string $message,
        bool|null $newLine = null,
        OutputStyle|null $outputStyle = null
    ): void;
}
