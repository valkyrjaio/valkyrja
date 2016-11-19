<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja;

use Valkyrja\Container\Container;
use Valkyrja\Contracts\Application as ApplicationContract;
use Valkyrja\Contracts\Container\Container as ContainerContract;
use Valkyrja\Contracts\Http\Response;
use Valkyrja\Contracts\Http\ResponseBuilder;
use Valkyrja\Contracts\Http\Router;
use Valkyrja\Contracts\View\View;
use Valkyrja\Exceptions\ExceptionHandler;
use Valkyrja\Support\PathHelpers;

/**
 * Class Application
 *
 * @package Valkyrja
 *
 * @author  Melech Mizrachi
 */
class Application implements ApplicationContract
{
    use ExceptionHandler;
    use PathHelpers;

    /**
     * Directory separator.
     *
     * @constant string
     */
    const DIRECTORY_SEPARATOR = '/';

    /**
     * Application environment variables.
     *
     * @var array
     */
    protected $env = [];

    /**
     * Application config variables.
     *
     * @var array
     */
    protected $config = [];

    /**
     * The base directory for the application.
     *
     * @var string
     */
    protected $basePath;

    /**
     * Is the app using a compiled version?
     *
     * @var bool
     */
    protected $isCompiled = false;

    /**
     * The container to use.
     *
     * @var \Valkyrja\Contracts\Container\Container
     */
    protected $container;

    /**
     * Application constructor.
     *
     * @param string $basePath The base path for the application
     */
    public function __construct($basePath)
    {
        $this->basePath = $basePath;

        $this->bootstrapContainer();
        $this->bootstrapHandler();
    }

    /**
     * Return the global $app variable.
     *
     * @return ApplicationContract
     */
    public static function app() : ApplicationContract
    {
        global $app;

        return $app;
    }

    /**
     * Get the application version.
     *
     * @return string
     */
    public function version() : string
    {
        return static::VERSION;
    }

    /**
     * Get the environment with which the application is running in.
     *
     * @return string
     */
    public function environment() : string
    {
        return $this->config('app.env', 'production');
    }

    /**
     * Whether the application is running in debug mode or not.
     *
     * @return string
     */
    public function debug() : string
    {
        return $this->config('app.debug', false);
    }

    /**
     * Is twig enabled?
     *
     * @return bool
     */
    public function isTwigEnabled() : bool
    {
        return $this->config('views.twig.enabled', false);
    }

    /**
     * Set the timezone for the application process.
     *
     * @return void
     */
    public function setTimezone() : void
    {
        date_default_timezone_set($this->config('app.timezone', 'UTC'));
    }

    /**
     * Get whether the application is using a compiled version.
     *
     * @return bool
     */
    public function isCompiled() : bool
    {
        return $this->isCompiled;
    }

    /**
     * Set the application as using compiled.
     *
     * @return void
     */
    public function setCompiled() : void
    {
        $this->isCompiled = true;
    }

    /**
     * Get a single environment variable via key or get all.
     *
     * @param string|bool $key     [optional] The variable to get
     * @param mixed       $default [optional] Default value to return if not found
     *
     * @return mixed
     */
    public function env($key = false, $default = false) : mixed
    {
        if (!$key) {
            return $this->env;
        }

        return isset($this->env[$key])
            ? $this->env[$key]
            : $default;
    }

    /**
     * Set a single environment variable.
     *
     * @param string $key   The key to set
     * @param mixed  $value The value to set
     *
     * @return ApplicationContract
     */
    public function setEnv($key, $value) : ApplicationContract
    {
        $this->env[$key] = $value;

        return $this;
    }

    /**
     * Set all environment variables.
     *
     * @param array $env The environment variables to set
     *
     * @return void
     */
    public function setEnvs(array $env) : void
    {
        $this->env = $env;
    }

    /**
     * Get a single config variable via key or get all.
     *
     * @param string|bool $key     [optional] The variable to get
     * @param mixed       $default [optional] Default value to return if not found
     *
     * @return mixed
     */
    public function config($key = false, $default = false) : mixed
    {
        if (!$key) {
            return $this->config;
        }

        if (isset($this->config[$key])) {
            return $this->config[$key];
        }

        $indexes = explode('.', $key);
        $configItem = $this->config;

        foreach ($indexes as $index) {
            if (isset($configItem[$index])) {
                $configItem = $configItem[$index];
            }
            else {
                return $default;
            }
        }

        return $configItem;
    }

    /**
     * Set a single config variable.
     *
     * @param string $key   The key to set
     * @param mixed  $value The value to set
     *
     * @return ApplicationContract
     */
    public function setConfig($key, $value) : ApplicationContract
    {
        if (isset($this->config[$key])) {
            $this->config[$key] = $value;

            return $this;
        }

        $indexes = explode('.', $key);
        $configItem = &$this->config;
        $totalIndexes = sizeof($indexes);

        foreach ($indexes as $indexKey => $index) {
            if (!isset($configItem[$index])) {
                $configItem[$index] = $indexKey + 1 === $totalIndexes
                    ? $value
                    : [];
            }

            $configItem = &$configItem[$index];
        }

        return $this;
    }

    /**
     * Set all config variables.
     *
     * @param array $config The environment variables to set
     *
     * @return void
     */
    public function setConfigVars(array $config) : void
    {
        $this->config = $config;
    }

    /**
     * Abort the application due to error.
     *
     * @param int    $code    [optional] The status code to use
     * @param string $message [optional] The message or data content to use
     * @param array  $headers [optional] The headers to set
     * @param string $view    [optional] The view template name to use
     *
     * @return void
     *
     * @throws \Valkyrja\Contracts\Exceptions\HttpException
     */
    public function abort($code = 404, $message = '', array $headers = [], $view = null) : void
    {
        $this->httpException($code, $message, null, $headers, $view);
    }

    /**
     * Return a new response from the application.
     *
     * @param string $content [optional] The content to set
     * @param int    $status  [optional] The status code to set
     * @param array  $headers [optional] The headers to set
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function response($content = '', $status = 200, array $headers = []) : Response
    {
        $factory = $this->responseBuilder();

        // Otherwise return a new Response using the ResponseBuilder->make() method
        return $factory->make($content, $status, $headers);
    }

    /**
     * Return a new response builder from the application.
     *
     * @return \Valkyrja\Contracts\Http\ResponseBuilder
     */
    public function responseBuilder() : ResponseBuilder
    {
        // Otherwise return a new Response using the ResponseBuilder->make() method
        return $this->container(ResponseBuilder::class);
    }

    /**
     * Return the router instance from the container.
     *
     * @return \Valkyrja\Contracts\Http\Router
     */
    public function router() : Router
    {
        return $this->container(Router::class);
    }

    /**
     * Return a new view.
     *
     * @param string $template  [optional] The template to use
     * @param array  $variables [optional] The variables to use
     *
     * @return \Valkyrja\Contracts\View\View
     */
    public function view($template = '', array $variables = []) : View
    {
        return $this->container(
            View::class,
            [
                $template,
                $variables,
            ]
        );
    }

    /**
     * Run the application.
     *
     * @return void
     */
    public function run() : void
    {
        // Dispatch the request and get a response
        $this->router()
             ->dispatch();
    }

    /**
     * Register a service provider.
     *
     * @param string $serviceProvider The service provider
     *
     * @return void
     */
    public function register($serviceProvider) : void
    {
        // Create a new instance of the service provider
        new $serviceProvider($this);
    }

    /**
     * Bootstrap the application container.
     *
     * @return void
     */
    protected function bootstrapContainer() : void
    {
        if (is_null($this->container)) {
            $this->container = new Container;
        }

        /**
         * Set App instance within container.
         */
        $this->instance(Application::class, $this);
    }

    /**
     * Set the container to use.
     *
     * @param \Valkyrja\Contracts\Container\Container $container
     *
     * @return void
     */
    public function setContainer(ContainerContract $container) : void
    {
        $this->container = $container;
    }

    /**
     * Set the service container for dependency injection.
     *
     * @param string               $abstract The abstract to use as the key
     * @param \Closure|array|mixed $instance The instance to set
     *
     * @return void
     */
    public function instance($abstract, $instance) : void
    {
        $this->container->instance($abstract, $instance);
    }
}
