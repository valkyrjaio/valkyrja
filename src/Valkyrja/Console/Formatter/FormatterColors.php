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

namespace Valkyrja\Console\Formatter;

use Valkyrja\Console\Enum\FormatBackground;
use Valkyrja\Console\Enum\FormatForeground;

/**
 * Trait FormatterColors.
 *
 * @author Melech Mizrachi
 */
trait FormatterColors
{
    /**
     * @inheritDoc
     */
    public function black(bool|null $background = null): void
    {
        $this->setColor($background ? FormatBackground::BLACK : FormatForeground::BLACK, $background);
    }

    /**
     * @inheritDoc
     */
    public function red(bool|null $background = null): void
    {
        $this->setColor($background ? FormatBackground::RED : FormatForeground::RED, $background);
    }

    /**
     * @inheritDoc
     */
    public function green(bool|null $background = null): void
    {
        $this->setColor($background ? FormatBackground::GREEN : FormatForeground::GREEN, $background);
    }

    /**
     * @inheritDoc
     */
    public function yellow(bool|null $background = null): void
    {
        $this->setColor($background ? FormatBackground::YELLOW : FormatForeground::YELLOW, $background);
    }

    /**
     * @inheritDoc
     */
    public function blue(bool|null $background = null): void
    {
        $this->setColor($background ? FormatBackground::BLUE : FormatForeground::BLUE, $background);
    }

    /**
     * @inheritDoc
     */
    public function magenta(bool|null $background = null): void
    {
        $this->setColor($background ? FormatBackground::MAGENTA : FormatForeground::MAGENTA, $background);
    }

    /**
     * @inheritDoc
     */
    public function cyan(bool|null $background = null): void
    {
        $this->setColor($background ? FormatBackground::CYAN : FormatForeground::CYAN, $background);
    }

    /**
     * @inheritDoc
     */
    public function white(bool|null $background = null): void
    {
        $this->setColor($background ? FormatBackground::WHITE : FormatForeground::WHITE, $background);
    }

    /**
     * Set a color.
     *
     * @param FormatBackground $color      The color
     * @param bool             $background [optional] Whether this is to set the background
     *
     * @return void
     */
    abstract protected function setColor(FormatBackground $color, bool|null $background = null): void;
}
