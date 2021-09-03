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

namespace Valkyrja\Container;

use ArrayAccess;
use Closure;
use Valkyrja\Support\Provider\ProvidersAware;

/**
 * Interface Container.
 *
 * @author   Melech Mizrachi
 * @template T
 */
interface Container extends ArrayAccess, ProvidersAware
{
    /**
     * Get a container instance with context.
     *
     * @param string      $context The context class or function name
     * @param string|null $member  [optional] The context method name
     *
     * @return static
     */
    public function withContext(string $context, string $member = null): self;

    /**
     * Get a container instance with no context.
     *
     * @return static
     */
    public function withoutContext(): self;

    /**
     * Check whether a given service exists.
     *
     * @param string $serviceId The service id
     *
     * @return bool
     */
    public function has(string $serviceId): bool;

    /**
     * Bind a service to the container.
     *
     * @param string $serviceId The service id
     * @param string $service   The service
     *
     * @return static
     */
    public function bind(string $serviceId, string $service): self;

    /**
     * Bind a service to a closure in the container.
     *
     * @param string  $serviceId The service id
     * @param Closure $closure   The closure
     *
     * @return static
     */
    public function bindClosure(string $serviceId, Closure $closure): self;

    /**
     * Bind a singleton to the container.
     *
     * @param string $serviceId The service id
     * @param string $singleton The singleton service
     *
     * @return static
     */
    public function bindSingleton(string $serviceId, string $singleton): self;

    /**
     * Set an alias in the container.
     *
     * @param string $alias     The alias
     * @param string $serviceId The service id to alias
     *
     * @return static
     */
    public function setAlias(string $alias, string $serviceId): self;

    /**
     * Set a closure in the container.
     *
     * @param string  $serviceId The service id
     * @param Closure $closure   The closure
     *
     * @return static
     */
    public function setClosure(string $serviceId, Closure $closure): self;

    /**
     * Set a singleton in the container.
     *
     * @param string $serviceId The service id
     * @param mixed  $singleton The singleton
     *
     * @return static
     */
    public function setSingleton(string $serviceId, $singleton): self;

    /**
     * Check whether a given service is an alias.
     *
     * @param string $serviceId The service id
     *
     * @return bool
     */
    public function isAlias(string $serviceId): bool;

    /**
     * Check whether a given service is bound to a closure.
     *
     * @param string $serviceId The service id
     *
     * @return bool
     */
    public function isClosure(string $serviceId): bool;

    /**
     * Check whether a given service is a singleton.
     *
     * @param string $serviceId The service id
     *
     * @return bool
     */
    public function isSingleton(string $serviceId): bool;

    /**
     * Check whether a given service exists.
     *
     * @param string $serviceId The service id
     *
     * @return bool
     */
    public function isService(string $serviceId): bool;

    /**
     * Get a service from the container.
     *
     * @param class-string<T> $serviceId The service id
     * @param array           $arguments [optional] The arguments
     *
     * @return mixed|T
     */
    public function get(string $serviceId, array $arguments = []);

    /**
     * Get a service bound to a closure from the container.
     *
     * @param string $serviceId The service id
     * @param array  $arguments [optional] The arguments
     *
     * @return mixed
     */
    public function getClosure(string $serviceId, array $arguments = []);

    /**
     * Get a singleton from the container.
     *
     * @param class-string<T> $serviceId The service id
     *
     * @return mixed|T
     */
    public function getSingleton(string $serviceId);

    /**
     * Make a service.
     *
     * @param class-string<T> $serviceId The service id
     * @param array           $arguments [optional] The arguments
     *
     * @return mixed|T
     */
    public function makeService(string $serviceId, array $arguments = []);

    /**
     * Get a service id with optional context.
     *
     * @param string      $serviceId The service id
     * @param string|null $context   [optional] The context class or function name
     * @param string|null $member    [optional] The context member name
     *
     * @return string
     */
    public function getServiceId(string $serviceId, string $context = null, string $member = null): string;
}
