<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Console;

use Valkyrja\Console\Enums\FormatBackground;
use Valkyrja\Console\Enums\FormatForeground;
use Valkyrja\Contracts\Console\OutputFormatter as OutputFormatterContract;

/**
 * Class OutputFormatter
 *
 * @package Valkyrja\Console
 *
 * @author  Melech Mizrachi
 */
class OutputFormatter implements OutputFormatterContract
{
    /**
     * The foreground color.
     *
     * @var \Valkyrja\Console\Enums\FormatForeground
     */
    protected $foreground;

    /**
     * The background color.
     *
     * @var \Valkyrja\Console\Enums\FormatBackground
     */
    protected $background;

    /**
     * The options.
     *
     * @var \Valkyrja\Console\Enums\FormatOption[]
     */
    protected $options = [];

    /**
     * Set the foreground.
     *
     * @param \Valkyrja\Console\Enums\FormatForeground $foreground The foreground color
     *
     * @return void
     */
    public function setForeground(FormatForeground $foreground = null): void
    {
        $this->foreground = $foreground;
    }

    /**
     * Set the background.
     *
     * @param \Valkyrja\Console\Enums\FormatBackground $background The background
     *
     * @return void
     */
    public function setBackground(FormatBackground $background = null): void
    {
        $this->background = $background;
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
        $set = [];
        $unset = [];

        if (null !== $this->foreground) {
            $set[] = $this->foreground->getValue();
            $unset[] = FormatForeground::DEFAULT;
        }

        if (null !== $this->background) {
            $set[] = $this->background->getValue();
            $unset[] = FormatBackground::DEFAULT;
        }

        if (count($this->options)) {
            foreach ($this->options as $option) {
                $set[] = $option->getValue();
                $unset[] = 0;
            }
        }

        if (0 === count($set)) {
            return $message;
        }

        return sprintf("\033[%sm%s\033[%sm", implode(';', $set), $message, implode(';', $unset));
    }
}
