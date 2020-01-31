<?php

declare(strict_types = 1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Console\Output;

use InvalidArgumentException;
use Valkyrja\Application\Application;
use Valkyrja\Config\Enums\ConfigKey;
use Valkyrja\Console\Enums\OutputStyle;
use Valkyrja\Support\Providers\Provides;

/**
 * Class NativeOutput.
 *
 * @author Melech Mizrachi
 */
class NativeOutput implements Output
{
    use Provides;

    /**
     * Whether to use quiet console.
     *
     * @var bool
     */
    private static bool $quiet = false;

    /**
     * The formatter.
     *
     * @var OutputFormatter
     */
    protected OutputFormatter $formatter;

    /**
     * Output constructor.
     *
     * @param OutputFormatter $formatter The output formatter
     */
    public function __construct(OutputFormatter $formatter)
    {
        $this->formatter = $formatter;

        self::$quiet = config(ConfigKey::CONSOLE_QUIET) || input()->hasOption('--quiet');
    }

    /**
     * Get the formatter.
     *
     * @return OutputFormatter
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
     * @param bool        $newLine     [optional] Whether to use new lines between each message
     * @param OutputStyle $outputStyle [optional] The output style to use
     *
     * @throws InvalidArgumentException
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
     * @param bool        $newLine     [optional] Whether to use new lines between each message
     * @param OutputStyle $outputStyle [optional] The output style to use
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public function writeMessage(string $message, bool $newLine = null, OutputStyle $outputStyle = null): void
    {
        $newLine         = $newLine ?? false;
        $outputStyleType = $outputStyle ? $outputStyle->getValue() : OutputStyle::NORMAL;

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
        if (self::$quiet) {
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
     * @param \Valkyrja\Application\Application $app The application
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
