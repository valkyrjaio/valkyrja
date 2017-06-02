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

use Valkyrja\Console\Enums\OutputStyle;
use Valkyrja\Container\Enums\CoreComponent;
use Valkyrja\Contracts\Application;
use Valkyrja\Contracts\Console\Output\Output as OutputContract;
use Valkyrja\Contracts\Console\Output\OutputFormatter;
use Valkyrja\Support\Provides;

/**
 * Class Output.
 *
 * @author Melech Mizrachi
 */
class Output implements OutputContract
{
    use Provides;

    /**
     * The formatter.
     *
     * @var \Valkyrja\Contracts\Console\Output\OutputFormatter
     */
    protected $formatter;

    /**
     * Output constructor.
     *
     * @param \Valkyrja\Contracts\Console\Output\OutputFormatter $formatter The output formatter
     */
    public function __construct(OutputFormatter $formatter)
    {
        $this->formatter = $formatter;
    }

    /**
     * Get the formatter.
     *
     * @return \Valkyrja\Contracts\Console\Output\OutputFormatter
     */
    public function formatter(): OutputFormatter
    {
        return $this->formatter;
    }

    /**
     * Set the formatter.
     *
     * @param \Valkyrja\Contracts\Console\Output\OutputFormatter $formatter
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
     * @param string                              $message     The message
     * @param bool                                $newLine     [optional] Whether to use new lines between each message
     * @param \Valkyrja\Console\Enums\OutputStyle $outputStyle [optional] The output style to use
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    public function writeMessage(string $message, bool $newLine = null, OutputStyle $outputStyle = null): void
    {
        $newLine         = $newLine ?? false;
        $outputStyleType = $outputStyle ? $outputStyle->getValue() : OutputStyle::NORMAL;

        switch ($outputStyleType) {
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
            CoreComponent::OUTPUT,
        ];
    }

    /**
     * Publish the provider.
     *
     * @param \Valkyrja\Contracts\Application $app The application
     *
     * @return void
     */
    public static function publish(Application $app): void
    {
        $app->container()->singleton(
            CoreComponent::OUTPUT,
            new static(
                $app->container()->getSingleton(CoreComponent::OUTPUT_FORMATTER)
            )
        );
    }
}
