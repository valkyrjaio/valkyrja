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

namespace Valkyrja\Cli\Interaction\Message\Contract;

use Valkyrja\Cli\Interaction\Formatter\Contract\FormatterContract;

/**
 * Interface MessageContract.
 */
interface MessageContract
{
    /**
     * Get the text.
     *
     * @return non-empty-string
     */
    public function getText(): string;

    /**
     * Get the formatted text.
     *
     * @return non-empty-string
     */
    public function getFormattedText(): string;

    /**
     * Create a new Message with the specified text.
     *
     * @param non-empty-string $text The text
     *
     * @return static
     */
    public function withText(string $text): static;

    /**
     * Get the formatter.
     *
     * @return FormatterContract|null
     */
    public function getFormatter(): FormatterContract|null;

    /**
     * Create a new Message with the specified Formatter.
     *
     * @param FormatterContract|null $formatter The formatter
     *
     * @return static
     */
    public function withFormatter(FormatterContract|null $formatter): static;
}
