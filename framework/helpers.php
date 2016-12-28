<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

if (! function_exists('app')) {
    /**
     * Return the global $app variable.
     *
     * @return \Valkyrja\Contracts\Application
     */
    function app(): Valkyrja\Contracts\Application
    {
        return Valkyrja\Application::app();
    }
}

if (! function_exists('abort')) {
    /**
     * Abort the application due to error.
     *
     * @param int    $statusCode The status code to use
     * @param string $message    [optional] The Exception message to throw
     * @param array  $headers    [optional] The headers to send
     * @param int    $code       [optional] The Exception code
     *
     * @return void
     *
     * @throws \Valkyrja\Contracts\Http\Exceptions\HttpException
     */
    function abort(int $statusCode = 404, string $message = '', array $headers = [], int $code = 0): void
    {
        app()->abort($statusCode, $message, $headers, $code);
    }
}

if (! function_exists('container')) {
    /**
     * Return the global $app variable.
     *
     * @return \Valkyrja\Contracts\Container\Container
     */
    function container(): Valkyrja\Contracts\Container\Container
    {
        return app()->container();
    }
}

if (! function_exists('container')) {
    /**
     * Get an item from the container.
     *
     * @param string $abstract  The abstract to get
     * @param array  $arguments [optional] Arguments to pass
     *
     * @return mixed
     */
    function instance(string $abstract, array $arguments = []) // : object
    {
        return container()->get($abstract, $arguments);
    }
}

if (! function_exists('config')) {
    /**
     * Get config.
     *
     * @return \config\Config|\Valkyrja\Config\Config|\Valkyrja\Contracts\Config\Config
     */
    function config(): Valkyrja\Contracts\Config\Config
    {
        return app()->config();
    }
}

if (! function_exists('router')) {
    /**
     * Get router.
     *
     * @return \Valkyrja\Contracts\Routing\Router
     */
    function router(): Valkyrja\Contracts\Routing\Router
    {
        return app()->router();
    }
}

if (! function_exists('responseBuilder')) {
    /**
     * Get the response builder.
     *
     * @return \Valkyrja\Contracts\Http\ResponseBuilder
     */
    function responseBuilder(): Valkyrja\Contracts\Http\ResponseBuilder
    {
        return app()->responseBuilder();
    }
}

if (! function_exists('response')) {
    /**
     * Return a new response from the application.
     *
     * @param string $content [optional] The content to set
     * @param int    $status  [optional] The status code to set
     * @param array  $headers [optional] The headers to set
     *
     * @return \Valkyrja\Contracts\Http\Response|\Valkyrja\Contracts\Http\ResponseBuilder
     */
    function response(string $content = '', int $status = 200, array $headers = []): Valkyrja\Contracts\Http\Response
    {
        return app()->response($content, $status, $headers);
    }
}

if (! function_exists('view')) {
    /**
     * Helper function to get a new view.
     *
     * @param string $template  [optional] The template to use
     * @param array  $variables [optional] The variables to use
     *
     * @return \Valkyrja\Contracts\View\View
     */
    function view(string $template = '', array $variables = []): Valkyrja\Contracts\View\View
    {
        return app()->view($template, $variables);
    }
}

if (! function_exists('get')) {
    /**
     * Helper function to set a GET addRoute.
     *
     * @param string $path      The path to set
     * @param array  $options   The closure or array of options
     * @param bool   $isDynamic [optional] Does the route have dynamic parameters?
     *
     * @return void
     */
    function get(string $path, array $options, bool $isDynamic = false): void
    {
        router()->get($path, $options, $isDynamic);
    }
}

if (! function_exists('post')) {
    /**
     * Helper function to set a POST addRoute.
     *
     * @param string $path      The path to set
     * @param array  $options   The closure or array of options
     * @param bool   $isDynamic [optional] Does the route have dynamic parameters?
     *
     * @return void
     */
    function post(string $path, array $options, bool $isDynamic = false): void
    {
        router()->post($path, $options, $isDynamic);
    }
}

if (! function_exists('put')) {
    /**
     * Helper function to set a PUT addRoute.
     *
     * @param string $path      The path to set
     * @param array  $options   The closure or array of options
     * @param bool   $isDynamic [optional] Does the route have dynamic parameters?
     *
     * @return void
     */
    function put(string $path, array $options, bool $isDynamic = false): void
    {
        router()->put($path, $options, $isDynamic);
    }
}

if (! function_exists('patch')) {
    /**
     * Helper function to set a PATCH addRoute.
     *
     * @param string $path      The path to set
     * @param array  $options   The closure or array of options
     * @param bool   $isDynamic [optional] Does the route have dynamic parameters?
     *
     * @return void
     */
    function patch(string $path, array $options, bool $isDynamic = false): void
    {
        router()->patch($path, $options, $isDynamic);
    }
}

if (! function_exists('delete')) {
    /**
     * Helper function to set a DELETE addRoute.
     *
     * @param string $path      The path to set
     * @param array  $options   The closure or array of options
     * @param bool   $isDynamic [optional] Does the route have dynamic parameters?
     *
     * @return void
     */
    function delete(string $path, array $options, bool $isDynamic = false): void
    {
        router()->delete($path, $options, $isDynamic);
    }
}

if (! function_exists('head')) {
    /**
     * Helper function to set a HEAD addRoute.
     *
     * @param string $path      The path to set
     * @param array  $options   The closure or array of options
     * @param bool   $isDynamic [optional] Does the route have dynamic parameters?
     *
     * @return void
     */
    function head(string $path, array $options, bool $isDynamic = false): void
    {
        router()->head($path, $options, $isDynamic);
    }
}

if (! function_exists('basePath')) {
    /**
     * Helper function to get base path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    function basePath(string $path = null): string
    {
        return Valkyrja\Support\Directory::basePath($path);
    }
}

if (! function_exists('appPath')) {
    /**
     * Helper function to get app path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    function appPath(string $path = null): string
    {
        return Valkyrja\Support\Directory::appPath($path);
    }
}

if (! function_exists('configPath')) {
    /**
     * Helper function to get config path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    function configPath(string $path = null): string
    {
        return Valkyrja\Support\Directory::configPath($path);
    }
}

if (! function_exists('frameworkPath')) {
    /**
     * Helper function to get framework path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    function frameworkPath(string $path = null): string
    {
        return Valkyrja\Support\Directory::frameworkPath($path);
    }
}

if (! function_exists('publicPath')) {
    /**
     * Helper function to get public path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    function publicPath(string $path = null): string
    {
        return Valkyrja\Support\Directory::publicPath($path);
    }
}

if (! function_exists('resourcesPath')) {
    /**
     * Helper function to get resources path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    function resourcesPath(string $path = null): string
    {
        return Valkyrja\Support\Directory::resourcesPath($path);
    }
}

if (! function_exists('storagePath')) {
    /**
     * Helper function to get storage path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    function storagePath(string $path = null): string
    {
        return Valkyrja\Support\Directory::storagePath($path);
    }
}

if (! function_exists('testsPath')) {
    /**
     * Helper function to get tests path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    function testsPath(string $path = null): string
    {
        return Valkyrja\Support\Directory::testsPath($path);
    }
}

if (! function_exists('vendorPath')) {
    /**
     * Helper function to get vendor path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    function vendorPath(string $path = null): string
    {
        return Valkyrja\Support\Directory::vendorPath($path);
    }
}

if (! function_exists('dd')) {
    /**
     * Dump the passed variables and end the script.
     *
     * @param mixed
     *  The arguments to dump
     *
     * @return void
     */
    function dd(): void
    {
        var_dump(func_get_args());

        die(1);
    }
}
