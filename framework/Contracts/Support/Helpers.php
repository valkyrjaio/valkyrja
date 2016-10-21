<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Contracts\Support;

/**
 * Interface Helpers
 *
 * @package Valkyrja\Contracts\Support
 *
 * @author  Melech Mizrachi
 */
interface Helpers
{
    /**
     * Return the global $app variable.
     *
     * @return \Valkyrja\Application
     */
    public static function app();

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
    public static function abort($code, $message = '', array $headers = [], $view = null);

    /**
     * Get an item from the container.
     *
     * @param string $abstract  The abstract to get
     * @param array  $arguments [optional] Arguments to pass
     *
     * @return mixed
     */
    public static function container($abstract, array $arguments = []);

    /**
     * Get an environment variable via key.
     *
     * @param string|bool $key     [optional] The variable to get
     * @param mixed       $default [optional] Default value to return if not found
     *
     * @return mixed
     */
    public static function env($key = false, $default = false);

    /**
     * Get a config variable via key.
     *
     * @param string|bool $key     [optional] The variable to get
     * @param mixed       $default [optional] Default value to return if not found
     *
     * @return mixed
     */
    public static function config($key = false, $default = false);

    /**
     * Helper function to set a GET addRoute.
     *
     * @param string         $path      The path to set
     * @param \Closure|array $handler   The closure or array of options
     * @param bool           $isDynamic [optional] Does the route have dynamic parameters?
     *
     * @return void
     */
    public static function get($path, $handler, $isDynamic = false);

    /**
     * Helper function to set a POST addRoute.
     *
     * @param string         $path      The path to set
     * @param \Closure|array $handler   The closure or array of options
     * @param bool           $isDynamic [optional] Does the route have dynamic parameters?
     *
     * @return void
     */
    public static function post($path, $handler, $isDynamic = false);

    /**
     * Helper function to set a PUT addRoute.
     *
     * @param string         $path      The path to set
     * @param \Closure|array $handler   The closure or array of options
     * @param bool           $isDynamic [optional] Does the route have dynamic parameters?
     *
     * @return void
     */
    public static function put($path, $handler, $isDynamic = false);

    /**
     * Helper function to set a PATCH addRoute.
     *
     * @param string         $path      The path to set
     * @param \Closure|array $handler   The closure or array of options
     * @param bool           $isDynamic [optional] Does the route have dynamic parameters?
     *
     * @return void
     */
    public static function patch($path, $handler, $isDynamic = false);

    /**
     * Helper function to set a DELETE addRoute.
     *
     * @param string         $path      The path to set
     * @param \Closure|array $handler   The closure or array of options
     * @param bool           $isDynamic [optional] Does the route have dynamic parameters?
     *
     * @return void
     */
    public static function delete($path, $handler, $isDynamic = false);

    /**
     * Helper function to set a HEAD addRoute.
     *
     * @param string         $path      The path to set
     * @param \Closure|array $handler   The closure or array of options
     * @param bool           $isDynamic [optional] Does the route have dynamic parameters?
     *
     * @return void
     */
    public static function head($path, $handler, $isDynamic = false);

    /**
     * Helper function to get base path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public static function basePath($path = null);

    /**
     * Helper function to get app path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public static function appPath($path = null);

    /**
     * Helper function to get cache path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public static function cachePath($path = null);

    /**
     * Helper function to get config path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public static function configPath($path = null);

    /**
     * Helper function to get framework path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public static function frameworkPath($path = null);

    /**
     * Helper function to get public path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public static function publicPath($path = null);

    /**
     * Helper function to get resources path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public static function resourcesPath($path = null);

    /**
     * Helper function to get storage path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public static function storagePath($path = null);

    /**
     * Helper function to get tests path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public static function testsPath($path = null);

    /**
     * Helper function to get vendor path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public static function vendorPath($path = null);

    /**
     * Return a new response from the application.
     *
     * @param string $content [optional] The content to set
     * @param int    $status  [optional] The status code to set
     * @param array  $headers [optional] The headers to set
     *
     * @return \Valkyrja\Contracts\Http\Response|\Valkyrja\Contracts\Http\ResponseBuilder
     */
    public static function response($content = '', $status = 200, array $headers = []);

    /**
     * Helper function to get a new view.
     *
     * @param string $template  [optional] The template to use
     * @param array  $variables [optional] The variables to use
     *
     * @return \Valkyrja\Contracts\View\View
     */
    public static function view($template = '', array $variables = []);

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
    );
}
