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

namespace Valkyrja\Container\Cacheables;

use Valkyrja\Application\Application;
use Valkyrja\Config\Configs\ContainerConfig;
use Valkyrja\Container\Annotation\ContainerAnnotator;
use Valkyrja\Container\Service;
use Valkyrja\Container\ServiceContext;
use Valkyrja\Support\Cacheables\Cacheable;
use Valkyrja\Support\Providers\ProvidersAwareTrait;

/**
 * Trait CacheableContainer.
 *
 * @author Melech Mizrachi
 */
trait CacheableContainer
{
    use Cacheable;
    use ProvidersAwareTrait;

    /**
     * The aliases.
     *
     * @var string[]
     */
    protected static array $aliases = [];

    /**
     * The services.
     *
     * @var Service[]
     */
    protected static array $services = [];

    /**
     * The application.
     *
     * @var Application
     */
    protected Application $app;

    /**
     * Get the application.
     *
     * @return Application
     */
    protected function getApplication(): Application
    {
        return $this->app;
    }

    /**
     * Get the config.
     *
     * @return ContainerConfig|array
     */
    protected function getConfig()
    {
        return $this->app->config()['container'];
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

        self::$services = $cache['services'];
        self::$provided = $cache['provided'];
        self::$aliases  = $cache['aliases'];
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
        self::$registered = [];
        self::$services   = [];
        self::$provided   = [];

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
        /** @var ContainerAnnotator $containerAnnotations */
        $containerAnnotations = $this->getSingleton(ContainerAnnotator::class);

        // Get all the annotated services from the list of controllers
        // Iterate through the services
        foreach ($containerAnnotations->getServices(...$config['services']) as $service) {
            // Set the service
            $this->bind($service);
        }

        // Get all the annotated services from the list of controllers
        // Iterate through the services
        foreach ($containerAnnotations->getContextServices(...$config['contextServices']) as $context) {
            // Set the service
            $this->setContext($context);
        }

        // Get all the annotated services from the list of classes
        // Iterate through the services
        foreach ($containerAnnotations->getAliasServices(...$config['aliases']) as $alias) {
            // Set the service
            $this->setAlias($alias->getName(), $alias->getId());
        }
    }

    /**
     * Get a cacheable representation of the service container.
     *
     * @return CacheConfig|object
     */
    public function getCacheable(): object
    {
        $this->setup(true, false);

        $config           = new CacheConfig();
        $config->services = self::$services;
        $config->aliases  = self::$aliases;
        $config->provided = self::$provided;

        return $config;
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
        foreach ($config['providers'] as $provider) {
            $this->register($provider);
        }

        // If this is not a dev environment
        if (! $this->app->debug()) {
            return;
        }

        // Iterate through all the providers
        foreach ($config['devProviders'] as $provider) {
            $this->register($provider);
        }
    }

    /**
     * Set an alias to the container.
     *
     * @param string $alias     The alias
     * @param string $serviceId The service to return
     *
     * @return void
     */
    abstract public function setAlias(string $alias, string $serviceId): void;

    /**
     * Bind a service to the container.
     *
     * @param Service $service The service model
     * @param bool    $verify  [optional] Whether to verify the service
     *
     * @return void
     */
    abstract public function bind(Service $service, bool $verify = true): void;

    /**
     * Bind a context to the container.
     *
     * @param ServiceContext $serviceContext The context service
     *
     * @return void
     */
    abstract public function setContext(ServiceContext $serviceContext): void;

    /**
     * Get a singleton from the container.
     *
     * @param string $serviceId The service
     *
     * @return mixed
     */
    abstract public function getSingleton(string $serviceId);
}
