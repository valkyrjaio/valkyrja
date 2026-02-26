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

namespace Valkyrja\Cli\Interaction\Message;

use Override;
use Valkyrja\Cli\Interaction\Formatter\Contract\FormatterContract;
use Valkyrja\Cli\Interaction\Message\Contract\MessageContract;
use Valkyrja\Cli\Interaction\Throwable\Exception\NoFormatterException;

class Message implements MessageContract
{
    /**
     * @param non-empty-string $text The text
     */
    public function __construct(
        protected string $text,
        protected FormatterContract|null $formatter = null
    ) {
    }

    /**
     * @inheritDoc
     *
     * @return non-empty-string
     */
    #[Override]
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @inheritDoc
     *
     * @return non-empty-string
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
            ?? throw new NoFormatterException('No formatter has been set');
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
