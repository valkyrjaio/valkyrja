<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Dispatch\Dispatcher\Contract;

use Valkyrja\Dispatch\Data\Contract\DispatchContract;

interface DispatcherContract
{
    /**
     * Dispatch a callable.
     *
     * @param DispatchContract               $dispatch  The dispatch
     * @param array<non-empty-string, mixed> $arguments The arguments
     */
    public function dispatch(DispatchContract $dispatch, array $arguments = []): mixed;
}
