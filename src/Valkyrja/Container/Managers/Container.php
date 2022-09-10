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

use Closure;
use Valkyrja\Container\Container as Contract;
use Valkyrja\Container\Service;
use Valkyrja\Support\Facade\Facade;
use Valkyrja\Support\Provider\Traits\ProvidersAwareTrait;
use Valkyrja\Support\Type\Cls;

/**
 * Class Container.
 *
 * @author Melech Mizrachi
 */
class Container implements Contract
{
    use ProvidersAwareTrait;

    /**
     * Has the Facade been setup?
     *
     * @var bool
     */
    protected static bool $facadeSetup = false;

    /**
     * The aliases.
     *
     * @var string[]
     */
    protected static array $aliases = [];

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
     * The service closures.
     *
     * @var Closure[]
     */
    protected static array $closures = [];

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
     * The context id.
     *
     * @var string|null
     */
    protected ?string $contextId = null;

    /**
     * The context method name.
     *
     * @var string|null
     */
    protected ?string $contextMember = null;

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

        if (! self::$facadeSetup && $config['setupFacade']) {
            Facade::setContainer($this);
        }
    }

    /**
     * @inheritDoc
     */
    public function withContext(string $context, string $member = null): self
    {
        $contextContainer = clone $this;

        $contextContainer->context       = $context;
        $contextContainer->contextMember = $member;
        $contextContainer->contextId     = $this->getServiceId('', $context, $member);

        return $contextContainer;
    }

    /**
     * @inheritDoc
     */
    public function withoutContext(): self
    {
        $contextContainer = clone $this;

        $contextContainer->context       = null;
        $contextContainer->contextMember = null;
        $contextContainer->contextId     = null;

        return $contextContainer;
    }

    /**
     * @inheritDoc
     */
    public function has(string $serviceId): bool
    {
        $serviceId = $this->getServiceIdInternal($serviceId);

        return $this->isProvided($serviceId)
            || isset(self::$services[$serviceId])
            || isset(self::$singletons[$serviceId])
            || isset(self::$aliases[$serviceId])
            || isset(self::$closures[$serviceId]);
    }

    /**
     * @inheritDoc
     */
    public function bind(string $serviceId, string $service): self
    {
        Cls::validateInherits($service, Service::class);

        $serviceId = $this->getServiceIdInternal($serviceId);

        self::$services[$serviceId] = $service;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function bindClosure(string $serviceId, Closure $closure): self
    {
        $serviceId = $this->getServiceIdInternal($serviceId);

        self::$closures[$serviceId] = $closure;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function bindSingleton(string $serviceId, string $singleton): self
    {
        $serviceId = $this->getServiceIdInternal($serviceId);

        self::$singletons[$serviceId] = $singleton;

        $this->bind($singleton, $singleton);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setAlias(string $alias, string $serviceId): self
    {
        $serviceId = $this->getServiceIdInternal($serviceId);

        self::$aliases[$alias]   = $serviceId;
        self::$published[$alias] = true;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setClosure(string $serviceId, Closure $closure): self
    {
        $serviceId = $this->getServiceIdInternal($serviceId);

        self::$closures[$serviceId]  = $closure;
        self::$published[$serviceId] = true;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setSingleton(string $serviceId, $singleton): self
    {
        $serviceId = $this->getServiceIdInternal($serviceId);

        self::$singletons[$serviceId] = $serviceId;
        self::$instances[$serviceId]  = $singleton;
        self::$published[$serviceId]  = true;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function isAlias(string $serviceId): bool
    {
        $serviceId = $this->getServiceIdInternal($serviceId);

        return $this->isAliasInternal($serviceId);
    }

    /**
     * @inheritDoc
     */
    public function isClosure(string $serviceId): bool
    {
        $serviceId = $this->getServiceIdInternal($serviceId);

        return $this->isClosureInternal($serviceId);
    }

    /**
     * @inheritDoc
     */
    public function isSingleton(string $serviceId): bool
    {
        $serviceId = $this->getServiceIdInternal($serviceId);

        return $this->isSingletonInternal($serviceId);
    }

    /**
     * @inheritDoc
     */
    public function isService(string $serviceId): bool
    {
        $serviceId = $this->getServiceIdInternal($serviceId);

        return $this->isServiceInternal($serviceId);
    }

    /**
     * @inheritDoc
     */
    public function get(string $serviceId, array $arguments = []): mixed
    {
        $serviceId = $this->getServiceIdAndEnsurePublished($serviceId);

        // If the service is a singleton
        if ($this->isSingletonInternal($serviceId)) {
            // Return the singleton
            return $this->getSingletonWithoutChecks($serviceId);
        }

        // If the service is a singleton
        if ($this->isClosureInternal($serviceId)) {
            // Return the closure
            return $this->getClosureWithoutChecks($serviceId, $arguments);
        }

        // If the service is in the container
        if ($this->isServiceInternal($serviceId)) {
            // Return the made service
            return $this->makeServiceWithoutChecks($serviceId, $arguments);
        }

        // Return a new object with the arguments
        return new $serviceId(...$arguments);
    }

    /**
     * @inheritDoc
     */
    public function getClosure(string $serviceId, array $arguments = []): mixed
    {
        $serviceId = $this->getServiceIdAndEnsurePublished($serviceId);

        return $this->getClosureWithoutChecks($serviceId, $arguments);
    }

    /**
     * @inheritDoc
     */
    public function getSingleton(string $serviceId): mixed
    {
        $serviceId = $this->getServiceIdAndEnsurePublished($serviceId);

        return $this->getSingletonWithoutChecks($serviceId);
    }

    /**
     * @inheritDoc
     */
    public function makeService(string $serviceId, array $arguments = []): mixed
    {
        $serviceId = $this->getServiceIdAndEnsurePublished($serviceId);

        return $this->makeServiceWithoutChecks($serviceId, $arguments);
    }

    /**
     * @inheritDoc
     */
    public function getServiceId(string $serviceId, string $context = null, string $member = null): string
    {
        if (null === $this->context) {
            return $serviceId;
        }

        // serviceId@context
        // serviceId@context::method
        return $serviceId . '@' . $context . ($member ? '::' . $member : '');
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value): void
    {
        $this->bind($offset, $value);
    }

    /**
     * @inheritDoc
     */
    public function offsetExists($offset): bool
    {
        return $this->has($offset);
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset($offset): void
    {
        unset(self::$services[$offset]);
    }

    /**
     * @inheritDoc
     */
    public function offsetGet($offset): mixed
    {
        return $this->get($offset);
    }

    /**
     * Ensure a provided service is published.
     *
     * @param string $serviceId The service id
     *
     * @return void
     */
    protected function ensureProvidedServiceIsPublished(string $serviceId): void
    {
        // Check if the service id is provided by a service provider and isn't already published
        if ($this->isProvided($serviceId) && ! $this->isPublished($serviceId)) {
            // Publish the service provider
            $this->publishProvided($serviceId);
        }
    }

    /**
     * Get an aliased service id if it exists.
     *
     * @param string $serviceId The service id
     *
     * @return string
     */
    protected function getAliasedServiceId(string $serviceId): string
    {
        return self::$aliases[$serviceId] ?? $serviceId;
    }

    /**
     * Get a service id and ensure that it is published if it is provided.
     *
     * @param string $serviceId The service id
     *
     * @return string
     */
    protected function getServiceIdAndEnsurePublished(string $serviceId): string
    {
        // Get an aliased service id if it exists
        $serviceId = $this->getServiceIdInternal($serviceId);

        $this->ensureProvidedServiceIsPublished($serviceId);

        return $serviceId;
    }

    /**
     * Get the context service id.
     *
     * @param string $serviceId The service id
     *
     * @return string
     */
    protected function getServiceIdInternal(string $serviceId): string
    {
        $serviceId = $this->getAliasedServiceId($serviceId);

        if (null === $this->context) {
            return $serviceId;
        }

        // serviceId@context
        // serviceId@context::method
        return $serviceId . $this->contextId;
    }

    /**
     * Check whether a given service is an alias.
     *
     * @param string $serviceId The service id
     *
     * @return bool
     */
    protected function isAliasInternal(string $serviceId): bool
    {
        return isset(self::$aliases[$serviceId]);
    }

    /**
     * Check whether a given service is bound to a closure.
     *
     * @param string $serviceId The service id
     *
     * @return bool
     */
    protected function isClosureInternal(string $serviceId): bool
    {
        return isset(self::$closures[$serviceId]);
    }

    /**
     * Check whether a given service is a singleton.
     *
     * @param string $serviceId The service id
     *
     * @return bool
     */
    protected function isSingletonInternal(string $serviceId): bool
    {
        return isset(self::$singletons[$serviceId]);
    }

    /**
     * Check whether a given service exists.
     *
     * @param string $serviceId The service id
     *
     * @return bool
     */
    protected function isServiceInternal(string $serviceId): bool
    {
        return isset(self::$services[$serviceId]);
    }

    /**
     * Get a service bound to a closure from the container.
     *
     * @param string $serviceId The service id
     * @param array  $arguments [optional] The arguments
     *
     * @return mixed
     */
    protected function getClosureWithoutChecks(string $serviceId, array $arguments = []): mixed
    {
        $closure = self::$closures[$serviceId];

        return $closure(...$arguments);
    }

    /**
     * Get a singleton from the container.
     *
     * @param string $serviceId The service id
     *
     * @return mixed
     */
    protected function getSingletonWithoutChecks(string $serviceId): mixed
    {
        return self::$instances[$serviceId] ?? self::$instances[$serviceId] = $this->makeService($serviceId);
    }

    /**
     * Make a service.
     *
     * @param string $serviceId The service id
     * @param array  $arguments [optional] The arguments
     *
     * @return Service
     */
    protected function makeServiceWithoutChecks(string $serviceId, array $arguments = []): Service
    {
        // Make the object by dispatching the service
        $made = self::$services[$serviceId]::make($this, $arguments);

        // If the service is a singleton
        if ($this->isSingleton($serviceId)) {
            // Set singleton
            $this->setSingleton($serviceId, $made);
        }

        return $made;
    }
}
