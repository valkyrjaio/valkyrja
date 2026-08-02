<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Cli\Interaction\Message\Contract;

use Valkyrja\Cli\Interaction\Formatter\Contract\FormatterContract;

interface MessageContract
{
    /**
     * Get the text.
     */
    public function getText(): string;

    /**
     * Get the formatted text.
     */
    public function getFormattedText(): string;

    /**
     * Create a new Message with the specified text.
     */
    public function withText(string $text): static;

    /**
     * Determine if the message has a formatter.
     */
    public function hasFormatter(): bool;

    /**
     * Get the formatter.
     */
    public function getFormatter(): FormatterContract;

    /**
     * Create a new Message with the specified Formatter.
     *
     * @param FormatterContract $formatter The formatter
     */
    public function withFormatter(FormatterContract $formatter): static;

    /**
     * Create a new Message without a Formatter.
     */
    public function withoutFormatter(): static;
}
