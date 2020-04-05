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
use Valkyrja\Support\ProvidersAware;

/**
 * Interface Container.
 *
 * @author Melech Mizrachi
 */
interface Container extends ArrayAccess, ProvidersAware
{
    /**
     * Get a container with context.
     *
     * @param string $context The context class or function name
     * @param string $member  [optional] The context method name
     *
     * @return static
     */
    public function withContext(string $context, string $member = null): self;

    /**
     * Check whether a given service exists.
     *
     * @param string $serviceId The service
     *
     * @return bool
     */
    public function has(string $serviceId): bool;

    /**
     * Check whether a given service has context.
     *
     * @param string      $serviceId The service id
     * @param string      $context   The context class or function name
     * @param string|null $member    [optional] The context member name
     *
     * @return bool
     */
    public function hasContext(string $serviceId, string $context, string $member = null): bool;

    /**
     * Bind a service to the container.
     *
     * @param string $serviceId The service id
     * @param string $service   The service
     *
     * @return void
     */
    public function bind(string $serviceId, string $service): void;

    /**
     * Bind a singleton to the container.
     *
     * @param string $serviceId The service id
     * @param string $singleton The singleton service
     *
     * @return void
     */
    public function bindSingleton(string $serviceId, string $singleton): void;

    /**
     * Set an alias to the container.
     *
     * @param string $alias     The alias
     * @param string $serviceId The service to return
     *
     * @return void
     */
    public function setAlias(string $alias, string $serviceId): void;

    /**
     * Bind a context to the container.
     *
     * @param string      $serviceId The service id
     * @param string      $context   The context class or function name
     * @param string|null $member    [optional] The context member name
     *
     * @return void
     */
    public function setContext(string $serviceId, string $context, string $member = null): void;

    /**
     * Bind a singleton to the container.
     *
     * @param string $serviceId The service
     * @param mixed  $singleton The singleton
     */
    public function setSingleton(string $serviceId, $singleton): void;

    /**
     * Check whether a given service is an alias.
     *
     * @param string $serviceId The service
     *
     * @return bool
     */
    public function isAlias(string $serviceId): bool;

    /**
     * Check whether a given service is a singleton.
     *
     * @param string $serviceId The service
     *
     * @return bool
     */
    public function isSingleton(string $serviceId): bool;

    /**
     * Get a service from the container.
     *
     * @param string $serviceId The service
     * @param array  $arguments [optional] The arguments
     *
     * @return mixed
     */
    public function get(string $serviceId, array $arguments = []);

    /**
     * Make a service.
     *
     * @param string $serviceId The service id
     * @param array  $arguments [optional] The arguments
     *
     * @return mixed
     */
    public function makeService(string $serviceId, array $arguments = []);

    /**
     * Get a singleton from the container.
     *
     * @param string $serviceId The service
     *
     * @return mixed
     */
    public function getSingleton(string $serviceId);

    /**
     * Get the context service id.
     *
     * @param string      $serviceId The service id
     * @param string      $context   The context class or function name
     * @param string|null $member    [optional] The context member name
     *
     * @return string
     */
    public function getContextServiceId(string $serviceId, string $context, string $member = null): string;
}
