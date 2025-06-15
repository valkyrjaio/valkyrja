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

use Valkyrja\Config\Config as ParentConfig;
use Valkyrja\Container\Config\Cache;
use Valkyrja\Container\Constant\ConfigName;
use Valkyrja\Container\Constant\ConfigValue;
use Valkyrja\Container\Constant\EnvName;
use Valkyrja\Container\Contract\Service;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Support\Directory;

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
        ConfigName::CONTEXT_SERVICES => EnvName::CONTEXT_SERVICES,
        ConfigName::PROVIDERS        => EnvName::PROVIDERS,
        ConfigName::DEV_PROVIDERS    => EnvName::DEV_PROVIDERS,
        ConfigName::USE_ATTRIBUTES   => EnvName::USE_ATTRIBUTES,
        ConfigName::FILE_PATH        => EnvName::FILE_PATH,
        ConfigName::CACHE_FILE_PATH  => EnvName::CACHE_FILE_PATH,
        ConfigName::USE_CACHE        => EnvName::USE_CACHE,
    ];

    /**
     * @param class-string[]           $aliases
     * @param class-string<Service>[]  $services
     * @param class-string<Service>[]  $contextServices
     * @param class-string<Provider>[] $providers
     * @param class-string<Provider>[] $devProviders
     */
    public function __construct(
        public array $aliases = [],
        public array $services = [],
        public array $contextServices = [],
        public array $providers = [],
        public array $devProviders = [],
        public bool $useAttributes = true,
        public string $filePath = '',
        public string $cacheFilePath = '',
        public bool $useCache = false,
        public Cache|null $cache = null,
    ) {
    }

    /**
     * @inheritDoc
     */
    protected function setPropertiesAfterSettingFromEnv(string $env): void
    {
        $this->providers    = array_merge(ConfigValue::PROVIDERS, $this->providers);
        $this->devProviders = array_merge(ConfigValue::DEV_PROVIDERS, $this->devProviders);

        if ($this->filePath === '') {
            $this->filePath = Directory::servicesPath('default.php');
        }

        if ($this->cacheFilePath === '') {
            $this->cacheFilePath = Directory::cachePath('container.php');
        }
    }
}
