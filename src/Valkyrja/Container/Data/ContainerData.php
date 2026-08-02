<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Container\Data;

use Valkyrja\Container\Manager\Contract\ContainerContract;

readonly class ContainerData
{
    /**
     * @param array<class-string, class-string>                                                $aliases
     * @param array<class-string, callable(ContainerContract):void>                            $callbacks
     * @param array<class-string, callable(ContainerContract, array<array-key, mixed>):object> $services
     * @param array<class-string, class-string>                                                $singletons
     */
    public function __construct(
        public array $aliases = [],
        public array $callbacks = [],
        public array $services = [],
        public array $singletons = [],
    ) {
    }
}
