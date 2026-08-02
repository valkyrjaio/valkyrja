<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
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
     */
    public function withFormats(FormatContract ...$formats): static;

    /**
     * Format text.
     */
    public function formatText(string $text): string;
}
