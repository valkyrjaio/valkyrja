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

namespace Valkyrja\Console\Output;

use Valkyrja\Console\Enum\OutputStyle;
use Valkyrja\Console\Formatter\Contract\Formatter;
use Valkyrja\Console\Formatter\Formatter as FormatterClass;
use Valkyrja\Console\Output\Contract\Output as Contract;

use function strip_tags;

use const PHP_EOL;

/**
 * Class Output.
 *
 * @author Melech Mizrachi
 */
class Output implements Contract
{
    /**
     * The formatter.
     *
     * @var Formatter
     */
    protected Formatter $formatter;

    /**
     * Output constructor.
     */
    public function __construct(
        protected bool $quiet = false,
    ) {
        $this->formatter = new FormatterClass();
    }

    /**
     * @inheritDoc
     */
    public function getFormatter(): Formatter
    {
        return $this->formatter;
    }

    /**
     * @inheritDoc
     */
    public function setFormatter(Formatter $formatter): void
    {
        $this->formatter = $formatter;
    }

    /**
     * @inheritDoc
     */
    public function write(array $messages, bool|null $newLine = null, OutputStyle|null $outputStyle = null): void
    {
        foreach ($messages as $message) {
            $this->writeMessage($message, $newLine, $outputStyle);
        }
    }

    /**
     * @inheritDoc
     */
    public function writeMessage(string $message, bool|null $newLine = null, OutputStyle|null $outputStyle = null): void
    {
        $newLine ??= false;
        /**
         * @psalm-suppress RedundantCondition Because $outputStyle can be null
         * @psalm-suppress TypeDoesNotContainType Because $outputStyle can be null
         */
        $outputStyleType = $outputStyle->value ?? OutputStyle::NORMAL->value;

        switch ($outputStyleType) {
            case OutputStyle::NORMAL:
                $message = $this->formatter->format($message);

                break;
            case OutputStyle::RAW:
                break;
            case OutputStyle::PLAIN:
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
        if ($this->quiet) {
            return;
        }

        echo $message;

        if ($newLine) {
            echo PHP_EOL;
        }
    }
}
