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

namespace Valkyrja\Cli\Interaction\Formatter\Contract;

use Valkyrja\Cli\Interaction\Enum\BackgroundColor;
use Valkyrja\Cli\Interaction\Enum\Style;
use Valkyrja\Cli\Interaction\Enum\TextColor;

/**
 * Interface FormatterContract.
 */
interface FormatterContract
{
    /**
     * Get the style.
     *
     * @return Style|null
     */
    public function getStyle(): Style|null;

    /**
     * Create a new Formatter with the specified style.
     *
     * @param Style|null $style The style
     *
     * @return static
     */
    public function withStyle(Style|null $style): static;

    /**
     * Get the text color.
     *
     * @return TextColor|null
     */
    public function getTextColor(): TextColor|null;

    /**
     * Create a new Formatter with the specified text color.
     *
     * @param TextColor|null $textColor The text color
     *
     * @return static
     */
    public function withTextColor(TextColor|null $textColor): static;

    /**
     * Get the background color.
     *
     * @return BackgroundColor|null
     */
    public function getBackgroundColor(): BackgroundColor|null;

    /**
     * Create a new Formatter with the specified background color.
     *
     * @param BackgroundColor|null $backgroundColor The background color
     *
     * @return static
     */
    public function withBackgroundColor(BackgroundColor|null $backgroundColor): static;

    /**
     * Format text.
     *
     * @param non-empty-string $text The text to format
     *
     * @return non-empty-string
     */
    public function formatText(string $text): string;
}
