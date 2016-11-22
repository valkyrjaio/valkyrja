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

use ErrorException;
use Exception;

use Valkyrja\Config\Config;
use Valkyrja\Container\Container;
use Valkyrja\Contracts\Application as ApplicationContract;
use Valkyrja\Contracts\Config\Config as ConfigContract;
use Valkyrja\Contracts\Container\Container as ContainerContract;
use Valkyrja\Contracts\Exceptions\HttpException;
use Valkyrja\Contracts\Http\Response;
use Valkyrja\Contracts\Http\ResponseBuilder;
use Valkyrja\Contracts\Http\Router;
use Valkyrja\Contracts\View\View;
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
     * Application constructor.
     *
     * @param string $basePath The base path for the application
     */
    public function __construct($basePath)
    {
        $this->basePath = $basePath;
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
     * Get the config class instance.
     *
     * @return \Valkyrja\Contracts\Config\Config|\Valkyrja\Config\Config|\config\Config
     */
    public function config() : ConfigContract
    {
        return Config::config();
    }

    /**
     * Get the environment with which the application is running in.
     *
     * @return string
     */
    public function environment() : string
    {
        return $this->config()->app->env ?? 'production';
    }

    /**
     * Whether the application is running in debug mode or not.
     *
     * @return string
     */
    public function debug() : string
    {
        return $this->config()->app->debug ?? false;
    }

    /**
     * Is twig enabled?
     *
     * @return bool
     */
    public function isTwigEnabled() : bool
    {
        return $this->config()->views->twig->enabled ?? false;
    }

    /**
     * Set the timezone for the application process.
     *
     * @return void
     */
    public function setTimezone() // : void
    {
        date_default_timezone_set($this->config()->app->timezone ?? 'UTC');
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
     * Bootstrap error, exception, and shutdown handler.
     *
     * @return void
     */
    public function bootstrapHandler() // : void
    {
        error_reporting(-1);

        set_error_handler(
            [
                $this,
                'handleError',
            ]
        );

        set_exception_handler(
            [
                $this,
                'handleException',
            ]
        );

        register_shutdown_function(
            [
                $this,
                'handleShutdown',
            ]
        );

        if (!$this->debug()) {
            ini_set('display_errors', 'Off');
        }
    }

    /**
     * Convert a PHP error to an ErrorException.
     *
     * @param int    $level   The error level
     * @param string $message The error message
     * @param string $file    [optional] The file within which the error occurred
     * @param int    $line    [optional] The line which threw the error
     * @param array  $context [optional] The context for the exception
     *
     * @return void
     *
     * @throws \Exception
     */
    public function handleError($level, $message, $file = '', $line = 0, $context = []) // : void
    {
        if (error_reporting() & $level) {
            throw new ErrorException($message, 0, $level, $file, $line);
        }
    }

    /**
     * Handle an uncaught exception from the application.
     *
     * Note: Most exceptions can be handled via the try / catch block in
     * the HTTP and Console kernels. But, fatal error exceptions must
     * be handled differently since they are not normal exceptions.
     *
     * @param \Throwable $e The exception that was captured
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function handleException($e) : Response
    {
        if (!$e instanceof Exception) {
            $e = new Exception($e);
        }

        $data = [
            'message' => $e->getMessage(),
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
            'trace'   => $e->getTrace(),
        ];
        $view = 'errors/exception';
        $headers = [];
        $code = 500;

        if ($e instanceof HttpException) {
            $code = $e->getStatusCode();
            $headers = $e->getHeaders();
            $view = $e->getView()
                ?: $view;
        }

        // Return a new sent response
        return $this->responseBuilder()
                    ->view($view, $data, $code, $headers)
                    ->send();
    }

    /**
     * Handle the PHP shutdown event.
     *
     * @return void
     */
    public function handleShutdown() // : void
    {
        if (!is_null($error = error_get_last())
            && in_array(
                $error['type'],
                [
                    E_ERROR,
                    E_CORE_ERROR,
                    E_COMPILE_ERROR,
                    E_PARSE,
                ]
            )
        ) {
            $this->handleException($this->fatalExceptionFromError($error));
        }
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
        throw $this->container()->get(
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
     * Create a new fatal exception instance from an error array.
     *
     * @param array $error The error array to use
     *
     * @return \Exception
     */
    protected function fatalExceptionFromError(array $error) : Exception
    {
        return new ErrorException(
            $error['message'], 0, $error['type'], $error['file'], $error['line']
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
        $factory = $this->responseBuilder();

        return $factory->make($content, $status, $headers);
    }

    /**
     * Return a new response builder from the application.
     *
     * @return \Valkyrja\Contracts\Http\ResponseBuilder
     */
    public function responseBuilder() : ResponseBuilder
    {
        return $this->container()->get(ResponseBuilder::class);
    }

    /**
     * Return the router instance from the container.
     *
     * @return \Valkyrja\Contracts\Http\Router
     */
    public function router() : Router
    {
        return $this->container()->get(Router::class);
    }

    /**
     * Return a new view.
     *
     * @param string $template  [optional] The template to use
     * @param array  $variables [optional] The variables to use
     *
     * @return \Valkyrja\Contracts\View\View
     */
    public function view(string $template = '', array $variables = []) : View
    {
        return $this->container()->get(
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
    public function run() // : void
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
    public function register(string $serviceProvider) // : void
    {
        // Create a new instance of the service provider
        new $serviceProvider($this);
    }

    /**
     * Set the service container for dependency injection.
     *
     * @return \Valkyrja\Contracts\Container\Container
     */
    public function container() : ContainerContract
    {
        return Container::container();
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
        $this->container()->instance($abstract, $instance);
    }
}
