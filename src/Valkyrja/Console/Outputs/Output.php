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

namespace Valkyrja\Console\Outputs;

use InvalidArgumentException;
use Valkyrja\Config\Constants\ConfigKey;
use Valkyrja\Console\Enums\OutputStyle;
use Valkyrja\Console\Formatter;
use Valkyrja\Console\Formatters\Formatter as FormatterClass;
use Valkyrja\Console\Output as Contract;
use Valkyrja\Container\Container;
use Valkyrja\Container\Support\Provides;

use function strip_tags;
use function Valkyrja\config;
use function Valkyrja\input;

use const PHP_EOL;

/**
 * Class Output.
 *
 * @author Melech Mizrachi
 */
class Output implements Contract
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
     * @var Formatter
     */
    protected Formatter $formatter;

    /**
     * Output constructor.
     */
    public function __construct()
    {
        $this->formatter = new FormatterClass();

        self::$quiet = config(ConfigKey::CONSOLE_QUIET) || input()->hasOption('--quiet');
    }

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            Contract::class,
        ];
    }

    /**
     * Publish the provider.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publish(Container $container): void
    {
        $container->setSingleton(
            Contract::class,
            new static()
        );
    }

    /**
     * Get the formatter.
     *
     * @return Formatter
     */
    public function getFormatter(): Formatter
    {
        return $this->formatter;
    }

    /**
     * Set the formatter.
     *
     * @param Formatter $formatter
     *
     * @return void
     */
    public function setFormatter(Formatter $formatter): void
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
}
