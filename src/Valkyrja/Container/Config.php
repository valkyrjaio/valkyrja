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

namespace Valkyrja\Container;

use Valkyrja\Container\Config\Cache;
use Valkyrja\Container\Constant\ConfigName;
use Valkyrja\Container\Constant\EnvName;
use Valkyrja\Container\Contract\Service;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Support\Config as ParentConfig;

/**
 * Class Config.
 *
 * @author Melech Mizrachi
 */
class Config extends ParentConfig
{
    /**
     * @inheritDoc
     *
     * @var array<string, string>
     */
    protected static array $envNames = [
        ConfigName::ALIASES          => EnvName::ALIASES,
        ConfigName::SERVICES         => EnvName::SERVICES,
        ConfigName::CONTEXT_ALIASES  => EnvName::CONTEXT_ALIASES,
        ConfigName::CONTEXT_SERVICES => EnvName::CONTEXT_SERVICES,
        ConfigName::PROVIDERS        => EnvName::PROVIDERS,
        ConfigName::USE_ATTRIBUTES   => EnvName::USE_ATTRIBUTES,
    ];

    /**
     * @param class-string[]           $aliases
     * @param class-string<Service>[]  $services
     * @param class-string[]           $contextAliases
     * @param class-string<Service>[]  $contextServices
     * @param class-string<Provider>[] $providers
     */
    public function __construct(
        public array $aliases = [],
        public array $services = [],
        public array $contextAliases = [],
        public array $contextServices = [],
        public array $providers = [],
        public bool $useAttributes = true,
        public Cache|null $cache = null,
    ) {
    }
}
