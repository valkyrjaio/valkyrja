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

use Exception;

use Valkyrja\Contracts\Config\Config;
use Valkyrja\Contracts\Config\Env;
use Valkyrja\Contracts\Container\Container as ContainerContract;
use Valkyrja\Contracts\Exceptions\HttpException as HttpExceptionContract;
use Valkyrja\Contracts\Http\JsonResponse as JsonResponseContract;
use Valkyrja\Contracts\Http\Request as RequestContract;
use Valkyrja\Contracts\Http\Response as ResponseContract;
use Valkyrja\Contracts\Http\ResponseBuilder as ResponseBuilderContract;
use Valkyrja\Contracts\Http\Router as RouterContract;
use Valkyrja\Contracts\Sessions\Session as SessionContract;
use Valkyrja\Contracts\View\View as ViewContract;
use Valkyrja\Exceptions\HttpException;
use Valkyrja\Http\JsonResponse;
use Valkyrja\Http\Request;
use Valkyrja\Http\Response;
use Valkyrja\Http\ResponseBuilder;
use Valkyrja\Http\Router;
use Valkyrja\Sessions\Session;
use Valkyrja\View\View;

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
     * Set an abstract in the service container.
     *
     * @param string                 $abstract  The abstract to use as the key
     * @param \Closure|string|object $instance  The instance to set
     * @param bool                   $singleton Whether this abstract should be treated as a singleton
     *
     * @return void
     */
    public function instance(string $abstract, $instance, $singleton = false) // : void
    {
        $this->serviceContainer[$abstract] = [
            $instance,
            $singleton,
        ];
    }

    /**
     * Set an abstract as a singleton in the service container.
     *
     * @param string                 $abstract The abstract to use as the key
     * @param \Closure|string|object $instance The instance to set
     *
     * @return void
     */
    public function singleton(string $abstract, $instance) // : void
    {
        $this->instance($abstract, $instance, true);
    }

    /**
     * Get an abstract from the container.
     *
     * @param string $abstract  The abstract to get
     * @param array  $arguments [optional] Arguments to pass
     *
     * @return object
     */
    public function get(string $abstract, array $arguments = []) // : object
    {
        // If the abstract is set in the service container
        if (isset($this->serviceContainer[$abstract])) {
            // Set the container item for ease of use here
            $containerItem = $this->serviceContainer[$abstract][0];

            // Check if this container item is a callable function
            if (is_callable($containerItem)) {
                // Run the callable function
                $containerItem = call_user_func_array($containerItem, $arguments);
            }

            // If the container item is a string
            if (is_string($containerItem)) {
                // Set the container item as a new instance
                $containerItem = new $containerItem;
            }

            // If this is a singleton
            if ($this->serviceContainer[$abstract][1] === true) {
                // Set the result in the service container for the next request
                $this->serviceContainer[$abstract][0] = $containerItem;
            }

            // Return the container item
            return $containerItem;
        }

        return new $abstract;
    }

    /**
     * Check whether an abstract is set in the container.
     *
     * @param string $abstract The abstract to check for
     *
     * @return bool
     */
    public function isset(string $abstract) : bool
    {
        return isset($this->serviceContainer[$abstract]);
    }

    /**
     * Bootstrap the container.
     *
     * @return void
     */
    public function bootstrap() // : void
    {
        // Check if the http exception has already been set in the container
        if (! $this->isset(HttpExceptionContract::class)) {
            $this->instance(
                HttpExceptionContract::class,
                function (
                    int $statusCode,
                    string $message = null,
                    Exception $previous = null,
                    array $headers = [],
                    int $code = 0
                ) {
                    return new HttpException($statusCode, $message, $previous, $headers, $code);
                }
            );
        }

        // Check if the request has already been set in the container
        if (! $this->isset(RequestContract::class)) {
            $this->instance(
                RequestContract::class,
                function () {
                    return new Request();
                }
            );
        }

        // Check if the response has already been set in the container
        if (! $this->isset(ResponseContract::class)) {
            $this->instance(
                ResponseContract::class,
                function (string $content = '', int $status = 200, array $headers = []) {
                    return new Response($content, $status, $headers);
                }
            );
        }

        // Check if the json response has already been set in the container
        if (! $this->isset(JsonResponseContract::class)) {
            $this->instance(
                JsonResponseContract::class,
                function (string $content = '', int $status = 200, array $headers = []) {
                    return new JsonResponse($content, $status, $headers);
                }
            );
        }

        // Check if the response builder has already been set in the container
        if (! $this->isset(ResponseBuilderContract::class)) {
            $this->instance(
                ResponseBuilderContract::class,
                function () {
                    $response = $this->get(ResponseContract::class);
                    $view = $this->get(ViewContract::class);

                    return new ResponseBuilder($response, $view);
                },
                true
            );
        }

        // Check if the router has already been set in the container
        if (! $this->isset(RouterContract::class)) {
            $this->instance(
                RouterContract::class,
                function () {
                    return new Router();
                },
                true
            );
        }

        // Check if the session has already been set in the container
        if (! $this->isset(SessionContract::class)) {
            $this->instance(
                SessionContract::class,
                function () {
                    return new Session();
                },
                true
            );
        }

        // Check if the View has already been set in the container
        if (! $this->isset(ViewContract::class)) {
            $this->instance(
                ViewContract::class,
                function (string $template = '', array $variables = []) {
                    return new View($template, $variables);
                }
            );
        }
    }
}
