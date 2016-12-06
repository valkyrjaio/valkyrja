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

use Exception;

use Valkyrja\Container\Container;
use Valkyrja\Contracts\Application as ApplicationContract;
use Valkyrja\Contracts\Config\Config;
use Valkyrja\Contracts\Config\Env;
use Valkyrja\Contracts\Container\Container as ContainerContract;
use Valkyrja\Contracts\Exceptions\ExceptionHandler as ExceptionHandlerContract;
use Valkyrja\Contracts\Exceptions\HttpException;
use Valkyrja\Contracts\Http\Response;
use Valkyrja\Contracts\Http\ResponseBuilder;
use Valkyrja\Contracts\Http\Router;
use Valkyrja\Contracts\View\View;
use Valkyrja\Exceptions\ExceptionHandler;
use Valkyrja\Support\Helpers;
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
    use PathHelpers;

    /**
     * Directory separator.
     *
     * @constant string
     */
    const DIRECTORY_SEPARATOR = '/';

    /**
     * Get the instance of the application.
     *
     * @var \Valkyrja\Contracts\Application
     */
    protected static $app;

    /**
     * Get the instance of the container.
     *
     * @var \Valkyrja\Contracts\Container\Container
     */
    protected $container;

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
     * Application constructor.
     *
     * @param string                                          $basePath         The base path for the application
     * @param \Valkyrja\Contracts\Container\Container         $container        The container to use
     * @param \Valkyrja\Contracts\Exceptions\ExceptionHandler $exceptionHandler The exception handler to use
     */
    public function __construct($basePath, ContainerContract $container = null, ExceptionHandlerContract $exceptionHandler = null)
    {
        // If a container has not been passed in
        if (! $container instanceof ContainerContract) {
            // Use the Valkyrja container
            $container = new Container();
        }

        // Set the app static
        static::$app = $this;

        // Set the container within the application
        $this->container = $container;

        // Set the application instance in the container
        $container->instance(ApplicationContract::class, $this);
        // Bootstrap the container
        $container->bootstrap();

        // If an exception handler has not been passed in
        if (! $exceptionHandler instanceof ExceptionHandlerContract) {
            // Use the Valkyrja exception handler
            new ExceptionHandler();
        }

        $this->basePath = $basePath;
    }

    /**
     * Get the application instance.
     *
     * @return \Valkyrja\Contracts\Application
     */
    public static function app() : ApplicationContract
    {
        return static::$app;
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
     * Get the container instance.
     *
     * @return \Valkyrja\Contracts\Container\Container
     */
    public function container() : ContainerContract
    {
        return $this->container;
    }

    /**
     * Get the config class instance.
     *
     * @return \Valkyrja\Contracts\Config\Config|\Valkyrja\Config\Config|\config\Config
     */
    public function config() : Config
    {
        return $this->container->get(Config::class);
    }

    /**
     * Get environment variables.
     *
     * @return \Valkyrja\Contracts\Config\Env|\Valkyrja\Config\Env||config|Env
     */
    public function env() : Env
    {
        return $this->container->get(Env::class);
    }

    /**
     * Return the router instance from the container.
     *
     * @return \Valkyrja\Contracts\Http\Router
     */
    public function router() : Router
    {
        return $this->container->get(Router::class);
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
    public function response(string $content = '', int $status = 200, array $headers = []) : Response
    {
        $factory = static::responseBuilder();

        return $factory->make($content, $status, $headers);
    }

    /**
     * Return a new response from the application.
     *
     * @return \Valkyrja\Contracts\Http\ResponseBuilder
     */
    public function responseBuilder() : ResponseBuilder
    {
        return $this->container->get(ResponseBuilder::class);
    }

    /**
     * Helper function to get a new view.
     *
     * @param string $template  [optional] The template to use
     * @param array  $variables [optional] The variables to use
     *
     * @return \Valkyrja\Contracts\View\View
     */
    public function view(string $template = '', array $variables = []) : View
    {
        return $this->container->get(
            View::class,
            [
                $template,
                $variables,
            ]
        );
    }

    /**
     * Get the environment with which the application is running in.
     *
     * @return string
     */
    public function environment() : string
    {
        return Helpers::config()->app->env ?? 'production';
    }

    /**
     * Whether the application is running in debug mode or not.
     *
     * @return string
     */
    public function debug() : string
    {
        return Helpers::config()->app->debug ?? false;
    }

    /**
     * Is twig enabled?
     *
     * @return bool
     */
    public function isTwigEnabled() : bool
    {
        return Helpers::config()->views->twig->enabled ?? false;
    }

    /**
     * Set the timezone for the application process.
     *
     * @return void
     */
    public function setTimezone() // : void
    {
        date_default_timezone_set(Helpers::config()->app->timezone ?? 'UTC');
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
    public function setCompiled() // : void
    {
        $this->isCompiled = true;
    }

    /**
     * Throw an http exception.
     *
     * @param int        $statusCode The status code to use
     * @param string     $message    [optional] The Exception message to throw
     * @param \Exception $previous   [optional] The previous exception used for the exception chaining
     * @param array      $headers    [optional] The headers to send
     * @param string     $view       [optional] The view template name to use
     * @param int        $code       [optional] The Exception code
     *
     * @return void
     *
     * @throws HttpException
     */
    public function httpException(
        $statusCode,
        $message = null,
        Exception $previous = null,
        array $headers = [],
        $view = null,
        $code = 0
    ) // : void
    {
        throw Helpers::container()->get(
            HttpException::class,
            [
                $statusCode,
                $message,
                $previous,
                $headers,
                $view,
                $code,
            ]
        );
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
    public function abort(int $code = 404, string $message = '', array $headers = [], string $view = null) // : void
    {
        $this->httpException($code, $message, null, $headers, $view);
    }

    /**
     * Run the application.
     *
     * @return void
     */
    public function run() // : void
    {
        // Dispatch the request and get a response
        Helpers::router()->dispatch();
    }

    /**
     * Register a service provider.
     *
     * @param string $serviceProvider The service provider
     *
     * @return void
     */
    public function register(string $serviceProvider) // : void
    {
        // Create a new instance of the service provider
        new $serviceProvider($this);
    }
}
