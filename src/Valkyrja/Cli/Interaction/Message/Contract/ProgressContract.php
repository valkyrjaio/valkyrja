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

interface ProgressContract extends MessageContract
{
    /**
     * Determine whether the progress is complete.
     */
    public function isComplete(): bool;

    /**
     * Create a new Progress message with the specified completion.
     *
     * @param bool $isComplete Whether progress is complete
     */
    public function withIsComplete(bool $isComplete): static;

    /**
     * Get the percentage.
     */
    public function getPercentage(): int;

    /**
     * Create a new Progress message with the specified percentage.
     *
     * @param int $percentage The percentage
     */
    public function withPercentage(int $percentage): static;
}
