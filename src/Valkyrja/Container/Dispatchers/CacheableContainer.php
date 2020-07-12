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

namespace Valkyrja\Container\Dispatchers;

use Valkyrja\Container\Annotation\Annotator;
use Valkyrja\Container\Annotation\Service\Context;
use Valkyrja\Container\Config\Cache;
use Valkyrja\Container\Config\Config as ContainerConfig;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Support\Cacheable\Cacheable;

use function Valkyrja\app;

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
     * @return Cache|object
     */
    public function getCacheable(): object
    {
        // $this->setup(true, false);

        // Set app config
        $config = new ContainerConfig($this->config);
        $config->cache = $this->getCacheModel();
        app()->config()->container = $config;

        return $config->cache;
    }

    /**
     * Get the config.
     *
     * @return ContainerConfig|array
     */
    protected function getConfig()
    {
        return $this->config;
    }

    /**
     * Before setup.
     *
     * @return void
     */
    protected function beforeSetup(): void
    {
    }

    /**
     * Setup the container from cache.
     *
     * @param ContainerConfig|array $config
     *
     * @return void
     */
    protected function setupFromCache(array $config): void
    {
        $cache = $config['cache'] ?? require $config['cacheFilePath'];

        self::$aliases    = $cache['aliases'];
        self::$provided   = $cache['provided'];
        self::$services   = $cache['services'];
        self::$singletons = $cache['singletons'];

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
    protected function setupNotCached($config): void
    {
        self::$aliases    = [];
        self::$registered = [];
        self::$provided   = [];
        self::$services   = [];
        self::$singletons = [];

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
    protected function setupAnnotations($config): void
    {
        /** @var Annotator $containerAnnotations */
        $containerAnnotations = $this->getSingleton(Annotator::class);

        // Get all the annotated services from the list of controllers and iterate through the services
        foreach ($containerAnnotations->getServices(...$config['services']) as $service) {
            // Set the service
            $this->bind($service->getId(), $service->getClass());
        }

        // Get all the annotated services from the list of controllers and iterate through the services
        /** @var Context $context */
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
     * Setup service providers.
     *
     * @param ContainerConfig|array $config
     *
     * @return void
     */
    protected function setupServiceProviders($config): void
    {
        // Iterate through all the providers
        foreach ($config['providers'] as $key => $provider) {
            $this->register($provider);

            /** @var Provider $provider */
            if ($provider::deferred()) {
                unset($config['providers'][$key]);
            }
        }

        // If this is not a dev environment
        if (! $this->debug) {
            return;
        }

        // Iterate through all the providers
        foreach ($config['devProviders'] as $key => $devProvider) {
            $this->register($devProvider);

            /** @var Provider $devProvider */
            if ($devProvider::deferred()) {
                unset($config['devProviders'][$key]);
            }
        }

        // Set the container config
        $this->config = $config;
    }

    /**
     * After setup.
     *
     * @return void
     */
    protected function afterSetup(): void
    {
    }

    /**
     * Get the cache model.
     *
     * @return Cache
     */
    protected function getCacheModel(): Cache
    {
        $config             = new Cache();
        $config->aliases    = self::$aliases;
        $config->provided   = self::$provided;
        $config->services   = self::$services;
        $config->singletons = self::$singletons;

        return $config;
    }
}
