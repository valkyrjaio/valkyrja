<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Event\Contract;

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
