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

use Valkyrja\Application\Application;
use Valkyrja\Container\Container as Contract;
use Valkyrja\Container\Service;
use Valkyrja\Support\Providers\ProvidersAwareTrait;
use Valkyrja\Support\Providers\Provides;
use Valkyrja\Support\Type\Cls;

/**
 * Class Container.
 *
 * @author Melech Mizrachi
 */
class Container implements Contract
{
    use Provides;
    use ProvidersAwareTrait;

    /**
     * The aliases.
     *
     * @var string[]
     */
    protected static array $aliases = [];

    /**
     * The context services.
     *
     * @var string[]
     */
    protected static array $contextServices = [];

    /**
     * The instances.
     *
     * @var array
     */
    protected static array $instances = [];

    /**
     * The services.
     *
     * @var Service[]
     */
    protected static array $services = [];

    /**
     * The singletons.
     *
     * @var string[]
     */
    protected static array $singletons = [];

    /**
     * The config.
     *
     * @var array
     */
    protected array $config;

    /**
     * The context class or function name.
     *
     * @var string|null
     */
    protected ?string $context = null;

    /**
     * The context method name.
     *
     * @var string|null
     */
    protected ?string $contextMethod = null;

    /**
     * Whether to run in debug.
     *
     * @var bool
     */
    protected bool $debug = false;

    /**
     * Container constructor.
     *
     * @param array $config
     * @param bool  $debug
     */
    public function __construct(array $config, bool $debug = false)
    {
        $this->config = $config;
        $this->debug  = $debug;
    }

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            Contract::class,
        ];
    }

    /**
     * Publish the provider.
     *
     * @param Application $app The application
     *
     * @return void
     */
    public static function publish(Application $app): void
    {
        $container = new static((array) $app->config()['container'], $app->debug());

        $app->setContainer($container);
    }

    /**
     * Get a container with context.
     *
     * @param string $context The context class or function name
     * @param string $member  [optional] The context method name
     *
     * @return static
     */
    public function withContext(string $context, string $member = null): self
    {
        $contextContainer = clone $this;

        $contextContainer->context       = $context;
        $contextContainer->contextMethod = $member;

        return $contextContainer;
    }

    /**
     * Check whether a given service exists.
     *
     * @param string $serviceId The service
     *
     * @return bool
     */
    public function has(string $serviceId): bool
    {
        return isset(self::$services[$serviceId]) || isset(self::$aliases[$serviceId]);
    }

    /**
     * Check whether a given service has context.
     *
     * @param string      $serviceId The service id
     * @param string      $context   The context class or function name
     * @param string|null $member    [optional] The context member name
     *
     * @return bool
     */
    public function hasContext(string $serviceId, string $context, string $member = null): bool
    {
        $contextIndex = $this->getContextServiceId($serviceId, $context, $member);

        return isset(self::$contextServices[$contextIndex]);
    }

    /**
     * Bind a service to the container.
     *
     * @param string $serviceId The service id
     * @param string $service   The service
     *
     * @return void
     */
    public function bind(string $serviceId, string $service): void
    {
        Cls::validateInherits($service, Service::class);

        self::$services[$serviceId] = $service;
    }

    /**
     * Bind a singleton to the container.
     *
     * @param string $serviceId The service id
     * @param string $singleton The singleton service
     *
     * @return void
     */
    public function bindSingleton(string $serviceId, string $singleton): void
    {
        self::$singletons[$serviceId] = $singleton;

        $this->bind($singleton, $singleton);
    }

    /**
     * Set an alias to the container.
     *
     * @param string $alias     The alias
     * @param string $serviceId The service to return
     *
     * @return void
     */
    public function setAlias(string $alias, string $serviceId): void
    {
        self::$aliases[$alias] = $serviceId;
    }

    /**
     * Bind a context to the container.
     *
     * @param string      $serviceId The service id
     * @param string      $context   The context class or function name
     * @param string|null $member    [optional] The context member name
     *
     * @return void
     */
    public function setContext(string $serviceId, string $context, string $member = null): void
    {
        $contextIndex = $this->getContextServiceId($serviceId, $context, $member);

        self::$contextServices[$contextIndex] = $serviceId;
    }

    /**
     * Bind a singleton to the container.
     *
     * @param string $serviceId The service
     * @param mixed  $singleton The singleton
     */
    public function setSingleton(string $serviceId, $singleton): void
    {
        self::$singletons[$serviceId] = $serviceId;
        self::$instances[$serviceId]  = $singleton;
    }

    /**
     * Check whether a given service is an alias.
     *
     * @param string $serviceId The service
     *
     * @return bool
     */
    public function isAlias(string $serviceId): bool
    {
        return isset(self::$aliases[$serviceId]);
    }

    /**
     * Check whether a given service is a singleton.
     *
     * @param string $serviceId The service
     *
     * @return bool
     */
    public function isSingleton(string $serviceId): bool
    {
        return isset(self::$singletons[$serviceId]);
    }

    /**
     * Get a service from the container.
     *
     * @param string $serviceId The service
     * @param array  $arguments [optional] The arguments
     *
     * @return mixed
     */
    public function get(string $serviceId, array $arguments = [])
    {
        if (null !== $this->context) {
            $serviceId = $this->getContextServiceId($serviceId, $this->context, $this->contextMethod);
        }

        // If this service is an alias
        if ($this->isAlias($serviceId)) {
            $serviceId = self::$aliases[$serviceId];
        }

        // Check if the service id is provided by a deferred service provider
        if ($this->isProvided($serviceId)) {
            $this->initializeProvided($serviceId);
        }

        // If the service is a singleton
        if ($this->isSingleton($serviceId)) {
            // Return the singleton
            return $this->getSingleton($serviceId);
        }

        // If the service is in the container
        if ($this->has($serviceId)) {
            // Return the made service
            return $this->makeService($serviceId, $arguments);
        }

        // Return a new object with the arguments
        return new $serviceId(...$arguments);
    }

    /**
     * Make a service.
     *
     * @param string     $serviceId The service id
     * @param array|null $arguments [optional] The arguments
     *
     * @return mixed
     */
    public function makeService(string $serviceId, array $arguments = [])
    {
        $service = self::$services[$serviceId];

        // Make the object by dispatching the service
        $made = $service::make($this, $arguments);

        // If the service is a singleton
        if ($service->isSingleton()) {
            // Set singleton
            $this->setSingleton($serviceId, $made);
        }

        return $made;
    }

    /**
     * Get a singleton from the container.
     *
     * @param string $serviceId The service
     *
     * @return mixed
     */
    public function getSingleton(string $serviceId)
    {
        // If the service isn't a singleton but is provided
        if (! isset(self::$instances[$serviceId]) && $this->isProvided($serviceId)) {
            // Initialize the provided service
            $this->initializeProvided($serviceId);
        }

        return self::$instances[$serviceId] ?? self::$instances[$serviceId] = $this->makeService($serviceId);
    }

    /**
     * Get the context service id.
     *
     * @param string      $serviceId The service id
     * @param string      $context   The context class or function name
     * @param string|null $member    [optional] The context member name
     *
     * @return string
     */
    public function getContextServiceId(string $serviceId, string $context = null, string $member = null): string
    {
        // service@class
        // service@method
        // service@class::method
        return $serviceId . '@' . ($context ?? '') . ($context && $member ? '::' : '') . ($member ?? '');
    }

    /**
     * Offset set.
     *
     * @param string|null $serviceId The service id
     * @param mixed       $service   The service
     *
     * @return void
     */
    public function offsetSet($serviceId, $service): void
    {
        $this->bind($serviceId, $service);
    }

    /**
     * Offset exists.
     *
     * @param string $serviceId The service id
     *
     * @return bool
     */
    public function offsetExists($serviceId): bool
    {
        return $this->has($serviceId);
    }

    /**
     * Offset unset.
     *
     * @param string $serviceId The service id
     *
     * @return void
     */
    public function offsetUnset($serviceId): void
    {
        unset(self::$services[$serviceId]);
    }

    /**
     * Offset get.
     *
     * @param string $serviceId The service id
     *
     * @return mixed
     */
    public function offsetGet($serviceId)
    {
        return $this->get($serviceId);
    }
}
