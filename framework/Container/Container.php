<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Container;

use Valkyrja\Contracts\Container\Container as ContainerContract;

/**
 * Class Container
 *
 * @package Valkyrja\Container
 *
 * @author  Melech Mizrachi
 */
class Container implements ContainerContract
{
    /**
     * Service container for dependency injection.
     *
     * @var array
     */
    protected $serviceContainer = [];

    /**
     * Return the global $container variable.
     *
     * @return \Valkyrja\Contracts\Container\Container
     */
    public static function container() : ContainerContract
    {
        global $container;

        return $container;
    }

    /**
     * Set the service container for dependency injection.
     *
     * @param array $serviceContainer The service container array to set
     *
     * @return void
     */
    public function setServiceContainer(array $serviceContainer) // : void
    {
        // The application has already bootstrapped the container so merge to avoid clearing
        $this->serviceContainer = array_merge($this->serviceContainer, $serviceContainer);
    }

    /**
     * Set the service container for dependency injection.
     *
     * @param string               $abstract The abstract to use as the key
     * @param \Closure|array|mixed $instance The instance to set
     *
     * @return void
     */
    public function instance(string $abstract, $instance) // : void
    {
        $this->serviceContainer[$abstract] = $instance;
    }

    /**
     * Get an abstract from the container.
     *
     * @param string $abstract  The abstract to get
     * @param array  $arguments [optional] Arguments to pass
     *
     * @return mixed
     */
    public function get(string $abstract, array $arguments = []) // : mixed
    {
        // If the abstract is set in the service container
        if (isset($this->serviceContainer[$abstract])) {
            // Set the container item for ease of use here
            $containerItem = $this->serviceContainer[$abstract];

            // The container item is a singleton and hasn't been requested yet
            if (is_callable($containerItem)) {
                // Run the callable function
                $containerItem = $containerItem();

                // Set the result in the service container for the next request
                $this->serviceContainer[$abstract] = $containerItem;

                // Return the container item
                return $containerItem;
            }
            // Otherwise we're looking to get a new instance every time
            elseif (is_array($containerItem) && is_callable($containerItem[0])) {
                // Return the first item in the array
                return call_user_func_array($containerItem[0], $arguments);
            }

            // Return the container item
            return $containerItem;
        }

        // A class was passed just in case it was in the container, so return it
        return $abstract;
    }
}
