<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Contracts\Container;

use Closure;

/**
 * Interface Container
 *
 * @package Valkyrja\Contracts\Container
 *
 * @author  Melech Mizrachi
 */
interface Container
{
    /**
     * Set the service container for dependency injection.
     *
     * @param array $serviceContainer The service container array to set
     *
     * @return void
     */
    public function setServiceContainer(array $serviceContainer) : void;

    /**
     * Set an abstract in the service container.
     *
     * @param string   $abstract  The abstract to use as the key
     * @param \Closure $closure   The instance to set
     * @param bool     $singleton Whether this abstract should be treated as a singleton
     *
     * @return void
     */
    public function bind(string $abstract, Closure $closure, bool $singleton = false) : void;

    /**
     * Set an abstract as a singleton in the service container.
     *
     * @param string   $abstract The abstract to use as the key
     * @param \Closure $closure  The instance to set
     *
     * @return void
     */
    public function singleton(string $abstract, Closure $closure) : void;

    /**
     * Set an object in the service container.
     *
     * @param string $abstract The abstract to use as the key
     * @param object $instance The instance to set
     *
     * @return void
     */
    public function instance(string $abstract, $instance) : void;

    /**
     * Set an alias in the service container.
     *
     * @param string $abstract  The abstract to use as the key
     * @param string $alias     The instance to set
     * @param bool   $singleton Whether this abstract should be treated as a singleton
     *
     * @return void
     */
    public function alias(string $abstract, string $alias, bool $singleton = false) : void;

    /**
     * Get an abstract from the container.
     *
     * @param string $abstract  The abstract to get
     * @param array  $arguments [optional] Arguments to pass
     *
     * @return object
     */
    public function get(string $abstract, array $arguments = []); // : object;

    /**
     * Check whether an abstract is set in the container.
     *
     * @param string $abstract The abstract to check for
     *
     * @return bool
     */
    public function bound(string $abstract) : bool;

    /**
     * Bootstrap the container.
     *
     * @return void
     */
    public function bootstrap() : void;
}
