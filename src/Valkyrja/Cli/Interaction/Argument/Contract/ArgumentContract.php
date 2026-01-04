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

namespace Valkyrja\Cli\Interaction\Argument\Contract;

interface ArgumentContract
{
    /**
     * Get the value.
     *
     * @return non-empty-string
     */
    public function getValue(): string;

    /**
     * Create a new argument with the specified value.
     *
     * @param non-empty-string $value The value
     */
    public function withValue(string $value): static;
}
