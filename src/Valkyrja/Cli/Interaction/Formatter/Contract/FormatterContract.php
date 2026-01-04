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

interface FormatterContract
{
    /**
     * Get the style.
     */
    public function getStyle(): Style|null;

    /**
     * Create a new Formatter with the specified style.
     *
     * @param Style|null $style The style
     */
    public function withStyle(Style|null $style): static;

    /**
     * Get the text color.
     */
    public function getTextColor(): TextColor|null;

    /**
     * Create a new Formatter with the specified text color.
     *
     * @param TextColor|null $textColor The text color
     */
    public function withTextColor(TextColor|null $textColor): static;

    /**
     * Get the background color.
     */
    public function getBackgroundColor(): BackgroundColor|null;

    /**
     * Create a new Formatter with the specified background color.
     *
     * @param BackgroundColor|null $backgroundColor The background color
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
