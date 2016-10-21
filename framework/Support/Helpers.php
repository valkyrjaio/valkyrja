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

use Valkyrja\Application;
use Valkyrja\Contracts\Support\Helpers as HelpersContract;

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
     * @return \Valkyrja\Application
     */
    public static function app()
    {
        return Application::app();
    }

    /**
     * Throw an HttpException with the given data.
     *
     * @param int    $code    The status code to use
     * @param string $message [optional] The message or data content to use
     * @param array  $headers [optional] The headers to set
     * @param string $view    [optional] The view template name to use
     *
     * @throws \Valkyrja\Contracts\Exceptions\HttpException
     */
    public static function abort($code, $message = '', array $headers = [], $view = null)
    {
        static::app()->abort($code, $message, $headers, $view);
    }

    /**
     * Get an item from the container.
     *
     * @param string $abstract  The abstract to get
     * @param array  $arguments [optional] Arguments to pass
     *
     * @return mixed
     */
    public static function container($abstract, array $arguments = [])
    {
        return static::app()->container($abstract, $arguments);
    }

    /**
     * Get an environment variable via key.
     *
     * @param string|bool $key     [optional] The variable to get
     * @param mixed       $default [optional] Default value to return if not found
     *
     * @return mixed
     */
    public static function env($key = false, $default = false)
    {
        return static::app()->env($key, $default);
    }

    /**
     * Get a config variable via key.
     *
     * @param string|bool $key     [optional] The variable to get
     * @param mixed       $default [optional] Default value to return if not found
     *
     * @return mixed
     */
    public static function config($key = false, $default = false)
    {
        return static::app()->config($key, $default);
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
    public static function get($path, $handler, $isDynamic = false)
    {
        static::app()
            ->router()
            ->get($path, $handler, $isDynamic);
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
    public static function post($path, $handler, $isDynamic = false)
    {
        static::app()
            ->router()
            ->post($path, $handler, $isDynamic);
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
    public static function put($path, $handler, $isDynamic = false)
    {
        static::app()
            ->router()
            ->put($path, $handler, $isDynamic);
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
    public static function patch($path, $handler, $isDynamic = false)
    {
        static::app()
            ->router()
            ->patch($path, $handler, $isDynamic);
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
    public static function delete($path, $handler, $isDynamic = false)
    {
        static::app()
            ->router()
            ->delete($path, $handler, $isDynamic);
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
    public static function head($path, $handler, $isDynamic = false)
    {
        static::app()
            ->router()
            ->head($path, $handler, $isDynamic);
    }

    /**
     * Helper function to get base path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public static function basePath($path = null)
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
    public static function appPath($path = null)
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
    public static function cachePath($path = null)
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
    public static function configPath($path = null)
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
    public static function frameworkPath($path = null)
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
    public static function publicPath($path = null)
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
    public static function resourcesPath($path = null)
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
    public static function storagePath($path = null)
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
    public static function testsPath($path = null)
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
    public static function vendorPath($path = null)
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
     * @return \Valkyrja\Contracts\Http\Response|\Valkyrja\Contracts\Http\ResponseBuilder
     */
    public static function response($content = '', $status = 200, array $headers = [])
    {
        if (func_num_args() === 0) {
            return app()->response();
        }

        return static::app()->response($content, $status, $headers);
    }

    /**
     * Helper function to get a new view.
     *
     * @param string $template  [optional] The template to use
     * @param array  $variables [optional] The variables to use
     *
     * @return \Valkyrja\Contracts\View\View
     */
    public static function view($template = '', array $variables = [])
    {
        return static::app()->view($template, $variables);
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
     * @throws \HttpException
     */
    public static function httpException(
        $statusCode,
        $message = null,
        \Exception $previous = null,
        array $headers = [],
        $view = null,
        $code = 0
    ) {
        static::app()->httpException($statusCode, $message, $previous, $headers, $view, $code);
    }
}
