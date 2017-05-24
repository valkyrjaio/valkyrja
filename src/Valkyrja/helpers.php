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
     * @throws \Valkyrja\Contracts\Http\Exceptions\HttpException
     *
     * @return void
     */
    function abort(
        int $statusCode = \Valkyrja\Http\Enums\StatusCode::NOT_FOUND,
        string $message = '',
        array $headers = [],
        int $code = 0
    ): void {
        app()->abort($statusCode, $message, $headers, $code);
    }
}

if (! function_exists('annotations')) {
    /**
     * Return the annotations instance from the container.
     *
     * @return \Valkyrja\Contracts\Annotations\Annotations
     */
    function annotations(): \Valkyrja\Contracts\Annotations\Annotations
    {
        return app()->annotations();
    }
}

if (! function_exists('client')) {
    /**
     * Return the client instance from the container.
     *
     * @return \Valkyrja\Contracts\Http\Client
     */
    function client(): \Valkyrja\Contracts\Http\Client
    {
        return app()->client();
    }
}

if (! function_exists('config')) {
    /**
     * Get config.
     *
     * @return array
     */
    function config(): array
    {
        return app()->config();
    }
}

if (! function_exists('console')) {
    /**
     * Get console.
     *
     * @return \Valkyrja\Contracts\Console\Console
     */
    function console(): Valkyrja\Contracts\Console\Console
    {
        return app()->console();
    }
}

if (! function_exists('container')) {
    /**
     * Get container.
     *
     * @return \Valkyrja\Contracts\Container\Container
     */
    function container(): Valkyrja\Contracts\Container\Container
    {
        return app()->container();
    }
}

if (! function_exists('env')) {
    /**
     * Get env.
     *
     * @return \Valkyrja\Config\Env|\config\Env
     */
    function env(): string
    {
        return Valkyrja\Application::env();
    }
}

if (! function_exists('events')) {
    /**
     * Get events.
     *
     * @return \Valkyrja\Contracts\Events\Events
     */
    function events(): Valkyrja\Contracts\Events\Events
    {
        return app()->events();
    }
}

if (! function_exists('input')) {
    /**
     * Get input.
     *
     * @return \Valkyrja\Contracts\Console\Input\Input
     */
    function input(): \Valkyrja\Contracts\Console\Input\Input
    {
        return container()->get(Valkyrja\Container\Enums\CoreComponent::INPUT);
    }
}

if (! function_exists('kernel')) {
    /**
     * Get kernel.
     *
     * @return \Valkyrja\Contracts\Http\Kernel
     */
    function kernel(): \Valkyrja\Contracts\Http\Kernel
    {
        return app()->kernel();
    }
}

if (! function_exists('consoleKernel')) {
    /**
     * Get console kernel.
     *
     * @return \Valkyrja\Contracts\Console\Kernel
     */
    function consoleKernel(): \Valkyrja\Contracts\Console\Kernel
    {
        return app()->consoleKernel();
    }
}

if (! function_exists('logger')) {
    /**
     * Get request.
     *
     * @return \Valkyrja\Contracts\Logger\Logger
     */
    function logger(): Valkyrja\Contracts\Logger\Logger
    {
        return app()->logger();
    }
}

if (! function_exists('output')) {
    /**
     * Get output.
     *
     * @return \Valkyrja\Contracts\Console\Output\Output
     */
    function output(): \Valkyrja\Contracts\Console\Output\Output
    {
        return container()->get(Valkyrja\Container\Enums\CoreComponent::OUTPUT);
    }
}

if (! function_exists('request')) {
    /**
     * Get request.
     *
     * @return \Valkyrja\Contracts\Http\Request
     */
    function request(): Valkyrja\Contracts\Http\Request
    {
        return app()->request();
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

if (! function_exists('route')) {
    /**
     * Get a route by name.
     *
     * @param string $name The name of the route to get
     *
     * @return \Valkyrja\Routing\Route
     */
    function route(string $name): Valkyrja\Routing\Route
    {
        return router()->route($name);
    }
}

if (! function_exists('routeUrl')) {
    /**
     * Get a route url by name.
     *
     * @param string $name     The name of the route to get
     * @param array  $data     [optional] The route data if dynamic
     * @param bool   $absolute [optional] Whether this url should be absolute
     *
     * @return string
     */
    function routeUrl(string $name, array $data = null, bool $absolute = null): string
    {
        return router()->routeUrl($name, $data, $absolute);
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
     * @param string $content    [optional] The content to set
     * @param int    $statusCode [optional] The status code to set
     * @param array  $headers    [optional] The headers to set
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    function response(
        string $content = '',
        int $statusCode = \Valkyrja\Http\Enums\StatusCode::OK,
        array $headers = []
    ): Valkyrja\Contracts\Http\Response {
        return app()->response($content, $statusCode, $headers);
    }
}

if (! function_exists('json')) {
    /**
     * Return a new json response from the application.
     *
     * @param array $data       [optional] An array of data
     * @param int   $statusCode [optional] The status code to set
     * @param array $headers    [optional] The headers to set
     *
     * @return \Valkyrja\Contracts\Http\JsonResponse
     */
    function json(
        array $data = [],
        int $statusCode = \Valkyrja\Http\Enums\StatusCode::OK,
        array $headers = []
    ): Valkyrja\Contracts\Http\JsonResponse {
        return app()->json($data, $statusCode, $headers);
    }
}

if (! function_exists('redirect')) {
    /**
     * Return a new redirect response from the application.
     *
     * @param string $uri        [optional] The URI to redirect to
     * @param int    $statusCode [optional] The response status code
     * @param array  $headers    [optional] An array of response headers
     *
     * @return \Valkyrja\Contracts\Http\RedirectResponse
     */
    function redirect(
        string $uri = null,
        int $statusCode = \Valkyrja\Http\Enums\StatusCode::FOUND,
        array $headers = []
    ): \Valkyrja\Contracts\Http\RedirectResponse {
        return app()->redirect($uri, $statusCode, $headers);
    }
}

if (! function_exists('redirectRoute')) {
    /**
     * Return a new redirect response from the application for a given route.
     *
     * @param string $route      The route to match
     * @param array  $parameters [optional] Any parameters to set for dynamic routes
     * @param int    $statusCode [optional] The response status code
     * @param array  $headers    [optional] An array of response headers
     *
     * @return \Valkyrja\Contracts\Http\RedirectResponse
     */
    function redirectRoute(
        string $route,
        array $parameters = [],
        int $statusCode = \Valkyrja\Http\Enums\StatusCode::FOUND,
        array $headers = []
    ): \Valkyrja\Contracts\Http\RedirectResponse {
        return app()->redirectRoute($route, $parameters, $statusCode, $headers);
    }
}

if (! function_exists('redirectTo')) {
    /**
     * Redirect to a given uri, and abort the application.
     *
     * @param string $uri        [optional] The URI to redirect to
     * @param int    $statusCode [optional] The response status code
     * @param array  $headers    [optional] An array of response headers
     *
     * @throws \Valkyrja\Http\Exceptions\HttpRedirectException
     *
     * @return void
     */
    function redirectTo(
        string $uri = null,
        int $statusCode = \Valkyrja\Http\Enums\StatusCode::FOUND,
        array $headers = []
    ): void {
        app()->redirectTo($uri, $statusCode, $headers);
    }
}

if (! function_exists('session')) {
    /**
     * Return the session.
     *
     * @return \Valkyrja\Contracts\Session\Session
     */
    function session(): \Valkyrja\Contracts\Session\Session
    {
        return app()->session();
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
     * Dump the passed variables and die.
     *
     * @param mixed
     *  The arguments to dump
     *
     * @return void
     */
    function dd(): void
    {
        /* @noinspection ForgottenDebugOutputInspection */
        var_dump(func_get_args());

        die(1);
    }
}
