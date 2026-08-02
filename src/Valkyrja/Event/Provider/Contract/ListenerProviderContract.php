<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Event\Provider\Contract;

use Valkyrja\Event\Data\Contract\ListenerContract;

interface ListenerProviderContract
{
    /**
     * Get a list of attributed listener classes.
     *
     * @return class-string[]
     */
    public function getListenerClasses(): array;

    /**
     * Get a list of listeners.
     *
     * @return ListenerContract[]
     */
    public function getListeners(): array;
}
