<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Config\Configs;

use Valkyrja\Config\Enums\EnvKey;
use Valkyrja\Config\Models\Annotatable;
use Valkyrja\Config\Models\Cacheable;
use Valkyrja\Config\Models\Config as Model;
use Valkyrja\Container\Enums\Config;

/**
 * Class Container.
 *
 * @author Melech Mizrachi
 */
class Container extends Model
{
    use Annotatable;
    use Cacheable;

    public array $aliases         = [];
    public array $services        = [];
    public array $contextServices = [];
    public array $providers       = [];
    public array $devProviders    = [];

    /**
     * Container constructor.
     */
    public function __construct()
    {
        $this->aliases         = (array) env(EnvKey::CONTAINER_ALIASES, $this->aliases);
        $this->services        = (array) env(EnvKey::CONTAINER_SERVICES, $this->services);
        $this->contextServices = (array) env(EnvKey::CONTAINER_CONTEXT_SERVICES, $this->contextServices);
        $this->providers       = (array) env(
            EnvKey::CONTAINER_PROVIDERS,
            array_merge(Config::PROVIDERS, $this->providers)
        );
        $this->devProviders    = (array) env(
            EnvKey::CONTAINER_DEV_PROVIDERS,
            array_merge(Config::DEV_PROVIDERS, $this->devProviders)
        );

        $this->envUseAnnotationsKey            = EnvKey::CONTAINER_USE_ANNOTATIONS;
        $this->envUseAnnotationsExclusivelyKey = EnvKey::CONTAINER_USE_ANNOTATIONS_EXCLUSIVELY;
        $this->setAnnotationsConfig();

        $this->filePath            = servicesPath('default.php');
        $this->cacheFilePath       = cachePath('container.php');
        $this->envFilePathKey      = EnvKey::CONTAINER_FILE_PATH;
        $this->envCacheFilePathKey = EnvKey::CONTAINER_CACHE_FILE_PATH;
        $this->envUseCacheKey      = EnvKey::CONTAINER_USE_CACHE_FILE;
        $this->setCacheableConfig();
    }
}
