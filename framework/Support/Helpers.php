<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Support;

use Exception;

use Valkyrja\Application;
use Valkyrja\Contracts\Application as ApplicationContract;
use Valkyrja\Contracts\Config\Config as ConfigContract;
use Valkyrja\Contracts\Config\Env;
use Valkyrja\Contracts\Container\Container;
use Valkyrja\Contracts\Http\Response;
use Valkyrja\Contracts\Http\ResponseBuilder;
use Valkyrja\Contracts\Http\Router;
use Valkyrja\Contracts\Support\Helpers as HelpersContract;
use Valkyrja\Contracts\View\View;

/**
 * Class Helpers
 *
 * @package Valkyrja\Support
 *
 * @author  Melech Mizrachi
 */
class Helpers implements HelpersContract
{
    /**
     * Return the global $app variable.
     *
     * @return \Valkyrja\Contracts\Application
     */
    public static function app() : ApplicationContract
    {
        return Application::app();
    }

    /**
     * Return the global $app variable.
     *
     * @return \Valkyrja\Contracts\Container\Container
     */
    public static function container() : Container
    {
        return app()->container();
    }

    /**
     * Get the config class instance.
     *
     * @return \Valkyrja\Contracts\Config\Config|\Valkyrja\Config\Config|\config\Config
     */
    public static function config() : ConfigContract
    {
        return static::container()->get(ConfigContract::class);
    }

    /**
     * Get environment variables.
     *
     * @return \Valkyrja\Contracts\Config\Env|\Valkyrja\Config\Env||config|Env
     */
    public static function env() : Env
    {
        return static::container()->get(Env::class);
    }

    /**
     * Return the router instance from the container.
     *
     * @return \Valkyrja\Contracts\Http\Router
     */
    public static function router() : Router
    {
        return static::container()->get(Router::class);
    }

    /**
     * Throw an HttpException with the given data.
     *
     * @param int    $code    The status code to use
     * @param string $message [optional] The message or data content to use
     * @param array  $headers [optional] The headers to set
     * @param string $view    [optional] The view template name to use
     *
     * @return void
     *
     * @throws \Valkyrja\Contracts\Exceptions\HttpException
     */
    public static function abort(int $code, string $message = '', array $headers = [], string $view = null) : void
    {
        static::app()->abort($code, $message, $headers, $view);
    }

    /**
     * Helper function to set a GET addRoute.
     *
     * @param string         $path      The path to set
     * @param \Closure|array $handler   The closure or array of options
     * @param bool           $isDynamic [optional] Does the route have dynamic parameters?
     *
     * @return void
     */
    public static function get(string $path, $handler, bool $isDynamic = false) : void
    {
        static::router()->get($path, $handler, $isDynamic);
    }

    /**
     * Helper function to set a POST addRoute.
     *
     * @param string         $path      The path to set
     * @param \Closure|array $handler   The closure or array of options
     * @param bool           $isDynamic [optional] Does the route have dynamic parameters?
     *
     * @return void
     */
    public static function post(string $path, $handler, bool $isDynamic = false) : void
    {
        static::router()->post($path, $handler, $isDynamic);
    }

    /**
     * Helper function to set a PUT addRoute.
     *
     * @param string         $path      The path to set
     * @param \Closure|array $handler   The closure or array of options
     * @param bool           $isDynamic [optional] Does the route have dynamic parameters?
     *
     * @return void
     */
    public static function put(string $path, $handler, bool $isDynamic = false) : void
    {
        static::router()->put($path, $handler, $isDynamic);
    }

    /**
     * Helper function to set a PATCH addRoute.
     *
     * @param string         $path      The path to set
     * @param \Closure|array $handler   The closure or array of options
     * @param bool           $isDynamic [optional] Does the route have dynamic parameters?
     *
     * @return void
     */
    public static function patch(string $path, $handler, bool $isDynamic = false) : void
    {
        static::router()->patch($path, $handler, $isDynamic);
    }

    /**
     * Helper function to set a DELETE addRoute.
     *
     * @param string         $path      The path to set
     * @param \Closure|array $handler   The closure or array of options
     * @param bool           $isDynamic [optional] Does the route have dynamic parameters?
     *
     * @return void
     */
    public static function delete(string $path, $handler, bool $isDynamic = false) : void
    {
        static::router()->delete($path, $handler, $isDynamic);
    }

    /**
     * Helper function to set a HEAD addRoute.
     *
     * @param string         $path      The path to set
     * @param \Closure|array $handler   The closure or array of options
     * @param bool           $isDynamic [optional] Does the route have dynamic parameters?
     *
     * @return void
     */
    public static function head(string $path, $handler, bool $isDynamic = false) : void
    {
        static::router()->head($path, $handler, $isDynamic);
    }

    /**
     * Helper function to get base path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public static function basePath(string $path = null) : string
    {
        return static::app()->basePath($path);
    }

    /**
     * Helper function to get app path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public static function appPath(string $path = null) : string
    {
        return static::app()->appPath($path);
    }

    /**
     * Helper function to get cache path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public static function cachePath(string $path = null) : string
    {
        return static::app()->cachePath($path);
    }

    /**
     * Helper function to get config path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public static function configPath(string $path = null) : string
    {
        return static::app()->configPath($path);
    }

    /**
     * Helper function to get framework path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public static function frameworkPath(string $path = null) : string
    {
        return static::app()->frameworkPath($path);
    }

    /**
     * Helper function to get public path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public static function publicPath(string $path = null) : string
    {
        return static::app()->publicPath($path);
    }

    /**
     * Helper function to get resources path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public static function resourcesPath(string $path = null) : string
    {
        return static::app()->resourcesPath($path);
    }

    /**
     * Helper function to get storage path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public static function storagePath(string $path = null) : string
    {
        return static::app()->storagePath($path);
    }

    /**
     * Helper function to get tests path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public static function testsPath(string $path = null) : string
    {
        return static::app()->testsPath($path);
    }

    /**
     * Helper function to get vendor path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public static function vendorPath(string $path = null) : string
    {
        return static::app()->vendorPath($path);
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
    public static function response(string $content = '', int $status = 200, array $headers = []) : Response
    {
        $factory = static::responseBuilder();

        return $factory->make($content, $status, $headers);
    }

    /**
     * Return a new response from the application.
     *
     * @return \Valkyrja\Contracts\Http\ResponseBuilder
     */
    public static function responseBuilder() : ResponseBuilder
    {
        return static::container()->get(ResponseBuilder::class);
    }

    /**
     * Helper function to get a new view.
     *
     * @param string $template  [optional] The template to use
     * @param array  $variables [optional] The variables to use
     *
     * @return \Valkyrja\Contracts\View\View
     */
    public static function view(string $template = '', array $variables = []) : View
    {
        return static::container()->get(
            View::class,
            [
                $template,
                $variables,
            ]
        );
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
     * @throws \HttpException
     */
    public static function httpException(
        int $statusCode,
        string $message = null,
        Exception $previous = null,
        array $headers = [],
        string $view = null,
        int $code = 0
    ) : void
    {
        static::app()->httpException($statusCode, $message, $previous, $headers, $view, $code);
    }
}
