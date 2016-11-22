<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

if (!function_exists('app')) {
    /**
     * Return the global $app variable.
     *
     * @return \Valkyrja\Contracts\Application
     */
    function app()
    {
        return \Valkyrja\Support\Helpers::app();
    }
}

if (!function_exists('abort')) {
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
    function abort($code, $message = '', array $headers = [], $view = null)
    {
        \Valkyrja\Support\Helpers::abort($code, $message, $headers, $view);
    }
}

if (!function_exists('container')) {
    /**
     * Return the global $app variable.
     *
     * @return \Valkyrja\Contracts\Container\Container
     */
    function container()
    {
        return \Valkyrja\Support\Helpers::container();
    }
}

if (!function_exists('container')) {
    /**
     * Get an item from the container.
     *
     * @param string $abstract  The abstract to get
     * @param array  $arguments [optional] Arguments to pass
     *
     * @return mixed
     */
    function instance($abstract, array $arguments = [])
    {
        return container()->get($abstract, $arguments);
    }
}

if (!function_exists('config')) {
    /**
     * Get config.
     *
     * @return \config\Config|\Valkyrja\Config\Config|\Valkyrja\Contracts\Config\Config
     */
    function config()
    {
        return \Valkyrja\Support\Helpers::config();
    }
}

if (!function_exists('get')) {
    /**
     * Helper function to set a GET addRoute.
     *
     * @param string         $path      The path to set
     * @param \Closure|array $handler   The closure or array of options
     * @param bool           $isDynamic [optional] Does the route have dynamic parameters?
     *
     * @return void
     */
    function get($path, $handler, $isDynamic = false)
    {
        \Valkyrja\Support\Helpers::get($path, $handler, $isDynamic);
    }
}

if (!function_exists('post')) {
    /**
     * Helper function to set a POST addRoute.
     *
     * @param string         $path      The path to set
     * @param \Closure|array $handler   The closure or array of options
     * @param bool           $isDynamic [optional] Does the route have dynamic parameters?
     *
     * @return void
     */
    function post($path, $handler, $isDynamic = false)
    {
        \Valkyrja\Support\Helpers::post($path, $handler, $isDynamic);
    }
}

if (!function_exists('put')) {
    /**
     * Helper function to set a PUT addRoute.
     *
     * @param string         $path      The path to set
     * @param \Closure|array $handler   The closure or array of options
     * @param bool           $isDynamic [optional] Does the route have dynamic parameters?
     *
     * @return void
     */
    function put($path, $handler, $isDynamic = false)
    {
        \Valkyrja\Support\Helpers::put($path, $handler, $isDynamic);
    }
}

if (!function_exists('patch')) {
    /**
     * Helper function to set a PATCH addRoute.
     *
     * @param string         $path      The path to set
     * @param \Closure|array $handler   The closure or array of options
     * @param bool           $isDynamic [optional] Does the route have dynamic parameters?
     *
     * @return void
     */
    function patch($path, $handler, $isDynamic = false)
    {
        \Valkyrja\Support\Helpers::patch($path, $handler, $isDynamic);
    }
}

if (!function_exists('delete')) {
    /**
     * Helper function to set a DELETE addRoute.
     *
     * @param string         $path      The path to set
     * @param \Closure|array $handler   The closure or array of options
     * @param bool           $isDynamic [optional] Does the route have dynamic parameters?
     *
     * @return void
     */
    function delete($path, $handler, $isDynamic = false)
    {
        \Valkyrja\Support\Helpers::delete($path, $handler, $isDynamic);
    }
}

if (!function_exists('head')) {
    /**
     * Helper function to set a HEAD addRoute.
     *
     * @param string         $path      The path to set
     * @param \Closure|array $handler   The closure or array of options
     * @param bool           $isDynamic [optional] Does the route have dynamic parameters?
     *
     * @return void
     */
    function head($path, $handler, $isDynamic = false)
    {
        \Valkyrja\Support\Helpers::head($path, $handler, $isDynamic);
    }
}

if (!function_exists('basePath')) {
    /**
     * Helper function to get base path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    function basePath($path = null)
    {
        return \Valkyrja\Support\Helpers::basePath($path);
    }
}

if (!function_exists('appPath')) {
    /**
     * Helper function to get app path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    function appPath($path = null)
    {
        return \Valkyrja\Support\Helpers::appPath($path);
    }
}

if (!function_exists('cachePath')) {
    /**
     * Helper function to get cache path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    function cachePath($path = null)
    {
        return \Valkyrja\Support\Helpers::cachePath($path);
    }
}

if (!function_exists('configPath')) {
    /**
     * Helper function to get config path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    function configPath($path = null)
    {
        return \Valkyrja\Support\Helpers::configPath($path);
    }
}

if (!function_exists('frameworkPath')) {
    /**
     * Helper function to get framework path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    function frameworkPath($path = null)
    {
        return \Valkyrja\Support\Helpers::frameworkPath($path);
    }
}

if (!function_exists('publicPath')) {
    /**
     * Helper function to get public path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    function publicPath($path = null)
    {
        return \Valkyrja\Support\Helpers::publicPath($path);
    }
}

if (!function_exists('resourcesPath')) {
    /**
     * Helper function to get resources path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    function resourcesPath($path = null)
    {
        return \Valkyrja\Support\Helpers::resourcesPath($path);
    }
}

if (!function_exists('storagePath')) {
    /**
     * Helper function to get storage path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    function storagePath($path = null)
    {
        return \Valkyrja\Support\Helpers::storagePath($path);
    }
}

if (!function_exists('testsPath')) {
    /**
     * Helper function to get tests path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    function testsPath($path = null)
    {
        return \Valkyrja\Support\Helpers::testsPath($path);
    }
}

if (!function_exists('vendorPath')) {
    /**
     * Helper function to get vendor path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    function vendorPath($path = null)
    {
        return \Valkyrja\Support\Helpers::vendorPath($path);
    }
}

if (!function_exists('response')) {
    /**
     * Return a new response from the application.
     *
     * @param string $content [optional] The content to set
     * @param int    $status  [optional] The status code to set
     * @param array  $headers [optional] The headers to set
     *
     * @return \Valkyrja\Contracts\Http\Response|\Valkyrja\Contracts\Http\ResponseBuilder
     */
    function response($content = '', $status = 200, array $headers = [])
    {
        if (func_num_args() === 0) {
            return \Valkyrja\Support\Helpers::responseBuilder();
        }

        return \Valkyrja\Support\Helpers::response($content, $status, $headers);
    }
}

if (!function_exists('view')) {
    /**
     * Helper function to get a new view.
     *
     * @param string $template  [optional] The template to use
     * @param array  $variables [optional] The variables to use
     *
     * @return \Valkyrja\Contracts\View\View
     */
    function view($template = '', array $variables = [])
    {
        return \Valkyrja\Support\Helpers::view($template, $variables);
    }
}

if (!function_exists('httpException')) {
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
    function httpException(
        $statusCode,
        $message = null,
        \Exception $previous = null,
        array $headers = [],
        $view = null,
        $code = 0
    ) {
        \Valkyrja\Support\Helpers::httpException($statusCode, $message, $previous, $headers, $view, $code);
    }
}

if (!function_exists('dd')) {
    /**
     * Dump the passed variables and end the script.
     *
     * @param mixed
     *  The arguments to dump
     *
     * @return void
     */
    function dd()
    {
        var_dump(func_get_args());

        die(1);
    }
}
