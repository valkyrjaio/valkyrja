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

/**
 * Interface DispatchCollectableEventContract.
 */
interface DispatchCollectableEventContract
{
    /**
     * Add a dispatch.
     */
    public function addDispatch(mixed $dispatch): void;

    /**
     * Get the dispatches.
     *
     * @return array<int, mixed>
     */
    public function getDispatches(): array;
}
