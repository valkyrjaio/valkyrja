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
use Throwable;
use Valkyrja\Container\Contract\ServiceContract;
use Valkyrja\Container\Data\Data;
use Valkyrja\Container\Enum\InvalidReferenceMode;
use Valkyrja\Container\Manager\Contract\ContainerContract as Contract;
use Valkyrja\Container\Manager\Trait\ProvidersAwareTrait;
use Valkyrja\Container\Throwable\Exception\InvalidReferenceException;

use function array_filter;
use function array_map;
use function array_merge;
use function assert;
use function is_a;
use function is_object;

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
     * @var array<class-string<ServiceContract>, class-string<ServiceContract>>
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
        return $this->isDeferred($id)
            || $this->isSingleton($id)
            || $this->isService($id)
            || $this->isCallable($id)
            || $this->isAlias($id);
    }

    /**
     * @inheritDoc
     *
     * @param class-string                  $id      The service id
     * @param class-string<ServiceContract> $service The service
     */
    #[Override]
    public function bind(string $id, string $service): static
    {
        assert(is_a($service, ServiceContract::class, true));

        /** @var class-string<ServiceContract> $id */
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
        $this->aliases[$alias] = $id;

        return $this;
    }

    /**
     * @inheritDoc
     *
     * @param class-string                  $id        The service id
     * @param class-string<ServiceContract> $singleton The singleton service
     */
    #[Override]
    public function bindSingleton(string $id, string $singleton): static
    {
        $this->singletons[$id] = $singleton;

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
        return isset($this->aliases[$id]);
    }

    /**
     * @inheritDoc
     *
     * @param class-string $id The service id
     */
    #[Override]
    public function isCallable(string $id): bool
    {
        return isset($this->callables[$id]);
    }

    /**
     * @inheritDoc
     *
     * @param class-string $id The service id
     */
    #[Override]
    public function isService(string $id): bool
    {
        return isset($this->services[$id]);
    }

    /**
     * @inheritDoc
     *
     * @param class-string $id The service id
     */
    #[Override]
    public function isSingleton(string $id): bool
    {
        return isset($this->singletons[$id]);
    }

    /**
     * @inheritDoc
     *
     * @psalm-suppress InvalidReturnType
     * @psalm-suppress InvalidReturnStatement
     * @psalm-suppress ImplementedReturnTypeMismatch
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    #[Override]
    public function get(string $id, array $arguments = [], InvalidReferenceMode $mode = InvalidReferenceMode::NEW_INSTANCE_OR_THROW_EXCEPTION): object|null
    {
        $this->publishUnpublishedProvided($id);

        // @phpstan-ignore-next-line
        return $this->getSingletonWithoutChecks($id)
            ?? $this->getCallableWithoutChecks($id, $arguments)
            ?? $this->getServiceWithoutChecks($id, $arguments)
            ?? $this->getAliasedWithoutChecks($id, $arguments)
            ?? $this->getFallback($id, $arguments, $mode);
    }

    /**
     * @inheritDoc
     *
     * @psalm-suppress InvalidReturnType
     * @psalm-suppress InvalidReturnStatement
     * @psalm-suppress ImplementedReturnTypeMismatch
     */
    #[Override]
    public function getAliased(string $id, array $arguments = []): object
    {
        // @phpstan-ignore-next-line
        return $this->getAliasedWithoutChecks($id, $arguments)
            ?? throw new InvalidReferenceException($id);
    }

    /**
     * @inheritDoc
     *
     * @psalm-suppress InvalidReturnType
     * @psalm-suppress InvalidReturnStatement
     * @psalm-suppress ImplementedReturnTypeMismatch
     */
    #[Override]
    public function getCallable(string $id, array $arguments = []): object
    {
        $this->publishUnpublishedProvided($id);

        // @phpstan-ignore-next-line
        return $this->getCallableWithoutChecks($id, $arguments)
            ?? throw new InvalidReferenceException($id);
    }

    /**
     * @inheritDoc
     *
     * @psalm-suppress InvalidReturnType
     * @psalm-suppress InvalidReturnStatement
     * @psalm-suppress ImplementedReturnTypeMismatch
     */
    #[Override]
    public function getService(string $id, array $arguments = []): ServiceContract
    {
        $this->publishUnpublishedProvided($id);

        /** @var class-string<ServiceContract> $id */

        // @phpstan-ignore-next-line
        return $this->getServiceWithoutChecks($id, $arguments)
            ?? throw new InvalidReferenceException($id);
    }

    /**
     * @inheritDoc
     *
     * @psalm-suppress InvalidReturnType
     * @psalm-suppress InvalidReturnStatement
     * @psalm-suppress ImplementedReturnTypeMismatch
     */
    #[Override]
    public function getSingleton(string $id): object
    {
        $this->publishUnpublishedProvided($id);

        // @phpstan-ignore-next-line
        return $this->getSingletonWithoutChecks($id)
            ?? throw new InvalidReferenceException($id);
    }

    /**
     * Get an aliased service from the container without trying to ensuring published.
     *
     * @param class-string            $id        The service id
     * @param array<array-key, mixed> $arguments [optional] The arguments
     */
    protected function getAliasedWithoutChecks(string $id, array $arguments = []): object|null
    {
        $aliased = $this->aliases[$id] ?? null;

        if ($aliased === null) {
            return null;
        }

        return $this->get($aliased, $arguments);
    }

    /**
     * Get a service bound to a callable from the container without trying to get an alias or ensuring published.
     *
     * @param class-string            $id        The service id
     * @param array<array-key, mixed> $arguments [optional] The arguments
     */
    protected function getCallableWithoutChecks(string $id, array $arguments = []): object|null
    {
        $closure = $this->callables[$id] ?? null;

        if ($closure === null) {
            return null;
        }

        return $closure($this, ...$arguments);
    }

    /**
     * Get a singleton from the container without trying to get an alias or ensuring published.
     *
     * @param class-string $id The service id
     */
    protected function getSingletonWithoutChecks(string $id): object|null
    {
        if (isset($this->instances[$id])) {
            return $this->instances[$id];
        }

        if (! isset($this->singletons[$id])) {
            return null;
        }

        $singleton = $this->getServiceWithoutChecks($id);

        return is_object($singleton) ? $this->instances[$id] = $singleton : null;
    }

    /**
     * Get a service from the container without trying to get an alias or ensuring published.
     *
     * @param class-string            $id        The service id
     * @param array<array-key, mixed> $arguments [optional] The arguments
     */
    protected function getServiceWithoutChecks(string $id, array $arguments = []): ServiceContract|null
    {
        if (! is_a($id, ServiceContract::class, true)) {
            return null;
        }

        $service = $this->services[$id] ?? null;

        if ($service === null) {
            return null;
        }

        // Make the object by dispatching the service
        return $service::make($this, $arguments);
    }

    /**
     * Fallback to the mode when a service is not found.
     *
     * @template T of object
     * @template Mode of InvalidReferenceMode
     *
     * @param class-string<T>         $id        The service id
     * @param array<array-key, mixed> $arguments [optional] The arguments
     * @param Mode                    $mode      [optional] The invalid reference mode
     *
     * @return (Mode is InvalidReferenceMode::NEW_INSTANCE_OR_NULL|InvalidReferenceMode::NULL ? T|null : T)
     */
    protected function getFallback(
        string $id,
        array $arguments = [],
        InvalidReferenceMode $mode = InvalidReferenceMode::NEW_INSTANCE_OR_THROW_EXCEPTION
    ): object|null {
        return match ($mode) {
            InvalidReferenceMode::NULL                            => null,
            InvalidReferenceMode::THROW_EXCEPTION                 => throw new InvalidReferenceException($id),
            InvalidReferenceMode::NEW_INSTANCE_OR_NULL,
            InvalidReferenceMode::NEW_INSTANCE_OR_THROW_EXCEPTION => $this->newInstanceOrModeFallback($id, $arguments, $mode),
        };
    }

    /**
     * Fallback to create a new instance or return null/throw exception depending on mode.
     *
     * @template T of object
     * @template Mode of InvalidReferenceMode
     *
     * @param class-string<T>         $id        The service id
     * @param array<array-key, mixed> $arguments [optional] The arguments
     * @param Mode                    $mode      [optional] The invalid reference mode
     *
     * @return (Mode is InvalidReferenceMode::NEW_INSTANCE_OR_NULL|InvalidReferenceMode::NULL ? T|null : T)
     */
    protected function newInstanceOrModeFallback(
        string $id,
        array $arguments = [],
        InvalidReferenceMode $mode = InvalidReferenceMode::NEW_INSTANCE_OR_THROW_EXCEPTION
    ): object|null {
        try {
            if (class_exists($id)) {
                /** @psalm-suppress MixedMethodCall The developer should have passed the proper arguments */
                // Return a new object with the arguments
                return new $id(...$arguments);
            }
        } catch (Throwable) {
        }

        if ($mode === InvalidReferenceMode::NEW_INSTANCE_OR_THROW_EXCEPTION) {
            /** @var class-string $id */
            throw new InvalidReferenceException($id);
        }

        return null;
    }
}
