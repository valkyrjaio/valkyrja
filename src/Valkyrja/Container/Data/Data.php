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

use Valkyrja\Container\Contract\ServiceContract;
use Valkyrja\Container\Provider\Provider;

/**
 * Class Data.
 *
 * @author Melech Mizrachi
 */
readonly class Data
{
    /**
     * @param array<class-string, class-string>                                   $aliases
     * @param array<class-string, class-string>                                   $deferred
     * @param array<class-string, callable>                                       $deferredCallback
     * @param array<class-string<ServiceContract>, class-string<ServiceContract>> $services
     * @param array<class-string, class-string>                                   $singletons
     * @param class-string<Provider>[]                                            $providers
     */
    public function __construct(
        public array $aliases = [],
        public array $deferred = [],
        public array $deferredCallback = [],
        public array $services = [],
        public array $singletons = [],
        public array $providers = [],
    ) {
    }
}
