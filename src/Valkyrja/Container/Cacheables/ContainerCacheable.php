<?php

declare(strict_types = 1);

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
use Valkyrja\Config\Enums\ConfigKey;
use Valkyrja\Config\Enums\ConfigKeyPart;
use Valkyrja\Container\Annotation\ContainerAnnotations;
use Valkyrja\Container\Exceptions\EndlessContextLoopException;
use Valkyrja\Container\Exceptions\InvalidContextException;
use Valkyrja\Container\Exceptions\InvalidServiceIdException;
use Valkyrja\Container\Service;
use Valkyrja\Container\ServiceContext;
use Valkyrja\Dispatcher\Exceptions\InvalidClosureException;
use Valkyrja\Dispatcher\Exceptions\InvalidDispatchCapabilityException;
use Valkyrja\Dispatcher\Exceptions\InvalidFunctionException;
use Valkyrja\Dispatcher\Exceptions\InvalidMethodException;
use Valkyrja\Dispatcher\Exceptions\InvalidPropertyException;
use Valkyrja\Support\Cacheables\Cacheable;
use Valkyrja\Support\Providers\ProvidersAwareTrait;

/**
 * Trait ContainerCacheable.
 *
 * @author Melech Mizrachi
 */
trait ContainerCacheable
{
    use Cacheable;
    use ProvidersAwareTrait;

    /**
     * The application.
     *
     * @var Application
     */
    protected Application $app;

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
     * @return array
     */
    protected function getConfig(): array
    {
        return $this->app->config(ConfigKeyPart::CONTAINER);
    }

    /**
     * Set not cached.
     *
     * @return void
     */
    protected function setupNotCached(): void
    {
        self::$registered = [];
        self::$services   = [];
        self::$provided   = [];

        // Setup service providers
        $this->setupServiceProviders();
    }

    /**
     * Setup the container from cache.
     *
     * @return void
     */
    protected function setupFromCache(): void
    {
        // Set the application container with said file
        $cache = $this->app->config(ConfigKey::CACHE_CONTAINER)
            ?? require $this->app->config(ConfigKey::CONTAINER_CACHE_FILE_PATH);

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
     * @throws EndlessContextLoopException
     * @throws InvalidServiceIdException
     * @throws InvalidClosureException
     * @throws InvalidDispatchCapabilityException
     * @throws InvalidFunctionException
     * @throws InvalidMethodException
     * @throws InvalidPropertyException
     * @throws InvalidContextException
     *
     * @return void
     */
    protected function setupAnnotations(): void
    {
        /** @var ContainerAnnotations $containerAnnotations */
        $containerAnnotations = $this->getSingleton(ContainerAnnotations::class);

        // Get all the annotated services from the list of controllers
        $services = $containerAnnotations->getServices(
            ...$this->app->config(ConfigKey::CONTAINER_SERVICES)
        );

        // Iterate through the services
        foreach ($services as $service) {
            // Set the service
            $this->bind($service);
        }

        // Get all the annotated services from the list of controllers
        $contextServices = $containerAnnotations->getContextServices(
            ...$this->app->config(ConfigKey::CONTAINER_CONTEXT_SERVICES)
        );

        // Iterate through the services
        foreach ($contextServices as $context) {
            // Set the service
            $this->context($context);
        }

        // Get all the annotated services from the list of classes
        $aliasServices = $containerAnnotations->getAliasServices(
            ...$this->app->config(ConfigKey::CONTAINER_SERVICES)
        );

        // Iterate through the services
        foreach ($aliasServices as $alias) {
            // Set the service
            $this->alias($alias->getName(), $alias->getId());
        }
    }

    /**
     * Setup service providers.
     *
     * @return void
     */
    protected function setupServiceProviders(): void
    {
        /** @var array $providers */
        $providers = $this->app->config(ConfigKey::CONTAINER_PROVIDERS);

        // Iterate through all the providers
        foreach ($providers as $provider) {
            $this->register($provider);
        }

        // If this is not a dev environment
        if (! $this->app->debug()) {
            return;
        }

        /** @var array $devProviders */
        $devProviders = $this->app->config(ConfigKey::CONTAINER_DEV_PROVIDERS);

        // Iterate through all the providers
        foreach ($devProviders as $provider) {
            $this->register($provider);
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
     * Set an alias to the container.
     *
     * @param string $alias     The alias
     * @param string $serviceId The service to return
     *
     * @return void
     */
    abstract public function alias(string $alias, string $serviceId): void;

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
    abstract public function context(ServiceContext $serviceContext): void;

    /**
     * Get a singleton from the container.
     *
     * @param string $serviceId The service
     *
     * @return mixed
     */
    abstract public function getSingleton(string $serviceId);
}
