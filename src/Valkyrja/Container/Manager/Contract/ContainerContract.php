<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Container\Manager\Contract;

use Override;
use Psr\Container\ContainerInterface;
use Valkyrja\Container\Data\ContainerData;

interface ContainerContract extends ContainerInterface, ProvidersAwareContract
{
    /**
     * Get a data representation of the container.
     */
    public function getData(): ContainerData;

    /**
     * Set data from a data object.
     */
    public function setFromData(ContainerData $data): void;

    /**
     * Check whether a given service exists.
     *
     * @param class-string $id The service id
     *
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    #[Override]
    public function has(string $id): bool;

    /**
     * Bind a service to the container.
     *
     * @template T of object
     *
     * @param class-string<T>                           $id       The service id
     * @param callable(self, array<array-key, mixed>):T $callable The callable
     */
    public function bind(string $id, callable $callable): static;

    /**
     * Bind an alias to the container.
     *
     * @param class-string $alias The alias
     * @param class-string $id    The service id to alias
     */
    public function bindAlias(string $alias, string $id): static;

    /**
     * Bind a singleton to the container.
     *
     * @template T of object
     *
     * @param class-string<T>                           $id       The service id
     * @param callable(self, array<array-key, mixed>):T $callable The callable
     */
    public function bindSingleton(string $id, callable $callable): static;

    /**
     * Set a singleton in the container.
     *
     * @template T of object
     *
     * @param class-string<T> $id        The service id
     * @param T               $singleton The singleton
     */
    public function setSingleton(string $id, object $singleton): static;

    /**
     * Check whether a given service is an alias.
     *
     * @param class-string $id The service id
     */
    public function isAlias(string $id): bool;

    /**
     * Check whether a given service exists.
     *
     * @param class-string $id The service id
     */
    public function isService(string $id): bool;

    /**
     * Check whether a given service is a singleton.
     *
     * @param class-string $id The service id
     */
    public function isSingleton(string $id): bool;

    /**
     * Check whether a given singleton has a class binding (but may not yet be resolved).
     *
     * @param class-string $id The service id
     */
    public function isSingletonBinding(string $id): bool;

    /**
     * Check whether a given singleton has already been resolved and cached as an instance.
     *
     * @param class-string $id The service id
     */
    public function isSingletonInstance(string $id): bool;

    /**
     * Get a service from the container.
     *
     * @template T of object
     *
     * @param class-string<T>         $id        The service id
     * @param array<array-key, mixed> $arguments [optional] The arguments
     *
     * @return T
     *
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    #[Override]
    public function get(string $id, array $arguments = []): object;

    /**
     * Get the id that an alias points to.
     *
     * @param class-string $alias The alias
     *
     * @return class-string|null
     */
    public function getAliasedId(string $alias): string|null;

    /**
     * Get an aliased service from the container.
     *
     * @template T of object
     *
     * @param class-string<T>         $id        The service id
     * @param array<array-key, mixed> $arguments [optional] The arguments
     *
     * @return T
     */
    public function getAliased(string $id, array $arguments = []): object;

    /**
     * Get a service from the container.
     *
     * @template T of object
     *
     * @param class-string<T>         $id        The service id
     * @param array<array-key, mixed> $arguments [optional] The arguments
     *
     * @return T
     */
    public function getService(string $id, array $arguments = []): object;

    /**
     * Get a singleton from the container.
     *
     * @template T of object
     *
     * @param class-string<T> $id The service id
     *
     * @return T
     */
    public function getSingleton(string $id): object;
}
