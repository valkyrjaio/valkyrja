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
