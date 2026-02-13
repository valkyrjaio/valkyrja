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

namespace Valkyrja\Event\Contract;

interface ArgumentsCapableEventContract
{
    /**
     * Set the arguments.
     *
     * @param array<array-key, mixed> $arguments The arguments
     */
    public function setArguments(array $arguments): static;
}
