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

use Valkyrja\Console\Enums\FormatBackground;
use Valkyrja\Console\Enums\FormatForeground;

/**
 * Trait FormatterColors.
 *
 * @author Melech Mizrachi
 */
trait FormatterColors
{
    /**
     * Set foreground or background to black.
     *
     * @param bool $background [optional] Whether this is to set the background
     *
     * @return void
     */
    public function black(bool $background = null): void
    {
        $this->setColor($background ? FormatBackground::BLACK : FormatForeground::BLACK, $background);
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
        $this->setColor($background ? FormatBackground::RED : FormatForeground::RED, $background);
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
        $this->setColor($background ? FormatBackground::GREEN : FormatForeground::GREEN, $background);
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
        $this->setColor($background ? FormatBackground::YELLOW : FormatForeground::YELLOW, $background);
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
        $this->setColor($background ? FormatBackground::BLUE : FormatForeground::BLUE, $background);
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
        $this->setColor($background ? FormatBackground::MAGENTA : FormatForeground::MAGENTA, $background);
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
        $this->setColor($background ? FormatBackground::CYAN : FormatForeground::CYAN, $background);
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
        $this->setColor($background ? FormatBackground::WHITE : FormatForeground::WHITE, $background);
    }

    /**
     * Set a color.
     *
     * @param int  $color      The color
     * @param bool $background [optional] Whether this is to set the background
     *
     * @return void
     */
    abstract protected function setColor(int $color, bool $background = null): void;
}
