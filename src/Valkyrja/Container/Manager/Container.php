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
use Valkyrja\Container\Data\ContainerData;
use Valkyrja\Container\Enum\InvalidReferenceMode;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Manager\Trait\ProvidersAware;
use Valkyrja\Container\Throwable\Exception\ContainerInvalidReferenceException;

use function array_merge;
use function is_object;

class Container implements ContainerContract
{
    use ProvidersAware;

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
     * @var array<class-string, callable(ContainerContract, array<array-key, mixed>):object>
     */
    protected array $services = [];

    /**
     * The singletons.
     *
     * @var array<class-string, class-string>
     */
    protected array $singletons = [];

    public function __construct(
        protected ContainerData $data = new ContainerData()
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
    public function getData(): ContainerData
    {
        return new ContainerData(
            aliases: $this->aliases,
            deferred: $this->deferred,
            deferredCallback: $this->deferredCallback,
            services: $this->services,
            singletons: $this->singletons,
            providers: $this->providers,
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function setFromData(ContainerData $data): void
    {
        $this->aliases          = array_merge($this->aliases, $data->aliases);
        $this->deferred         = array_merge($this->deferred, $data->deferred);
        $this->deferredCallback = array_merge($this->deferredCallback, $data->deferredCallback);
        $this->services         = array_merge($this->services, $data->services);
        $this->singletons       = array_merge($this->singletons, $data->singletons);
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
            || $this->isAlias($id);
    }

    /**
     * @inheritDoc
     *
     * @param class-string $id The service id
     */
    #[Override]
    public function bind(string $id, callable $callable): static
    {
        /** @var callable(ContainerContract, mixed...):object $callable */
        $this->services[$id]  = $callable;
        $this->published[$id] = true;

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
     * @param class-string $id The service id
     */
    #[Override]
    public function bindSingleton(string $id, callable $callable): static
    {
        $this->singletons[$id] = $id;

        /** @var callable(ContainerContract, mixed...):object $callable */
        $this->bind($id, $callable);

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
        return $this->isSingletonBinding($id)
            || $this->isSingletonInstance($id);
    }

    /**
     * @inheritDoc
     *
     * @param class-string $id The service id
     */
    #[Override]
    public function isSingletonBinding(string $id): bool
    {
        return isset($this->singletons[$id]);
    }

    /**
     * @inheritDoc
     *
     * @param class-string $id The service id
     */
    #[Override]
    public function isSingletonInstance(string $id): bool
    {
        return isset($this->instances[$id]);
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
    public function get(string $id, array $arguments = [], InvalidReferenceMode $mode = InvalidReferenceMode::NEW_INSTANCE_OR_THROW_EXCEPTION): object
    {
        $this->publishUnpublishedProvided($id);

        // @phpstan-ignore-next-line
        return $this->getSingletonWithoutChecks($id)
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
            ?? throw new ContainerInvalidReferenceException($id);
    }

    /**
     * @inheritDoc
     *
     * @psalm-suppress InvalidReturnType
     * @psalm-suppress InvalidReturnStatement
     * @psalm-suppress ImplementedReturnTypeMismatch
     */
    #[Override]
    public function getService(string $id, array $arguments = []): object
    {
        $this->publishUnpublishedProvided($id);

        // @phpstan-ignore-next-line
        return $this->getServiceWithoutChecks($id, $arguments)
            ?? throw new ContainerInvalidReferenceException($id);
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
            ?? throw new ContainerInvalidReferenceException($id);
    }

    /**
     * Get an aliased service from the container without trying to ensuring published.
     *
     * @param class-string            $id        The service id
     * @param array<array-key, mixed> $arguments [optional] The arguments
     */
    protected function getAliasedWithoutChecks(string $id, array $arguments = []): object|null
    {
        $aliased = $this->getAlias($id);

        if ($aliased === null) {
            return null;
        }

        return $this->get($aliased, $arguments);
    }

    /**
     * Get a singleton from the container without trying to get an alias or ensuring published.
     *
     * @param class-string $id The service id
     */
    protected function getSingletonWithoutChecks(string $id): object|null
    {
        $instance = $this->getSingletonInstance($id);

        if ($instance !== null) {
            return $instance;
        }

        if (! $this->isSingletonBinding($id)) {
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
    protected function getServiceWithoutChecks(string $id, array $arguments = []): object|null
    {
        $service = $this->getServiceCallable($id);

        if ($service === null) {
            return null;
        }

        // Make the object by dispatching the service
        return $service($this, $arguments);
    }

    /**
     * Get the alias target for a given id.
     *
     * @param class-string $id The service id
     *
     * @return class-string|null
     */
    protected function getAlias(string $id): string|null
    {
        return $this->aliases[$id]
            ?? null;
    }

    /**
     * Get a cached singleton instance for a given id.
     *
     * @param class-string $id The service id
     */
    protected function getSingletonInstance(string $id): object|null
    {
        return $this->instances[$id]
            ?? null;
    }

    /**
     * Get the service class binding for a given id.
     *
     * @param class-string $id The service id
     *
     * @return callable(ContainerContract, array<array-key, mixed>):object|null
     */
    protected function getServiceCallable(string $id): callable|null
    {
        return $this->services[$id]
            ?? null;
    }

    /**
     * Fallback to the mode when a service is not found.
     *
     * @template T of object
     *
     * @param class-string<T>         $id        The service id
     * @param array<array-key, mixed> $arguments [optional] The arguments
     *
     * @return T
     */
    protected function getFallback(
        string $id,
        array $arguments = [],
        InvalidReferenceMode $mode = InvalidReferenceMode::NEW_INSTANCE_OR_THROW_EXCEPTION
    ): object {
        return match ($mode) {
            InvalidReferenceMode::THROW_EXCEPTION                 => throw new ContainerInvalidReferenceException($id),
            InvalidReferenceMode::NEW_INSTANCE_OR_THROW_EXCEPTION => $this->newInstanceOrModeFallback($id, $arguments),
        };
    }

    /**
     * Fallback to create a new instance or return null/throw exception depending on mode.
     *
     * @template T of object
     *
     * @param class-string<T>         $id        The service id
     * @param array<array-key, mixed> $arguments [optional] The arguments
     *
     * @return T
     */
    protected function newInstanceOrModeFallback(string $id, array $arguments = []): object
    {
        try {
            if (class_exists($id)) {
                /** @psalm-suppress MixedMethodCall The developer should have passed the proper arguments */
                // Return a new object with the arguments
                return new $id(...$arguments);
            }
        } catch (Throwable) {
            // Fall through to the exception being thrown by default
        }

        /** @var class-string $id */
        throw new ContainerInvalidReferenceException($id);
    }
}
