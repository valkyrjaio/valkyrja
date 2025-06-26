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

use Valkyrja\Cli\Interaction\Formatter\Contract\Formatter;
use Valkyrja\Cli\Interaction\Message\Contract\Progress as Contract;

/**
 * Class Progress.
 *
 * @author Melech Mizrachi
 */
class Progress extends Message implements Contract
{
    /**
     * @param non-empty-string $text The text
     */
    public function __construct(
        string $text,
        protected bool $isComplete = false,
        protected int $percentage = 0,
        Formatter|null $formatter = null
    ) {
        parent::__construct($text, $formatter);
    }

    /**
     * @inheritDoc
     */
    public function isComplete(): bool
    {
        return $this->isComplete;
    }

    /**
     * @inheritDoc
     */
    public function withIsComplete(bool $isComplete): static
    {
        $new = clone $this;

        $new->isComplete = $isComplete;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function getPercentage(): int
    {
        return $this->percentage;
    }

    /**
     * @inheritDoc
     */
    public function withPercentage(int $percentage): static
    {
        $new = clone $this;

        $new->percentage = $percentage;

        return $new;
    }
}
