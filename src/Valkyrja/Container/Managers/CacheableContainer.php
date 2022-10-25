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

namespace Valkyrja\Container\Managers;

use JsonException;
use Valkyrja\Config\Config;
use Valkyrja\Container\Annotator;
use Valkyrja\Container\Config\Cache;
use Valkyrja\Container\Config\Config as ContainerConfig;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Support\Cacheable\Cacheable;

/**
 * Class CacheableContainer.
 *
 * @author Melech Mizrachi
 */
class CacheableContainer extends Container
{
    use Cacheable;

    /**
     * Get a cacheable representation of the service container.
     *
     * @throws JsonException
     *
     * @return ContainerConfig
     */
    public function getCacheable(): Config
    {
        $this->setup(true, false);

        // Set app config
        $config        = new ContainerConfig($this->config);
        $config->cache = $this->getCacheModel();
        $providers     = $config->providers;

        // Iterate through all the providers
        foreach ($providers as $key => $provider) {
            /** @var Provider $provider */
            if ($provider::deferred()) {
                unset($providers[$key]);
            }
        }

        $config->providers = $providers;

        return $config;
    }

    /**
     * Get the config.
     *
     * @return ContainerConfig|array
     */
    protected function getConfig(): Config|array
    {
        return $this->config;
    }

    /**
     * Before setup.
     *
     * @param ContainerConfig|array $config
     *
     * @return void
     */
    protected function beforeSetup(Config|array $config): void
    {
    }

    /**
     * Setup the container from cache.
     *
     * @param array $config
     *
     * @return void
     */
    protected function setupFromCache(array $config): void
    {
        $cache = $config['cache'] ?? require $config['cacheFilePath'];

        self::$aliases        = $cache['aliases'];
        self::$provided       = $cache['provided'];
        self::$providedMethod = $cache['providedMethod'];
        self::$services       = $cache['services'];
        self::$singletons     = $cache['singletons'];

        // Setup service providers
        $this->setupServiceProviders($config);
    }

    /**
     * Set not cached.
     *
     * @param ContainerConfig|array $config
     *
     * @return void
     */
    protected function setupNotCached(Config|array $config): void
    {
        self::$aliases        = [];
        self::$registered     = [];
        self::$provided       = [];
        self::$providedMethod = [];
        self::$services       = [];
        self::$singletons     = [];

        // Setup service providers
        $this->setupServiceProviders($config);
    }

    /**
     * Setup annotations.
     *
     * @param ContainerConfig|array $config
     *
     * @return void
     */
    protected function setupAnnotations(Config|array $config): void
    {
        /** @var Annotator $containerAnnotations */
        $containerAnnotations = $this->getSingleton(Annotator::class);

        // Get all the annotated services from the list of controllers and iterate through the services
        foreach ($containerAnnotations->getServices(...$config['services']) as $service) {
            // Set the service
            $this->bind($service->getId(), $service->getClass());
        }

        // Get all the annotated services from the list of controllers and iterate through the services
        foreach ($containerAnnotations->getContextServices(...$config['contextServices']) as $context) {
            // Set the service
            $this->withContext($context->getClass(), $context->getMethod())->bind(
                $context->getId(),
                $context->getService()
            );
        }

        // Get all the annotated services from the list of classes and iterate through the services
        foreach ($containerAnnotations->getAliasServices(...$config['aliases']) as $alias) {
            // Set the service
            $this->setAlias($alias->getName(), $alias->getId());
        }
    }

    /**
     * Set attributes.
     *
     * @param ContainerConfig|array $config
     *
     * @return void
     */
    protected function setupAttributes(Config|array $config): void
    {
    }

    /**
     * Setup service providers.
     *
     * @param ContainerConfig|array $config
     *
     * @return void
     */
    protected function setupServiceProviders(Config|array $config): void
    {
        // Iterate through all the providers
        foreach ($config['providers'] as $provider) {
            $this->register($provider);
        }

        // If this is not a dev environment
        if (! $this->debug) {
            return;
        }

        // Iterate through all the providers
        foreach ($config['devProviders'] as $devProvider) {
            $this->register($devProvider);
        }
    }

    /**
     * After setup.
     *
     * @param ContainerConfig|array $config
     *
     * @return void
     */
    protected function afterSetup(Config|array $config): void
    {
    }

    /**
     * Get the cache model.
     *
     * @return Cache
     */
    protected function getCacheModel(): Cache
    {
        $config                 = new Cache();
        $config->aliases        = self::$aliases;
        $config->provided       = self::$provided;
        $config->providedMethod = self::$providedMethod;
        $config->services       = self::$services;
        $config->singletons     = self::$singletons;

        return $config;
    }
}
