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
use Valkyrja\Cli\Interaction\Message\Contract\MessageContract as Contract;

/**
 * Class Message.
 */
class Message implements Contract
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
    public function getFormatter(): FormatterContract|null
    {
        return $this->formatter;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withFormatter(FormatterContract|null $formatter): static
    {
        $new = clone $this;

        $new->formatter = $formatter;

        return $new;
    }
}
