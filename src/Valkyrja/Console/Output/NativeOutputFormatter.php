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
use Valkyrja\Console\Enums\FormatBackground;
use Valkyrja\Console\Enums\FormatForeground;
use Valkyrja\Console\Enums\FormatOption;
use Valkyrja\Support\Providers\Provides;

/**
 * Class OutputFormatter.
 *
 * @author Melech Mizrachi
 */
class NativeOutputFormatter implements OutputFormatter
{
    use Provides;

    /**
     * The foreground color.
     *
     * @var int
     */
    protected $foreground;

    /**
     * The background color.
     *
     * @var int
     */
    protected $background;

    /**
     * The options.
     *
     * @var int[]
     */
    protected $options = [];

    /**
     * Set the foreground.
     *
     * @param FormatForeground $foreground The foreground color
     *
     * @return void
     */
    public function setForeground(FormatForeground $foreground = null): void
    {
        if (null === $foreground) {
            $this->foreground = null;

            return;
        }

        $this->foreground = $foreground->getValue();
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
        if (null === $background) {
            $this->background = null;

            return;
        }

        $this->background = $background->getValue();
    }

    /**
     * Set foreground or background to black.
     *
     * @param bool $background [optional] Whether this is to set the background
     *
     * @return void
     */
    public function black(bool $background = null): void
    {
        $this->setColor(
            $background ? FormatBackground::BLACK : FormatForeground::BLACK,
            $background
        );
    }

    /**
     * Set foreground or background to red.
     *
     * @param bool $background [optional] Whether this is to set the background
     *
     * @return void
     */
    public function red(bool $background = null): void
    {
        $this->setColor(
            $background ? FormatBackground::RED : FormatForeground::RED,
            $background
        );
    }

    /**
     * Set foreground or background to green.
     *
     * @param bool $background [optional] Whether this is to set the background
     *
     * @return void
     */
    public function green(bool $background = null): void
    {
        $this->setColor(
            $background ? FormatBackground::GREEN : FormatForeground::GREEN,
            $background
        );
    }

    /**
     * Set foreground or background to yellow.
     *
     * @param bool $background [optional] Whether this is to set the background
     *
     * @return void
     */
    public function yellow(bool $background = null): void
    {
        $this->setColor(
            $background ? FormatBackground::YELLOW : FormatForeground::YELLOW,
            $background
        );
    }

    /**
     * Set foreground or background to blue.
     *
     * @param bool $background [optional] Whether this is to set the background
     *
     * @return void
     */
    public function blue(bool $background = null): void
    {
        $this->setColor(
            $background ? FormatBackground::BLUE : FormatForeground::BLUE,
            $background
        );
    }

    /**
     * Set foreground or background to magenta.
     *
     * @param bool $background [optional] Whether this is to set the background
     *
     * @return void
     */
    public function magenta(bool $background = null): void
    {
        $this->setColor(
            $background ? FormatBackground::MAGENTA : FormatForeground::MAGENTA,
            $background
        );
    }

    /**
     * Set foreground or background to cyan.
     *
     * @param bool $background [optional] Whether this is to set the background
     *
     * @return void
     */
    public function cyan(bool $background = null): void
    {
        $this->setColor(
            $background ? FormatBackground::CYAN : FormatForeground::CYAN,
            $background
        );
    }

    /**
     * Set foreground or background to white.
     *
     * @param bool $background [optional] Whether this is to set the background
     *
     * @return void
     */
    public function white(bool $background = null): void
    {
        $this->setColor(
            $background ? FormatBackground::WHITE : FormatForeground::WHITE,
            $background
        );
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
        $this->setColor(
            $background ? FormatBackground::DEFAULT : FormatForeground::DEFAULT,
            $background
        );
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
     * @param FormatOption[] ...$options The options
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
     * Set the bold option.
     *
     * @return void
     */
    public function bold(): void
    {
        $this->setOptionNum(FormatOption::BOLD);
    }

    /**
     * Set the underscore option.
     *
     * @return void
     */
    public function underscore(): void
    {
        $this->setOptionNum(FormatOption::UNDERSCORE);
    }

    /**
     * Set the blink option.
     *
     * @return void
     */
    public function blink(): void
    {
        $this->setOptionNum(FormatOption::BLINK);
    }

    /**
     * Set the reverse option.
     *
     * @return void
     */
    public function reverse(): void
    {
        $this->setOptionNum(FormatOption::INVERSE);
    }

    /**
     * Set the conceal option.
     *
     * @return void
     */
    public function conceal(): void
    {
        $this->setOptionNum(FormatOption::CONCEAL);
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

        return sprintf(
            "\033[%sm%s\033[%sm",
            implode(';', $set),
            $message,
            implode(';', $unset)
        );
    }

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            OutputFormatter::class,
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
            OutputFormatter::class,
            new static()
        );
    }
}
