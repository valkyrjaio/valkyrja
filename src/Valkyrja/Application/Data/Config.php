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

namespace Valkyrja\Application\Data;

use Valkyrja\Container\Contract\Service;
use Valkyrja\Container\Provider\Provider;

/**
 * Class AppConfig.
 *
 * @author Melech Mizrachi
 */
class Config
{
    /**
     * @param class-string[]           $aliases
     * @param class-string<Service>[]  $services
     * @param class-string<Provider>[] $providers
     * @param class-string[]           $listeners
     * @param class-string[]           $commands
     * @param class-string[]           $controllers
     */
    public function __construct(
        public array $aliases = [],
        public array $services = [],
        public array $providers = [],
        public array $listeners = [],
        public array $commands = [],
        public array $controllers = [],
    ) {
    }
}
