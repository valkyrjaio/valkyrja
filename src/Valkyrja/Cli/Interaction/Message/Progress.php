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
use Valkyrja\Cli\Interaction\Message\Contract\ProgressContract as Contract;

class Progress extends Message implements Contract
{
    /**
     * @param non-empty-string $text The text
     */
    public function __construct(
        string $text,
        protected bool $isComplete = false,
        protected int $percentage = 0,
        FormatterContract|null $formatter = null
    ) {
        parent::__construct($text, $formatter);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function isComplete(): bool
    {
        return $this->isComplete;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withIsComplete(bool $isComplete): static
    {
        $new = clone $this;

        $new->isComplete = $isComplete;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getPercentage(): int
    {
        return $this->percentage;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withPercentage(int $percentage): static
    {
        $new = clone $this;

        $new->percentage = $percentage;

        return $new;
    }
}
