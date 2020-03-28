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

namespace Valkyrja\Console\Formatters;

use Valkyrja\Application\Application;
use Valkyrja\Console\Enums\FormatBackground;
use Valkyrja\Console\Enums\FormatForeground;
use Valkyrja\Console\Enums\FormatOption;
use Valkyrja\Console\Formatter as OutputFormatterContract;
use Valkyrja\Support\Providers\Provides;

use function count;
use function implode;
use function sprintf;

/**
 * Class OutputFormatter.
 *
 * @author Melech Mizrachi
 */
class Formatter implements OutputFormatterContract
{
    use FormatterColors;
    use FormatterOptions;
    use Provides;

    /**
     * The foreground color.
     *
     * @var int|null
     */
    protected ?int $foreground = null;

    /**
     * The background color.
     *
     * @var int|null
     */
    protected ?int $background = null;

    /**
     * The options.
     *
     * @var int[]
     */
    protected array $options = [];

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            OutputFormatterContract::class,
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
        $app->container()->setSingleton(
            OutputFormatterContract::class,
            new static()
        );
    }

    /**
     * Set the foreground.
     *
     * @param FormatForeground $foreground The foreground color
     *
     * @return void
     */
    public function setForeground(FormatForeground $foreground = null): void
    {
        $this->foreground = $foreground ? (int) $foreground->getValue() : null;
    }

    /**
     * Set the background.
     *
     * @param FormatBackground $background The background
     *
     * @return void
     */
    public function setBackground(FormatBackground $background = null): void
    {
        $this->background = $background ? (int) $background->getValue() : null;
    }

    /**
     * Set foreground or background to default.
     *
     * @param bool $background [optional] Whether this is to set the background
     *
     * @return void
     */
    public function resetColor(bool $background = null): void
    {
        $this->setColor($background ? FormatBackground::DEFAULT : FormatForeground::DEFAULT, $background);
    }

    /**
     * Set an option.
     *
     * @param FormatOption $option The option
     *
     * @return void
     */
    public function setOption(FormatOption $option): void
    {
        $this->options[$option->getValue()] = $option->getValue();
    }

    /**
     * Determine whether an option has been set.
     *
     * @param FormatOption $option The option
     *
     * @return bool
     */
    public function hasOption(FormatOption $option): bool
    {
        return isset($this->options[$option->getValue()]);
    }

    /**
     * Remove an option.
     *
     * @param FormatOption $option The option
     *
     * @return void
     */
    public function removeOption(FormatOption $option): void
    {
        if ($this->hasOption($option)) {
            unset($this->options[$option->getValue()]);
        }
    }

    /**
     * Set options.
     *
     * @param FormatOption ...$options The options
     *
     * @return void
     */
    public function setOptions(FormatOption ...$options): void
    {
        foreach ($options as $option) {
            $this->setOption($option);
        }
    }

    /**
     * Reset the options.
     *
     * @return void
     */
    public function resetOptions(): void
    {
        $this->options = [];
    }

    /**
     * Format a message.
     *
     * @param string $message The message
     *
     * @return string
     */
    public function format(string $message): string
    {
        $set   = [];
        $unset = [];

        // Check if a foreground was specified
        if (null !== $this->foreground) {
            $set[]   = $this->foreground;
            $unset[] = FormatForeground::DEFAULT;
        }

        // Check if a background was specified
        if (null !== $this->background) {
            $set[]   = $this->background;
            $unset[] = FormatBackground::DEFAULT;
        }

        // Check if options were specified
        if (count($this->options)) {
            // Iterate through all the options
            foreach ($this->options as $option) {
                $set[]   = $option;
                $unset[] = FormatOption::DEFAULT[$option];
            }
        }

        // No need to format if there's nothing to set
        if (0 === count($set)) {
            return $message;
        }

        return sprintf("\033[%sm%s\033[%sm", implode(';', $set), $message, implode(';', $unset));
    }

    /**
     * Set a color.
     *
     * @param int  $color      The color
     * @param bool $background [optional] Whether this is to set the background
     *
     * @return void
     */
    protected function setColor(int $color, bool $background = null): void
    {
        if (null !== $background) {
            $this->background = $color;

            return;
        }

        $this->foreground = $color;
    }

    /**
     * Set an option by its number value.
     *
     * @param int $option The option
     *
     * @return void
     */
    protected function setOptionNum(int $option): void
    {
        $this->options[$option] = $option;
    }
}
