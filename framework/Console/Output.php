<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Console;

use Valkyrja\Console\Enums\OutputStyle;
use Valkyrja\Contracts\Console\Output as OutputContract;
use Valkyrja\Contracts\Console\OutputFormatter;

/**
 * Class Output
 *
 * @package Valkyrja\Console
 *
 * @author  Melech Mizrachi
 */
class Output implements OutputContract
{
    /**
     * The formatter.
     *
     * @var \Valkyrja\Contracts\Console\OutputFormatter
     */
    protected $formatter;

    /**
     * Output constructor.
     *
     * @param \Valkyrja\Contracts\Console\OutputFormatter $formatter The output formatter
     */
    public function __construct(OutputFormatter $formatter)
    {
        $this->formatter = $formatter;
    }

    /**
     * Get the formatter.
     *
     * @return \Valkyrja\Contracts\Console\OutputFormatter
     */
    public function getFormatter(): OutputFormatter
    {
        return $this->formatter;
    }

    /**
     * Set the formatter.
     *
     * @param \Valkyrja\Contracts\Console\OutputFormatter $formatter
     *
     * @return void
     */
    public function setFormatter(OutputFormatter $formatter): void
    {
        $this->formatter = $formatter;
    }

    /**
     * Write messages to the console.
     *
     * @param array                               $messages    The messages
     * @param bool                                $newLine     [optional] Whether to use new lines between each message
     * @param \Valkyrja\Console\Enums\OutputStyle $outputStyle [optional] The output style to use
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     */
    public function write(array $messages, bool $newLine = null, OutputStyle $outputStyle = null): void
    {
        foreach ($messages as $message) {
            $this->writeMessage($message, $newLine, $outputStyle);
        }
    }

    /**
     * Write a message to the console.
     *
     * @param string                              $message     The message
     * @param bool                                $newLine     [optional] Whether to use new lines between each message
     * @param \Valkyrja\Console\Enums\OutputStyle $outputStyle [optional] The output style to use
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     */
    public function writeMessage(string $message, bool $newLine = null, OutputStyle $outputStyle = null): void
    {
        $newLine = $newLine ?? false;
        $outputStyle = $outputStyle ?? new OutputStyle(OutputStyle::NORMAL);

        switch ($outputStyle->getValue()) {
            case OutputStyle::NORMAL :
                $message = $this->formatter->format($message);
                break;
            case OutputStyle::RAW :
                break;
            case OutputStyle::PLAIN :
                $message = strip_tags($this->formatter->format($message));
                break;
        }

        $this->writeOut($message, $newLine);
    }

    /**
     * Write a message out to the console.
     *
     * @param string $message
     * @param bool   $newLine
     *
     * @return void
     */
    protected function writeOut(string $message, bool $newLine): void
    {
        echo $message;

        if ($newLine) {
            echo PHP_EOL;
        }
    }
}
