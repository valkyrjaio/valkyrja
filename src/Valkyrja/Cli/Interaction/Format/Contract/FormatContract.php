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

namespace Valkyrja\Cli\Interaction\Format\Contract;

interface FormatContract
{
    /**
     * Get the set code.
     *
     * @return non-empty-string
     */
    public function getSetCode(): string;

    /**
     * Create a new format with the specified set code.
     *
     * @param non-empty-string $setCode The set code
     */
    public function withSetCode(string $setCode): static;

    /**
     * Get the unset code.
     *
     * @return non-empty-string
     */
    public function getUnsetCode(): string;

    /**
     * Create a new format with the specified unset code.
     *
     * @param non-empty-string $unsetCode The unset code
     */
    public function withUnsetCode(string $unsetCode): static;
}
