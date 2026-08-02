<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Cli\Interaction\Message;

use Override;
use Valkyrja\Cli\Interaction\Formatter\Contract\FormatterContract;
use Valkyrja\Cli\Interaction\Message\Contract\MessageContract;
use Valkyrja\Cli\Interaction\Throwable\Exception\CliInteractionNoFormatterException;

class Message implements MessageContract
{
    public function __construct(
        protected string $text,
        protected FormatterContract|null $formatter = null
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getFormattedText(): string
    {
        $text      = $this->getText();
        $formatter = $this->formatter;

        if ($formatter === null) {
            return $text;
        }

        return $formatter->formatText($text);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withText(string $text): static
    {
        $new = clone $this;

        $new->text = $text;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function hasFormatter(): bool
    {
        return $this->formatter !== null;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getFormatter(): FormatterContract
    {
        return $this->formatter
            ?? throw new CliInteractionNoFormatterException('No formatter has been set');
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withFormatter(FormatterContract $formatter): static
    {
        $new = clone $this;

        $new->formatter = $formatter;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withoutFormatter(): static
    {
        $new = clone $this;

        $new->formatter = null;

        return $new;
    }
}
