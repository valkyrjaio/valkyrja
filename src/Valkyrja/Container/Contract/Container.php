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

namespace Valkyrja\Container\Contract;

use ArrayAccess;
use Psr\Container\ContainerInterface;
use Valkyrja\Support\Provider\Contract\ProvidersAware;

/**
 * Interface Container.
 *
 * @author Melech Mizrachi
 *
 * @extends ArrayAccess<class-string|string, mixed>
 */
interface Container extends ArrayAccess, ContainerInterface, ProvidersAware
{
    /**
     * Check whether a given service exists.
     *
     * @param class-string|string $id The service id
     *
     * @return bool
     */
    public function has(string $id): bool;

    /**
     * Bind a service to the container.
     *
     * @param class-string|string   $id      The service id
     * @param class-string<Service> $service The service
     *
     * @return static
     */
    public function bind(string $id, string $service): static;

    /**
     * Bind an alias to the container.
     *
     * @param string              $alias The alias
     * @param class-string|string $id    The service id to alias
     *
     * @return static
     */
    public function bindAlias(string $alias, string $id): static;

    /**
     * Bind a singleton to the container.
     *
     * @param class-string|string   $id        The service id
     * @param class-string<Service> $singleton The singleton service
     *
     * @return static
     */
    public function bindSingleton(string $id, string $singleton): static;

    /**
     * Set a callable in the container.
     *
     * @param class-string|string $id       The service id
     * @param callable            $callable The callable
     *
     * @return static
     */
    public function setCallable(string $id, callable $callable): static;

    /**
     * Set a singleton in the container.
     *
     * @param class-string|string $id        The service id
     * @param mixed               $singleton The singleton
     *
     * @return static
     */
    public function setSingleton(string $id, mixed $singleton): static;

    /**
     * Check whether a given service is an alias.
     *
     * @param class-string|string $id The service id
     *
     * @return bool
     */
    public function isAlias(string $id): bool;

    /**
     * Check whether a given service is bound to a callable.
     *
     * @param class-string|string $id The service id
     *
     * @return bool
     */
    public function isCallable(string $id): bool;

    /**
     * Check whether a given service exists.
     *
     * @param class-string|string $id The service id
     *
     * @return bool
     */
    public function isService(string $id): bool;

    /**
     * Check whether a given service is a singleton.
     *
     * @param class-string|string $id The service id
     *
     * @return bool
     */
    public function isSingleton(string $id): bool;

    /**
     * Get a service from the container.
     *
     * @template T of object
     *
     * @param class-string<T>|string  $id        The service id
     * @param array<array-key, mixed> $arguments [optional] The arguments
     *
     * @return ($id is class-string<T> ? T : mixed)
     */
    public function get(string $id, array $arguments = []): mixed;

    /**
     * Get a service bound to a callable from the container.
     *
     * @template T of object
     *
     * @param class-string<T>|string  $id        The service id
     * @param array<array-key, mixed> $arguments [optional] The arguments
     *
     * @return ($id is class-string<T> ? T : mixed)
     */
    public function getCallable(string $id, array $arguments = []): mixed;

    /**
     * Get a service from the container.
     *
     * @template T of Service
     *
     * @param class-string<T>|string  $id        The service id
     * @param array<array-key, mixed> $arguments [optional] The arguments
     *
     * @return ($id is class-string<T> ? T : Service)
     */
    public function getService(string $id, array $arguments = []): Service;

    /**
     * Get a singleton from the container.
     *
     * @template T of object
     *
     * @param class-string<T>|string $id The service id
     *
     * @return ($id is class-string<T> ? T : mixed)
     */
    public function getSingleton(string $id): mixed;

    /**
     * Get a service from the container.
     *
     * @param string $offset The service id
     *
     * @return mixed
     */
    public function offsetGet($offset): mixed;

    /**
     * Bind a service to the container.
     *
     * @param class-string|string   $offset The service id
     * @param class-string<Service> $value  The service
     *
     * @return void
     */
    public function offsetSet($offset, $value): void;

    /**
     * Unbind a service to the container.
     *
     * @param class-string|string $offset The service id
     *
     * @return void
     */
    public function offsetUnset($offset): void;

    /**
     * Check whether a given service exists.
     *
     * @param class-string|string $offset The service id
     *
     * @return bool
     */
    public function offsetExists($offset): bool;
}
