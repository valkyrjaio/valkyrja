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

/**
 * Interface ProgressContract.
 *
 * @author Melech Mizrachi
 */
interface ProgressContract extends MessageContract
{
    /**
     * Determine whether the progress is complete.
     *
     * @return bool
     */
    public function isComplete(): bool;

    /**
     * Create a new Progress message with the specified completion.
     *
     * @param bool $isComplete Whether progress is complete
     *
     * @return static
     */
    public function withIsComplete(bool $isComplete): static;

    /**
     * Get the percentage.
     *
     * @return int
     */
    public function getPercentage(): int;

    /**
     * Create a new Progress message with the specified percentage.
     *
     * @param int $percentage The percentage
     *
     * @return static
     */
    public function withPercentage(int $percentage): static;
}
