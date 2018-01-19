<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Console\Output;

use Valkyrja\Application;
use Valkyrja\Console\Enums\OutputStyle;
use Valkyrja\Support\Providers\Provides;

/**
 * Class Output.
 *
 * @author Melech Mizrachi
 */
class OutputImpl implements Output
{
    use Provides;

    /**
     * The formatter.
     *
     * @var \Valkyrja\Console\Output\OutputFormatter
     */
    protected $formatter;

    /**
     * Output constructor.
     *
     * @param OutputFormatter $formatter The output formatter
     */
    public function __construct(OutputFormatter $formatter)
    {
        $this->formatter = $formatter;
    }

    /**
     * Get the formatter.
     *
     * @return \Valkyrja\Console\Output\OutputFormatter
     */
    public function formatter(): OutputFormatter
    {
        return $this->formatter;
    }

    /**
     * Set the formatter.
     *
     * @param OutputFormatter $formatter
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
     * @param array       $messages    The messages
     * @param bool        $newLine     [optional] Whether to use new lines
     *                                 between each message
     * @param OutputStyle $outputStyle [optional] The output style to use
     *
     * @throws \InvalidArgumentException
     *
     * @return void
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
     * @param string      $message     The message
     * @param bool        $newLine     [optional] Whether to use new lines
     *                                 between each message
     * @param OutputStyle $outputStyle [optional] The output style to use
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    public function writeMessage(string $message, bool $newLine = null, OutputStyle $outputStyle = null): void
    {
        $newLine         = $newLine ?? false;
        $outputStyleType =
            $outputStyle ? $outputStyle->getValue() : OutputStyle::NORMAL;

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
        if (config()['console']['quiet'] || input()->hasOption('--quiet')) {
            return;
        }

        echo $message;

        if ($newLine) {
            echo PHP_EOL;
        }
    }

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            Output::class,
        ];
    }

    /**
     * Publish the provider.
     *
     * @param Application $app The application
     *
     * @return void
     */
    public static function publish(Application $app): void
    {
        $app->container()->singleton(
            Output::class,
            new static(
                $app->container()->getSingleton(OutputFormatter::class)
            )
        );
    }
}
