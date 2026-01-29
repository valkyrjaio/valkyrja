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

use Valkyrja\Cli\Interaction\Format\Contract\FormatContract;

interface FormatterContract
{
    /**
     * Get the formats.
     *
     * @return FormatContract[]
     */
    public function getFormats(): array;

    /**
     * Create a new instance with the specified formats.
     *
     * @param FormatContract ...$formats The formats
     *
     * @return static
     */
    public function withFormats(FormatContract ...$formats): static;

    /**
     * Format text.
     *
     * @param non-empty-string $text The text to format
     *
     * @return non-empty-string
     */
    public function formatText(string $text): string;
}
