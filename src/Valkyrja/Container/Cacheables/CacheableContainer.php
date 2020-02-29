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
use Valkyrja\Config\Enums\ConfigKeyPart;
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
     * @return ContainerConfig|object
     */
    protected function getConfig(): object
    {
        return $this->app->config()->container;
    }

    /**
     * Set not cached.
     *
     * @param ContainerConfig|object $config
     *
     * @return void
     */
    protected function setupNotCached(object $config): void
    {
        self::$registered = [];
        self::$services   = [];
        self::$provided   = [];

        // Setup service providers
        $this->setupServiceProviders($config);
    }

    /**
     * Setup the container from cache.
     *
     * @param ContainerConfig|object $config
     *
     * @return void
     */
    protected function setupFromCache(object $config): void
    {
        // Set the application container with said file
        $cache = $config->cache ?? require $config->cacheFilePath;

        self::$services = unserialize(
            base64_decode($cache[ConfigKeyPart::SERVICES], true),
            [
                'allowed_classes' => [
                    Service::class,
                ],
            ]
        );
        self::$provided = $cache[ConfigKeyPart::PROVIDED];
        self::$aliases  = $cache[ConfigKeyPart::ALIASES];
    }

    /**
     * Setup annotations.
     *
     * @param ContainerConfig|object $config
     *
     * @return void
     */
    protected function setupAnnotations(object $config): void
    {
        /** @var ContainerAnnotator $containerAnnotations */
        $containerAnnotations = $this->getSingleton(ContainerAnnotator::class);

        // Get all the annotated services from the list of controllers
        // Iterate through the services
        foreach ($containerAnnotations->getServices(...$config->services) as $service) {
            // Set the service
            $this->bind($service);
        }

        // Get all the annotated services from the list of controllers
        // Iterate through the services
        foreach ($containerAnnotations->getContextServices(...$config->contextServices) as $context) {
            // Set the service
            $this->setContext($context);
        }

        // Get all the annotated services from the list of classes
        // Iterate through the services
        foreach ($containerAnnotations->getAliasServices(...$config->aliases) as $alias) {
            // Set the service
            $this->setAlias($alias->getName(), $alias->getId());
        }
    }

    /**
     * Get a cacheable representation of the service container.
     *
     * @return array
     */
    public function getCacheable(): array
    {
        $this->setup(true, false);

        return [
            ConfigKeyPart::SERVICES => base64_encode(serialize(self::$services)),
            ConfigKeyPart::ALIASES  => self::$aliases,
            ConfigKeyPart::PROVIDED => self::$provided,
        ];
    }

    /**
     * Setup service providers.
     *
     * @param ContainerConfig|object $config
     *
     * @return void
     */
    protected function setupServiceProviders(object $config): void
    {
        // Iterate through all the providers
        foreach ($config->providers as $provider) {
            $this->register($provider);
        }

        // If this is not a dev environment
        if (! $this->app->debug()) {
            return;
        }

        // Iterate through all the providers
        foreach ($config->devProviders as $provider) {
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
