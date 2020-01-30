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

use Valkyrja\Console\Enums\FormatBackground;
use Valkyrja\Console\Enums\FormatForeground;
use Valkyrja\Console\Enums\FormatOption;

/**
 * Interface OutputFormatter.
 *
 * @author Melech Mizrachi
 */
interface OutputFormatter
{
    /**
     * Set the foreground.
     *
     * @param FormatForeground $foreground The foreground color
     *
     * @return void
     */
    public function setForeground(FormatForeground $foreground = null): void;

    /**
     * Set the background.
     *
     * @param FormatBackground $background The background
     *
     * @return void
     */
    public function setBackground(FormatBackground $background = null): void;

    /**
     * Set foreground or background to black.
     *
     * @param bool $background [optional] Whether this is to set the background
     *
     * @return void
     */
    public function black(bool $background = null): void;

    /**
     * Set foreground or background to red.
     *
     * @param bool $background [optional] Whether this is to set the background
     *
     * @return void
     */
    public function red(bool $background = null): void;

    /**
     * Set foreground or background to green.
     *
     * @param bool $background [optional] Whether this is to set the background
     *
     * @return void
     */
    public function green(bool $background = null): void;

    /**
     * Set foreground or background to yellow.
     *
     * @param bool $background [optional] Whether this is to set the background
     *
     * @return void
     */
    public function yellow(bool $background = null): void;

    /**
     * Set foreground or background to blue.
     *
     * @param bool $background [optional] Whether this is to set the background
     *
     * @return void
     */
    public function blue(bool $background = null): void;

    /**
     * Set foreground or background to magenta.
     *
     * @param bool $background [optional] Whether this is to set the background
     *
     * @return void
     */
    public function magenta(bool $background = null): void;

    /**
     * Set foreground or background to cyan.
     *
     * @param bool $background [optional] Whether this is to set the background
     *
     * @return void
     */
    public function cyan(bool $background = null): void;

    /**
     * Set foreground or background to white.
     *
     * @param bool $background [optional] Whether this is to set the background
     *
     * @return void
     */
    public function white(bool $background = null): void;

    /**
     * Set foreground or background to default.
     *
     * @param bool $background [optional] Whether this is to set the background
     *
     * @return void
     */
    public function resetColor(bool $background = null): void;

    /**
     * Set an option.
     *
     * @param FormatOption $option The option
     *
     * @return void
     */
    public function setOption(FormatOption $option): void;

    /**
     * Determine whether an option has been set.
     *
     * @param FormatOption $option The option
     *
     * @return bool
     */
    public function hasOption(FormatOption $option): bool;

    /**
     * Remove an option.
     *
     * @param FormatOption $option The option
     *
     * @return void
     */
    public function removeOption(FormatOption $option): void;

    /**
     * Set options.
     *
     * @param FormatOption ...$options The options
     *
     * @return void
     */
    public function setOptions(FormatOption ...$options): void;

    /**
     * Set the bold option.
     *
     * @return void
     */
    public function bold(): void;

    /**
     * Set the underscore option.
     *
     * @return void
     */
    public function underscore(): void;

    /**
     * Set the blink option.
     *
     * @return void
     */
    public function blink(): void;

    /**
     * Set the reverse option.
     *
     * @return void
     */
    public function reverse(): void;

    /**
     * Set the conceal option.
     *
     * @return void
     */
    public function conceal(): void;

    /**
     * Reset the options.
     *
     * @return void
     */
    public function resetOptions(): void;

    /**
     * Format a message.
     *
     * @param string $message The message
     *
     * @return string
     */
    public function format(string $message): string;
}
