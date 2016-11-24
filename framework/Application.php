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

use Valkyrja\Contracts\Application as ApplicationContract;
use Valkyrja\Contracts\Exceptions\HttpException;
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
