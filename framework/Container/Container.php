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

use Closure;
use Exception;

use Valkyrja\Config\Config;
use Valkyrja\Config\Env;
use Valkyrja\Contracts\Application;
use Valkyrja\Contracts\Config\Config as ConfigContract;
use Valkyrja\Contracts\Config\Env as EnvContract;
use Valkyrja\Contracts\Container\Container as ContainerContract;
use Valkyrja\Contracts\Exceptions\HttpException as HttpExceptionContract;
use Valkyrja\Contracts\Http\Client as ClientContract;
use Valkyrja\Contracts\Http\JsonResponse as JsonResponseContract;
use Valkyrja\Contracts\Http\Request as RequestContract;
use Valkyrja\Contracts\Http\Response as ResponseContract;
use Valkyrja\Contracts\Http\ResponseBuilder as ResponseBuilderContract;
use Valkyrja\Contracts\Http\Router as RouterContract;
use Valkyrja\Contracts\Sessions\Session as SessionContract;
use Valkyrja\Contracts\View\View as ViewContract;
use Valkyrja\Exceptions\HttpException;
use Valkyrja\Http\Client;
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
     * @param string   $abstract  The abstract to use as the key
     * @param \Closure $closure   The instance to set
     * @param bool     $singleton Whether this abstract should be treated as a singleton
     *
     * @return void
     */
    public function bind(string $abstract, Closure $closure, bool $singleton = false) // : void
    {
        $this->set($abstract, $closure, $singleton);
    }

    /**
     * Set an abstract as a singleton in the service container.
     *
     * @param string   $abstract The abstract to use as the key
     * @param \Closure $closure  The instance to set
     *
     * @return void
     */
    public function singleton(string $abstract, Closure $closure) // : void
    {
        $this->bind($abstract, $closure, true);
    }

    /**
     * Set an object in the service container.
     *
     * @param string $abstract The abstract to use as the key
     * @param object $instance The instance to set
     *
     * @return void
     */
    public function instance(string $abstract, $instance) // : void
    {
        $this->set($abstract, $instance, true);
    }

    /**
     * Set an alias in the service container.
     *
     * @param string $abstract  The abstract to use as the key
     * @param string $alias     The instance to set
     * @param bool   $singleton Whether this abstract should be treated as a singleton
     *
     * @return void
     */
    public function alias(string $abstract, string $alias, bool $singleton = false) // : void
    {
        $this->set($abstract, $alias, $singleton);
    }

    /**
     * Set an abstract in the service container.
     *
     * @param string                 $abstract  The abstract to use as the key
     * @param \Closure|string|object $closure   The instance to set
     * @param bool                   $singleton Whether this abstract should be treated as a singleton
     *
     * @return void
     */
    protected function set(string $abstract, $closure, bool $singleton = false) // : void
    {
        $this->serviceContainer[$abstract] = [
            $closure,
            $singleton,
        ];
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
                $containerItem = $containerItem(...$arguments);

                // If this is a singleton
                if ($this->serviceContainer[$abstract][1] === true) {
                    // Set the result in the service container for the next request
                    $this->serviceContainer[$abstract][0] = $containerItem;
                }
            }
            // If the container item is a string
            else if (is_string($containerItem)) {
                // Set the container item as a new instance
                $containerItem = new $containerItem(...$arguments);

                // If this is a singleton
                if ($this->serviceContainer[$abstract][1] === true) {
                    // Set the result in the service container for the next request
                    $this->serviceContainer[$abstract][0] = $containerItem;
                }
            }

            // Return the container item
            return $containerItem;
        }

        return new $abstract(...$arguments);
    }

    /**
     * Get an abstract from the container.
     *
     * @param string $abstract The abstract to get
     *
     * @return object
     */
    public function __get(string $abstract) // : object
    {
        return $this->get($abstract);
    }

    /**
     * Check whether an abstract is set in the container.
     *
     * @param string $abstract The abstract to check for
     *
     * @return bool
     */
    public function bound(string $abstract) : bool
    {
        return isset($this->serviceContainer[$abstract]);
    }

    /**
     * Check whether an abstract is set in the container.
     *
     * @param string $abstract The abstract to check for
     *
     * @return bool
     */
    public function __isset(string $abstract) : bool
    {
        return $this->bound($abstract);
    }

    /**
     * Bootstrap the container.
     *
     * @return void
     */
    public function bootstrap() // : void
    {
        // Check if the env has already been set in the container
        if (! $this->bound(ConfigContract::class)) {
            $this->singleton(
                EnvContract::class,
                function ()
                {
                    return new Env();
                }
            );
        }

        // Check if the config has already been set in the container
        if (! $this->bound(ConfigContract::class)) {
            $this->singleton(
                ConfigContract::class,
                function () {
                    return new Config(
                        $this->get(Application::class)
                    );
                }
            );
        }

        // Check if the http exception has already been set in the container
        if (! $this->bound(HttpExceptionContract::class)) {
            $this->bind(
                HttpExceptionContract::class,
                function (
                    int $statusCode,
                    string $message = null,
                    Exception $previous = null,
                    array $headers = [],
                    string $view = null
                ) {
                    return new HttpException($statusCode, $message, $previous, $headers, $view);
                }
            );
        }

        // Check if the request has already been set in the container
        if (! $this->bound(RequestContract::class)) {
            $this->bind(
                RequestContract::class,
                function () {
                    return new Request();
                }
            );
        }

        // Check if the response has already been set in the container
        if (! $this->bound(ResponseContract::class)) {
            $this->bind(
                ResponseContract::class,
                function (string $content = '', int $status = 200, array $headers = []) {
                    return new Response($content, $status, $headers);
                }
            );
        }

        // Check if the json response has already been set in the container
        if (! $this->bound(JsonResponseContract::class)) {
            $this->bind(
                JsonResponseContract::class,
                function (string $content = '', int $status = 200, array $headers = []) {
                    return new JsonResponse($content, $status, $headers);
                }
            );
        }

        // Check if the response builder has already been set in the container
        if (! $this->bound(ResponseBuilderContract::class)) {
            $this->singleton(
                ResponseBuilderContract::class,
                function () {
                    $response = $this->get(ResponseContract::class);
                    $view = $this->get(ViewContract::class);

                    return new ResponseBuilder($response, $view);
                }
            );
        }

        // Check if the router has already been set in the container
        if (! $this->bound(RouterContract::class)) {
            $this->singleton(
                RouterContract::class,
                function () {
                    $app = $this->get(Application::class);

                    return new Router($app);
                }
            );
        }

        // Check if the session has already been set in the container
        if (! $this->bound(SessionContract::class)) {
            $this->singleton(
                SessionContract::class,
                function () {
                    return new Session();
                }
            );
        }

        // Check if the view has already been set in the container
        if (! $this->bound(ViewContract::class)) {
            $this->bind(
                ViewContract::class,
                function (string $template = '', array $variables = []) {
                    return new View($template, $variables);
                }
            );
        }

        // Check if the client has already been set in the container
        if (! $this->bound(ClientContract::class)) {
            $this->bind(
                ClientContract::class,
                function () {
                    return new Client();
                }
            );
        }
    }
}
