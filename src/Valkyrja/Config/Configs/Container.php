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

namespace Valkyrja\Config\Configs;

use Valkyrja\Config\Enums\EnvKey;
use Valkyrja\Config\Models\Cacheable as Model;
use Valkyrja\Container\Enums\Config;
use Valkyrja\Support\Providers\Provider;

use function Valkyrja\cachePath;
use function Valkyrja\env;
use function Valkyrja\servicesPath;

/**
 * Class Container
 *
 * @author Melech Mizrachi
 */
class Container extends Model
{
    /**
     * The annotated service aliases.
     *
     * @var string[]
     */
    public array $aliases;

    /**
     * The annotated services.
     *
     * @var string[]
     */
    public array $services;

    /**
     * The annotated context services.
     *
     * @var string[]
     */
    public array $contextServices;

    /**
     * The command providers.
     *
     * @var Provider[]|string[]
     */
    public array $providers;

    /**
     * The dev command providers.
     *
     * @var Provider[]|string[]
     */
    public array $devProviders;

    /**
     * Container constructor.
     *
     * @param bool $setDefaults [optional]
     */
    public function __construct(bool $setDefaults = true)
    {
        if (! $setDefaults) {
            return;
        }

        $this->setAliases();
        $this->setServices();
        $this->setContextServices();
        $this->setProviders();
        $this->setDevProviders();

        $this->setFilePathEnvKey(EnvKey::CONTAINER_FILE_PATH);
        $this->setCacheFilePathEnvKey(EnvKey::CONTAINER_CACHE_FILE_PATH);
        $this->setUseCacheEnvKey(EnvKey::CONTAINER_USE_CACHE_FILE);
        $this->setUseAnnotationsEnvKey(EnvKey::CONTAINER_USE_ANNOTATIONS);
        $this->setUseAnnotationsExclusivelyEnvKey(EnvKey::CONTAINER_USE_ANNOTATIONS_EXCLUSIVELY);

        $this->setFilePath(servicesPath('default.php'));
        $this->setCacheFilePath(cachePath('container.php'));
        $this->setUseCache();
        $this->setAnnotationsConfig();
    }

    /**
     * Set the annotated service aliases.
     *
     * @param array $aliases [optional] The annotated service aliases
     *
     * @return void
     */
    protected function setAliases(array $aliases = []): void
    {
        $this->aliases = (array) env(EnvKey::CONTAINER_ALIASES, $aliases);
    }

    /**
     * Set the annotated services.
     *
     * @param array $aliases [optional] The annotated service aliases
     *
     * @return void
     */
    protected function setServices(array $aliases = []): void
    {
        $this->aliases = (array) env(EnvKey::CONTAINER_SERVICES, $aliases);
    }

    /**
     * Set the annotated context services.
     *
     * @param array $aliases [optional] The annotated service aliases
     *
     * @return void
     */
    protected function setContextServices(array $aliases = []): void
    {
        $this->aliases = (array) env(EnvKey::CONTAINER_CONTEXT_SERVICES, $aliases);
    }

    /**
     * Set the command providers.
     *
     * @param array $providers [optional] The command providers
     *
     * @return void
     */
    protected function setProviders(array $providers = Config::PROVIDERS): void
    {
        $this->providers = (array) env(EnvKey::CONTAINER_PROVIDERS, $providers);
    }

    /**
     * Set the dev command providers.
     *
     * @param array $devProviders [optional] The dev command providers
     *
     * @return void
     */
    protected function setDevProviders(array $devProviders = Config::DEV_PROVIDERS): void
    {
        $this->devProviders = (array) env(EnvKey::CONTAINER_DEV_PROVIDERS, $devProviders);
    }
}
