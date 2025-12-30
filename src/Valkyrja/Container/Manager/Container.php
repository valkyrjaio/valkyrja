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

namespace Valkyrja\Container\Manager;

use Override;
use Valkyrja\Container\Contract\Service;
use Valkyrja\Container\Data\Data;
use Valkyrja\Container\Manager\Contract\Container as Contract;
use Valkyrja\Container\Manager\Trait\ProvidersAwareTrait;
use Valkyrja\Container\Throwable\Exception\InvalidArgumentException;

use function assert;
use function class_exists;

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
     * @var array<class-string, class-string>
     */
    protected array $aliases = [];

    /**
     * The instances.
     *
     * @var array<class-string, object>
     */
    protected array $instances = [];

    /**
     * The services.
     *
     * @var array<class-string<Service>, class-string<Service>>
     */
    protected array $services = [];

    /**
     * The service callables.
     *
     * @var array<class-string, callable(Container, mixed...):object>
     */
    protected array $callables = [];

    /**
     * The singletons.
     *
     * @var array<class-string, class-string>
     */
    protected array $singletons = [];

    /**
     * Container constructor.
     */
    public function __construct(
        protected Data $data = new Data()
    ) {
        $this->aliases          = $data->aliases;
        $this->deferred         = $data->deferred;
        $this->deferredCallback = $data->deferredCallback;
        $this->services         = $data->services;
        $this->singletons       = $data->singletons;
        $this->registered       = [];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getData(): Data
    {
        return new Data(
            aliases: $this->aliases,
            deferred: $this->deferred,
            deferredCallback: $this->deferredCallback,
            services: [],
            singletons: [],
            providers: array_filter($this->providers, static fn (string $provider): bool => ! $provider::deferred()),
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function setFromData(Data $data): void
    {
        $this->aliases          = array_merge($this->aliases, $data->aliases);
        $this->deferred         = array_merge($this->deferred, $data->deferred);
        $this->deferredCallback = array_merge($this->deferredCallback, $data->deferredCallback);
        $this->services         = array_merge($this->services, $data->services);
        $this->singletons       = array_merge($this->singletons, $data->singletons);

        array_map(
            [$this, 'register'],
            $data->providers
        );
    }

    /**
     * @inheritDoc
     *
     * @param class-string $id The service id
     *
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    #[Override]
    public function has(string $id): bool
    {
        $id = $this->getServiceIdInternal($id);

        return $this->isDeferred($id)
            || $this->isSingletonInternal($id)
            || $this->isServiceInternal($id)
            || $this->isCallableInternal($id)
            || $this->isAliasInternal($id);
    }

    /**
     * @inheritDoc
     *
     * @param class-string          $id      The service id
     * @param class-string<Service> $service The service
     */
    #[Override]
    public function bind(string $id, string $service): static
    {
        assert(is_a($service, Service::class, true));

        $id = $this->getServiceIdInternal($id);

        /** @var class-string<Service> $id */
        $this->services[$id] = $service;

        return $this;
    }

    /**
     * @inheritDoc
     *
     * @param class-string $alias The alias
     * @param class-string $id    The service id to alias
     */
    #[Override]
    public function bindAlias(string $alias, string $id): static
    {
        $id = $this->getServiceIdInternal($id);

        $this->aliases[$alias] = $id;

        return $this;
    }

    /**
     * @inheritDoc
     *
     * @param class-string          $id        The service id
     * @param class-string<Service> $singleton The singleton service
     */
    #[Override]
    public function bindSingleton(string $id, string $singleton): static
    {
        $internalId = $this->getServiceIdInternal($id);

        $this->singletons[$internalId] = $singleton;

        $this->bind($id, $singleton);

        return $this;
    }

    /**
     * @inheritDoc
     *
     * @param class-string $id The service id
     */
    #[Override]
    public function setCallable(string $id, callable $callable): static
    {
        $id = $this->getServiceIdInternal($id);

        /** @var callable(Contract, mixed...):object $callable */
        $this->callables[$id] = $callable;
        $this->published[$id] = true;

        return $this;
    }

    /**
     * @inheritDoc
     *
     * @param class-string $id The service id
     */
    #[Override]
    public function setSingleton(string $id, object $singleton): static
    {
        $id = $this->getServiceIdInternal($id);

        $this->singletons[$id] = $id;
        $this->instances[$id]  = $singleton;
        $this->published[$id]  = true;

        return $this;
    }

    /**
     * @inheritDoc
     *
     * @param class-string $id The service id
     */
    #[Override]
    public function isAlias(string $id): bool
    {
        return $this->isAliasInternal($id);
    }

    /**
     * @inheritDoc
     *
     * @param class-string $id The service id
     */
    #[Override]
    public function isCallable(string $id): bool
    {
        $id = $this->getServiceIdInternal($id);

        return $this->isCallableInternal($id);
    }

    /**
     * @inheritDoc
     *
     * @param class-string $id The service id
     */
    #[Override]
    public function isService(string $id): bool
    {
        $id = $this->getServiceIdInternal($id);

        return $this->isServiceInternal($id);
    }

    /**
     * @inheritDoc
     *
     * @param class-string $id The service id
     */
    #[Override]
    public function isSingleton(string $id): bool
    {
        $id = $this->getServiceIdInternal($id);

        return $this->isSingletonInternal($id);
    }

    /**
     * @inheritDoc
     *
     * @param class-string $id The service id
     *
     * @psalm-suppress InvalidReturnType
     * @psalm-suppress InvalidReturnStatement
     * @psalm-suppress ImplementedReturnTypeMismatch
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    #[Override]
    public function get(string $id, array $arguments = []): object
    {
        $id = $this->getServiceIdAndEnsurePublished($id);

        // If the service is a singleton
        if ($this->isSingletonInternal($id)) {
            // Return the singleton
            // @phpstan-ignore-next-line
            return $this->getSingletonWithoutChecks($id);
        }

        // If the service is a singleton
        if ($this->isCallableInternal($id)) {
            // Return the closure
            // @phpstan-ignore-next-line
            return $this->getCallableWithoutChecks($id, $arguments);
        }

        // If the service is in the container
        if ($this->isServiceInternal($id)) {
            /** @var class-string<Service> $id */
            // Return the made service
            // @phpstan-ignore-next-line
            return $this->getServiceWithoutChecks($id, $arguments);
        }

        if (class_exists($id)) {
            /** @psalm-suppress MixedMethodCall The developer should have passed the proper arguments */
            // Return a new object with the arguments
            // @phpstan-ignore-next-line
            return new $id(...$arguments);
        }

        throw new InvalidArgumentException("Provided $id does not exist");
    }

    /**
     * @inheritDoc
     *
     * @param class-string $id The service id
     *
     * @psalm-suppress InvalidReturnType
     * @psalm-suppress InvalidReturnStatement
     * @psalm-suppress ImplementedReturnTypeMismatch
     */
    #[Override]
    public function getCallable(string $id, array $arguments = []): object
    {
        $id = $this->getServiceIdAndEnsurePublished($id);

        // @phpstan-ignore-next-line
        return $this->getCallableWithoutChecks($id, $arguments);
    }

    /**
     * @inheritDoc
     *
     * @param class-string $id The service id
     *
     * @psalm-suppress InvalidReturnType
     * @psalm-suppress InvalidReturnStatement
     * @psalm-suppress ImplementedReturnTypeMismatch
     */
    #[Override]
    public function getService(string $id, array $arguments = []): Service
    {
        $id = $this->getServiceIdAndEnsurePublished($id);

        /** @var class-string<Service> $id */

        return $this->getServiceWithoutChecks($id, $arguments);
    }

    /**
     * @inheritDoc
     *
     * @param class-string $id The service id
     *
     * @psalm-suppress InvalidReturnType
     * @psalm-suppress InvalidReturnStatement
     * @psalm-suppress ImplementedReturnTypeMismatch
     */
    #[Override]
    public function getSingleton(string $id): object
    {
        $id = $this->getServiceIdAndEnsurePublished($id);

        // @phpstan-ignore-next-line
        return $this->getSingletonWithoutChecks($id);
    }

    /**
     * Get an aliased service id if it exists.
     *
     * @param class-string $id The service id
     *
     * @return class-string
     */
    protected function getAliasedServiceId(string $id): string
    {
        return $this->aliases[$id] ?? $id;
    }

    /**
     * Get a service id and ensure that it is published if it is provided.
     *
     * @param class-string $id The service id
     *
     * @return class-string
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
     * @param class-string $id The service id
     *
     * @return class-string
     */
    protected function getServiceIdInternal(string $id): string
    {
        return $this->getAliasedServiceId($id);
    }

    /**
     * Check whether a given service is an alias.
     *
     * @param class-string $id The service id
     *
     * @return bool
     */
    protected function isAliasInternal(string $id): bool
    {
        return isset($this->aliases[$id]);
    }

    /**
     * Check whether a given service is bound to a callable.
     *
     * @param class-string $id The service id
     *
     * @return bool
     */
    protected function isCallableInternal(string $id): bool
    {
        return isset($this->callables[$id]);
    }

    /**
     * Check whether a given service is a singleton.
     *
     * @param class-string $id The service id
     *
     * @return bool
     */
    protected function isSingletonInternal(string $id): bool
    {
        return isset($this->singletons[$id]);
    }

    /**
     * Check whether a given service exists.
     *
     * @param class-string $id The service id
     *
     * @return bool
     */
    protected function isServiceInternal(string $id): bool
    {
        return isset($this->services[$id]);
    }

    /**
     * Get a service bound to a callable from the container without trying to get an alias or ensuring published.
     *
     * @param class-string            $id        The service id
     * @param array<array-key, mixed> $arguments [optional] The arguments
     *
     * @return object
     */
    protected function getCallableWithoutChecks(string $id, array $arguments = []): object
    {
        $closure = $this->callables[$id];

        return $closure($this, ...$arguments);
    }

    /**
     * Get a singleton from the container without trying to get an alias or ensuring published.
     *
     * @param class-string $id The service id
     *
     * @return object
     */
    protected function getSingletonWithoutChecks(string $id): object
    {
        if (isset($this->instances[$id])) {
            return $this->instances[$id];
        }

        if (isset($this->services[$id])) {
            /** @var class-string<Service> $id */
            return $this->instances[$id] = $this->getServiceWithoutChecks($id);
        }

        throw new InvalidArgumentException("Provided $id does not exist");
    }

    /**
     * Get a service from the container without trying to get an alias or ensuring published.
     *
     * @param class-string<Service>   $id        The service id
     * @param array<array-key, mixed> $arguments [optional] The arguments
     *
     * @return Service
     */
    protected function getServiceWithoutChecks(string $id, array $arguments = []): Service
    {
        $service = $this->services[$id];

        // Make the object by dispatching the service
        return $service::make($this, $arguments);
    }
}
