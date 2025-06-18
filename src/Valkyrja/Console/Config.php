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

namespace Valkyrja\Console;

use Valkyrja\Config\Config as ParentConfig;
use Valkyrja\Console\Commander\Contract\Commander;
use Valkyrja\Console\Config\Cache;
use Valkyrja\Console\Constant\ConfigName;
use Valkyrja\Console\Constant\ConfigValue;
use Valkyrja\Console\Constant\EnvName;

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
        ConfigName::HANDLERS           => EnvName::HANDLERS,
        ConfigName::PROVIDERS          => EnvName::PROVIDERS,
        ConfigName::DEV_PROVIDERS      => EnvName::DEV_PROVIDERS,
        ConfigName::SHOULD_RUN_QUIETLY => EnvName::SHOULD_RUN_QUIETLY,
    ];

    /**
     * @param class-string<Commander>[] $handlers     A list of attributed command handlers
     * @param class-string[]            $providers    A list of providers for command handlers
     * @param class-string[]            $devProviders A list of providers for command handlers for dev only
     */
    public function __construct(
        public array $handlers = [],
        public array $providers = [],
        public array $devProviders = [],
        public bool $shouldRunQuietly = false,
        public Cache|null $cache = null
    ) {
    }

    /**
     * @inheritDoc
     */
    protected function setPropertiesAfterSettingFromEnv(string $env): void
    {
        $this->providers    = array_merge(ConfigValue::PROVIDERS, $this->providers);
        $this->devProviders = array_merge(ConfigValue::DEV_PROVIDERS, $this->devProviders);
    }
}
