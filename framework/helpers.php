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
     * @return \Valkyrja\Application
     */
    function app()
    {
        global $app;

        return $app;
    }
}

if (!function_exists('abort')) {
    /**
     * Throw an HttpException with the given data.
     *
     * @param int    $code    The status code to use
     * @param string $message The message or data content to use
     * @param array  $headers The headers to set
     * @param string $view    The view template name to use
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    function abort($code, $message = '', array $headers = [], $view = null)
    {
        return app()->abort($code, $message, $headers, $view);
    }
}

if (!function_exists('container')) {
    /**
     * Get an item from the container.
     *
     * @param string $abstract  The abstract to get
     * @param array  $arguments Arguments to pass
     *
     * @return mixed
     */
    function container($abstract, array $arguments = [])
    {
        return app()->container($abstract, $arguments);
    }
}

if (!function_exists('env')) {
    /**
     * Get an environment variable via key.
     *
     * @param string|bool $key     The variable to get
     * @param mixed       $default Default value to return if not found
     *
     * @return mixed
     */
    function env($key, $default = false)
    {
        return app()->env($key, $default);
    }
}

if (!function_exists('get')) {
    /**
     * Helper function to set a GET addRoute.
     *
     * @param string         $path      The path to set
     * @param \Closure|array $handler   The closure or array of options
     * @param bool           $isDynamic Does the route have dynamic parameters?
     *
     * @return void
     */
    function get($path, $handler, $isDynamic = false)
    {
        app()->get($path, $handler, $isDynamic);
    }
}

if (!function_exists('post')) {
    /**
     * Helper function to set a POST addRoute.
     *
     * @param string         $path      The path to set
     * @param \Closure|array $handler   The closure or array of options
     * @param bool           $isDynamic Does the route have dynamic parameters?
     *
     * @return void
     */
    function post($path, $handler, $isDynamic = false)
    {
        app()->post($path, $handler, $isDynamic);
    }
}

if (!function_exists('put')) {
    /**
     * Helper function to set a PUT addRoute.
     *
     * @param string         $path      The path to set
     * @param \Closure|array $handler   The closure or array of options
     * @param bool           $isDynamic Does the route have dynamic parameters?
     *
     * @return void
     */
    function put($path, $handler, $isDynamic = false)
    {
        app()->put($path, $handler, $isDynamic);
    }
}

if (!function_exists('patch')) {
    /**
     * Helper function to set a PATCH addRoute.
     *
     * @param string         $path      The path to set
     * @param \Closure|array $handler   The closure or array of options
     * @param bool           $isDynamic Does the route have dynamic parameters?
     *
     * @return void
     */
    function patch($path, $handler, $isDynamic = false)
    {
        app()->patch($path, $handler, $isDynamic);
    }
}

if (!function_exists('delete')) {
    /**
     * Helper function to set a DELETE addRoute.
     *
     * @param string         $path      The path to set
     * @param \Closure|array $handler   The closure or array of options
     * @param bool           $isDynamic Does the route have dynamic parameters?
     *
     * @return void
     */
    function delete($path, $handler, $isDynamic = false)
    {
        app()->delete($path, $handler, $isDynamic);
    }
}

if (!function_exists('head')) {
    /**
     * Helper function to set a HEAD addRoute.
     *
     * @param string         $path      The path to set
     * @param \Closure|array $handler   The closure or array of options
     * @param bool           $isDynamic Does the route have dynamic parameters?
     *
     * @return void
     */
    function head($path, $handler, $isDynamic = false)
    {
        app()->head($path, $handler, $isDynamic);
    }
}

if (!function_exists('basePath')) {
    /**
     * Helper function to get base path.
     *
     * @param string $path The path to append
     *
     * @return string
     */
    function basePath($path)
    {
        return app()->basePath($path);
    }
}

if (!function_exists('appPath')) {
    /**
     * Helper function to get app path.
     *
     * @param string $path The path to append
     *
     * @return string
     */
    function appPath($path)
    {
        return app()->appPath($path);
    }
}

if (!function_exists('cachePath')) {
    /**
     * Helper function to get cache path.
     *
     * @param string $path The path to append
     *
     * @return string
     */
    function cachePath($path)
    {
        return app()->cachePath($path);
    }
}

if (!function_exists('configPath')) {
    /**
     * Helper function to get config path.
     *
     * @param string $path The path to append
     *
     * @return string
     */
    function configPath($path)
    {
        return app()->configPath($path);
    }
}

if (!function_exists('frameworkPath')) {
    /**
     * Helper function to get framework path.
     *
     * @param string $path The path to append
     *
     * @return string
     */
    function frameworkPath($path)
    {
        return app()->frameworkPath($path);
    }
}

if (!function_exists('publicPath')) {
    /**
     * Helper function to get public path.
     *
     * @param string $path The path to append
     *
     * @return string
     */
    function publicPath($path)
    {
        return app()->publicPath($path);
    }
}

if (!function_exists('resourcesPath')) {
    /**
     * Helper function to get resources path.
     *
     * @param string $path The path to append
     *
     * @return string
     */
    function resourcesPath($path)
    {
        return app()->resourcesPath($path);
    }
}

if (!function_exists('storagePath')) {
    /**
     * Helper function to get storage path.
     *
     * @param string $path The path to append
     *
     * @return string
     */
    function storagePath($path)
    {
        return app()->storagePath($path);
    }
}

if (!function_exists('testsPath')) {
    /**
     * Helper function to get tests path.
     *
     * @param string $path The path to append
     *
     * @return string
     */
    function testsPath($path)
    {
        return app()->testsPath($path);
    }
}

if (!function_exists('vendorPath')) {
    /**
     * Helper function to get vendor path.
     *
     * @param string $path The path to append
     *
     * @return string
     */
    function vendorPath($path)
    {
        return app()->vendorPath($path);
    }
}

if (!function_exists('response')) {
    /**
     * Return a new response from the application.
     *
     * @param string $content The content to set
     * @param int    $status  The status code to set
     * @param array  $headers The headers to set
     *
     * @return \Valkyrja\Contracts\Http\Response|\Valkyrja\Contracts\Http\ResponseBuilder
     */
    function response($content = '', $status = 200, array $headers = [])
    {
        if (func_num_args() === 0) {
            return app()->response();
        }

        return app()->response($content, $status, $headers);
    }
}

if (!function_exists('view')) {
    /**
     * Helper function to get a new view.
     *
     * @param string $template  The template to use
     * @param array  $variables The variables to use
     *
     * @return \Valkyrja\Contracts\View\View
     */
    function view($template = '', array $variables = [])
    {
        return app()->view($template, $variables);
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
