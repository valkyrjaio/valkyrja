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

namespace Valkyrja\Container\Manager\Contract;

use Override;
use Psr\Container\ContainerInterface;
use Valkyrja\Container\Contract\ServiceContract;
use Valkyrja\Container\Data\Data;
use Valkyrja\Container\Enum\InvalidReferenceMode;

interface ContainerContract extends ContainerInterface, ProvidersAwareContract
{
    /**
     * Get a data representation of the container.
     */
    public function getData(): Data;

    /**
     * Set data from a data object.
     */
    public function setFromData(Data $data): void;

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
     * @param class-string                  $id      The service id
     * @param class-string<ServiceContract> $service The service
     */
    public function bind(string $id, string $service): static;

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
     * @param class-string                  $id        The service id
     * @param class-string<ServiceContract> $singleton The singleton service
     */
    public function bindSingleton(string $id, string $singleton): static;

    /**
     * Set a callable in the container.
     *
     * @template T of object
     *
     * @param class-string<T> $id       The service id
     * @param callable        $callable The callable
     *
     * @see https://psalm.dev/r/4431cf022b callable(Container, mixed...):T
     */
    public function setCallable(string $id, callable $callable): static;

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
     * Check whether a given service is bound to a callable.
     *
     * @param class-string $id The service id
     */
    public function isCallable(string $id): bool;

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
     * Get a service from the container.
     *
     * @template T of object
     * @template Mode of InvalidReferenceMode
     *
     * @param class-string<T>         $id        The service id
     * @param array<array-key, mixed> $arguments [optional] The arguments
     * @param Mode                    $mode      [optional] The invalid reference mode
     *
     * @return (Mode is InvalidReferenceMode::NEW_INSTANCE_OR_NULL|InvalidReferenceMode::NULL ? T|null : T)
     *
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    #[Override]
    public function get(string $id, array $arguments = [], InvalidReferenceMode $mode = InvalidReferenceMode::NEW_INSTANCE_OR_THROW_EXCEPTION): object|null;

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
     * Get a service bound to a callable from the container.
     *
     * @template T of object
     *
     * @param class-string<T>         $id        The service id
     * @param array<array-key, mixed> $arguments [optional] The arguments
     *
     * @return T
     */
    public function getCallable(string $id, array $arguments = []): object;

    /**
     * Get a service from the container.
     *
     * @template T of ServiceContract
     *
     * @param class-string<T>         $id        The service id
     * @param array<array-key, mixed> $arguments [optional] The arguments
     *
     * @return T
     */
    public function getService(string $id, array $arguments = []): ServiceContract;

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
