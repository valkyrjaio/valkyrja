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
use RuntimeException;
use Valkyrja\Container\Config\Config;
use Valkyrja\Container\Container as Contract;
use Valkyrja\Container\Service;
use Valkyrja\Support\Provider\Traits\ProvidersAwareTrait;

use function assert;

/**
 * Class Container.
 *
 * @author Melech Mizrachi
 */
class Container implements Contract
{
    use ProvidersAwareTrait;

    /**
     * The aliases.
     *
     * @var array<class-string<Service>|string, class-string<Service>|string>
     */
    protected static array $aliases = [];

    /**
     * The instances.
     *
     * @var array<class-string<Service>|string, mixed>
     */
    protected static array $instances = [];

    /**
     * The services.
     *
     * @var array<class-string<Service>|string, class-string<Service>>
     */
    protected static array $services = [];

    /**
     * The service closures.
     *
     * @var array<class-string<Service>|string, Closure>
     */
    protected static array $closures = [];

    /**
     * The singletons.
     *
     * @var array<class-string<Service>|string, class-string<Service>|string>
     */
    protected static array $singletons = [];

    /**
     * The context class or function name.
     *
     * @var class-string|string|null
     */
    protected string|null $context = null;

    /**
     * The context id.
     *
     * @var string|null
     */
    protected string|null $contextId = null;

    /**
     * Container constructor.
     *
     * @param Config|array $config
     * @param bool         $debug
     */
    public function __construct(
        protected Config|array $config,
        protected bool $debug = false
    ) {
    }

    /**
     * @inheritDoc
     */
    public function has(string $id): bool
    {
        $id = $this->getServiceIdInternal($id);

        return $this->isProvided($id)
            || $this->isSingletonInternal($id)
            || $this->isServiceInternal($id)
            || $this->isClosureInternal($id)
            || $this->isAliasInternal($id);
    }

    /**
     * @inheritDoc
     */
    public function bind(string $id, string $service): static
    {
        assert(is_a($service, Service::class, true));

        $id = $this->getServiceIdInternal($id);

        self::$services[$id] = $service;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function bindAlias(string $alias, string $id): static
    {
        $id = $this->getServiceIdInternal($id);

        self::$aliases[$alias] = $id;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function bindSingleton(string $id, string $singleton): static
    {
        $id = $this->getServiceIdInternal($id);

        self::$singletons[$id] = $singleton;

        $this->bind($singleton, $singleton);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setClosure(string $id, Closure $closure): static
    {
        $id = $this->getServiceIdInternal($id);

        self::$closures[$id]  = $closure;
        self::$published[$id] = true;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setSingleton(string $id, mixed $singleton): static
    {
        $id = $this->getServiceIdInternal($id);

        self::$singletons[$id] = $id;
        self::$instances[$id]  = $singleton;
        self::$published[$id]  = true;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function isAlias(string $id): bool
    {
        return $this->isAliasInternal($id);
    }

    /**
     * @inheritDoc
     */
    public function isClosure(string $id): bool
    {
        $id = $this->getServiceIdInternal($id);

        return $this->isClosureInternal($id);
    }

    /**
     * @inheritDoc
     */
    public function isService(string $id): bool
    {
        $id = $this->getServiceIdInternal($id);

        return $this->isServiceInternal($id);
    }

    /**
     * @inheritDoc
     */
    public function isSingleton(string $id): bool
    {
        $id = $this->getServiceIdInternal($id);

        return $this->isSingletonInternal($id);
    }

    /**
     * @inheritDoc
     */
    public function get(string $id, array $arguments = []): mixed
    {
        $id = $this->getServiceIdAndEnsurePublished($id);

        // If the service is a singleton
        if ($this->isSingletonInternal($id)) {
            // Return the singleton
            return $this->getSingletonWithoutChecks($id);
        }

        // If the service is a singleton
        if ($this->isClosureInternal($id)) {
            // Return the closure
            return $this->getClosureWithoutChecks($id, $arguments);
        }

        // If the service is in the container
        if ($this->isServiceInternal($id)) {
            // Return the made service
            return $this->getServiceWithoutChecks($id, $arguments);
        }

        // Return a new object with the arguments
        return new $id(...$arguments);
    }

    /**
     * @inheritDoc
     */
    public function getClosure(string $id, array $arguments = []): mixed
    {
        $id = $this->getServiceIdAndEnsurePublished($id);

        return $this->getClosureWithoutChecks($id, $arguments);
    }

    /**
     * @inheritDoc
     */
    public function getService(string $id, array $arguments = []): Service
    {
        $id = $this->getServiceIdAndEnsurePublished($id);

        return $this->getServiceWithoutChecks($id, $arguments);
    }

    /**
     * @inheritDoc
     */
    public function getSingleton(string $id): mixed
    {
        $id = $this->getServiceIdAndEnsurePublished($id);

        return $this->getSingletonWithoutChecks($id);
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
        throw new RuntimeException("Cannot remove service with name $offset from the container.");
    }

    /**
     * @inheritDoc
     */
    public function offsetGet($offset): mixed
    {
        return $this->get($offset);
    }

    /**
     * Get an aliased service id if it exists.
     *
     * @param class-string|string $id The service id
     *
     * @return string
     */
    protected function getAliasedServiceId(string $id): string
    {
        return self::$aliases[$id] ?? $id;
    }

    /**
     * Get a service id and ensure that it is published if it is provided.
     *
     * @param class-string|string $id The service id
     *
     * @return string
     */
    protected function getServiceIdAndEnsurePublished(string $id): string
    {
        // Get an aliased service id if it exists
        $id = $this->getServiceIdInternal($id);

        $this->publishUnpublishedProvided($id);

        return $id;
    }

    /**
     * Get the context service id.
     *
     * @param class-string|string $id The service id
     *
     * @return string
     */
    protected function getServiceIdInternal(string $id): string
    {
        $id = $this->getAliasedServiceId($id);

        if ($this->context === null) {
            return $id;
        }

        // serviceId@context
        // serviceId@context::method
        return $id . ($this->contextId ?? '');
    }

    /**
     * Check whether a given service is an alias.
     *
     * @param class-string|string $id The service id
     *
     * @return bool
     */
    protected function isAliasInternal(string $id): bool
    {
        return isset(self::$aliases[$id]);
    }

    /**
     * Check whether a given service is bound to a closure.
     *
     * @param class-string|string $id The service id
     *
     * @return bool
     */
    protected function isClosureInternal(string $id): bool
    {
        return isset(self::$closures[$id]);
    }

    /**
     * Check whether a given service is a singleton.
     *
     * @param class-string|string $id The service id
     *
     * @return bool
     */
    protected function isSingletonInternal(string $id): bool
    {
        return isset(self::$singletons[$id]);
    }

    /**
     * Check whether a given service exists.
     *
     * @param class-string|string $id The service id
     *
     * @return bool
     */
    protected function isServiceInternal(string $id): bool
    {
        return isset(self::$services[$id]);
    }

    /**
     * Get a service bound to a closure from the container without trying to get an alias or ensuring published.
     *
     * @param class-string|string $id        The service id
     * @param array               $arguments [optional] The arguments
     *
     * @return mixed
     */
    protected function getClosureWithoutChecks(string $id, array $arguments = []): mixed
    {
        $closure = self::$closures[$id];

        return $closure(...$arguments);
    }

    /**
     * Get a singleton from the container without trying to get an alias or ensuring published.
     *
     * @param class-string|string $id The service id
     *
     * @return mixed
     */
    protected function getSingletonWithoutChecks(string $id): mixed
    {
        /** @var mixed $instance */
        $instance = self::$instances[$id] ??= $this->getServiceWithoutChecks($id);

        return $instance;
    }

    /**
     * Get a service from the container without trying to get an alias or ensuring published.
     *
     * @param class-string<Service>|string $id        The service id
     * @param array                        $arguments [optional] The arguments
     *
     * @return Service
     */
    protected function getServiceWithoutChecks(string $id, array $arguments = []): Service
    {
        /** @var Service $service */
        $service = self::$services[$id];
        // Make the object by dispatching the service
        $made = $service::make($this, $arguments);

        // If the service is a singleton
        if ($this->isSingleton($id)) {
            // Set singleton
            $this->setSingleton($id, $made);
        }

        return $made;
    }
}
