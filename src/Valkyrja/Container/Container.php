<?php

declare(strict_types = 1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Container;

use Valkyrja\Support\Cacheable;
use Valkyrja\Support\Providers\ProvidersAware;

/**
 * Interface Container.
 *
 * @author Melech Mizrachi
 */
interface Container extends Cacheable, ProvidersAware
{
    /**
     * Set an alias to the container.
     *
     * @param string $alias     The alias
     * @param string $serviceId The service to return
     *
     * @return void
     */
    public function alias(string $alias, string $serviceId): void;

    /**
     * Bind a service to the container.
     *
     * @param Service $service The service model
     * @param bool    $verify  [optional] Whether to verify the service
     *
     * @return void
     */
    public function bind(Service $service, bool $verify = true): void;

    /**
     * Bind a context to the container.
     *
     * @param ServiceContext $serviceContext The context service
     *
     * @return void
     */
    public function context(ServiceContext $serviceContext): void;

    /**
     * Bind a singleton to the container.
     *
     * @param string $serviceId The service
     * @param mixed  $singleton The singleton
     */
    public function singleton(string $serviceId, $singleton): void;

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
     * @param string $serviceId The service
     * @param string $context   The context class name || function name || variable name
     * @param string $member    [optional] The context member method name || property name
     *
     * @return bool
     */
    public function hasContext(string $serviceId, string $context, string $member = null): bool;

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
     * Check whether a given service is provided by a deferred service provider.
     *
     * @param string $serviceId The service
     *
     * @return bool
     */
    public function isProvided(string $serviceId): bool;

    /**
     * Get a service from the container.
     *
     * @param string $serviceId The service
     * @param array  $arguments [optional] The arguments
     * @param string $context   [optional] The context class name || function name || variable name
     * @param string $member    [optional] The context member method name || property name
     *
     * @return mixed
     */
    public function get(string $serviceId, array $arguments = null, string $context = null, string $member = null);

    /**
     * Make a service.
     *
     * @param string $serviceId The service id
     * @param array  $arguments [optional] The arguments
     *
     * @return mixed
     */
    public function make(string $serviceId, array $arguments = null);

    /**
     * Get a singleton from the container.
     *
     * @param string $serviceId The service
     *
     * @return mixed
     */
    public function getSingleton(string $serviceId);

    /**
     * Get a provided service from the container.
     *
     * @param string $serviceId The service
     * @param array  $arguments [optional] The arguments
     * @param string $context   [optional] The context class name || function name || variable name
     * @param string $member    [optional] The context member method name || property name
     *
     * @return mixed
     */
    public function getProvided(
        string $serviceId,
        array $arguments = null,
        string $context = null,
        string $member = null
    );

    /**
     * Get the context service id.
     *
     * @param string $serviceId The service
     * @param string $context   The context class name || function name || variable name
     * @param string $member    [optional] The context member method name || property name
     *
     * @return string
     */
    public function contextServiceId(string $serviceId, string $context, string $member = null): string;
}
