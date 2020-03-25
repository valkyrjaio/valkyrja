<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Valkyrja\Annotation\Annotator;
use Valkyrja\Application\Application;
use Valkyrja\Application\Applications\Valkyrja;
use Valkyrja\Cache\Cache;
use Valkyrja\Client\Client;
use Valkyrja\Config\Config;
use Valkyrja\Console\Console;
use Valkyrja\Console\Input;
use Valkyrja\Console\Output;
use Valkyrja\Container\Container;
use Valkyrja\Crypt\Crypt;
use Valkyrja\Event\Events;
use Valkyrja\Filesystem\Filesystem;
use Valkyrja\Http\Enums\StatusCode;
use Valkyrja\Http\Kernel;
use Valkyrja\Http\Response;
use Valkyrja\Log\Logger;
use Valkyrja\Mail\Mail;
use Valkyrja\ORM\Manager;
use Valkyrja\Session\Session;
use Valkyrja\View\View;
use Valkyrja\Http\Exceptions\HttpRedirectException;
use Valkyrja\Http\JsonResponse;
use Valkyrja\Http\RedirectResponse;
use Valkyrja\Http\Request;
use Valkyrja\Http\ResponseFactory;
use Valkyrja\Routing\Route;
use Valkyrja\Routing\Router;
use Valkyrja\Support\Directory as ValkyrjaDirectory;

if (! function_exists('app')) {
    /**
     * Return the global $app variable.
     *
     * @return Application
     */
    function app(): Application
    {
        return Valkyrja::app();
    }
}

if (! function_exists('abort')) {
    /**
     * Abort the application due to error.
     *
     * @param int      $statusCode The status code to use
     * @param string   $message    [optional] The Exception message to throw
     * @param array    $headers    [optional] The headers to send
     * @param int      $code       [optional] The Exception code
     * @param Response $response   [optional] The Response to send
     *
     * @throws \Valkyrja\Http\Exceptions\HttpException
     *
     * @return void
     */
    function abort(
        int $statusCode = StatusCode::NOT_FOUND,
        string $message = '',
        array $headers = [],
        int $code = 0,
        Response $response = null
    ): void {
        app()->abort($statusCode, $message, $headers, $code, $response);
    }
}

if (! function_exists('abortResponse')) {
    /**
     * Abort the application due to error with a given response to send.
     *
     * @param Response $response The Response to send
     *
     * @throws \Valkyrja\Http\Exceptions\HttpException
     *
     * @return void
     */
    function abortResponse(Response $response): void
    {
        app()->abort(0, '', [], 0, $response);
    }
}

if (! function_exists('annotator')) {
    /**
     * Return the annotator instance from the container.
     *
     * @return Annotator
     */
    function annotator(): Annotator
    {
        return app()->annotator();
    }
}

if (! function_exists('cache')) {
    /**
     * Return the cache instance from the container.
     *
     * @return Cache
     */
    function cache(): Cache
    {
        return app()->cache();
    }
}

if (! function_exists('client')) {
    /**
     * Return the client instance from the container.
     *
     * @return Client
     */
    function client(): Client
    {
        return app()->client();
    }
}

if (! function_exists('config')) {
    /**
     * Get the config.
     *
     * @param string $key     [optional] The key to get
     * @param mixed  $default [optional] The default value if the key is not found
     *
     * @return mixed|Config|null
     */
    function config(string $key = null, $default = null)
    {
        return app()->config($key, $default);
    }
}

if (! function_exists('console')) {
    /**
     * Get console.
     *
     * @return Console
     */
    function console(): Console
    {
        return app()->console();
    }
}

if (! function_exists('container')) {
    /**
     * Get container.
     *
     * @return Container
     */
    function container(): Container
    {
        return app()->container();
    }
}

if (! function_exists('env')) {
    /**
     * Get an environment variable.
     *
     * @param string $key     [optional] The variable to get
     * @param mixed  $default [optional] The default value to return
     *
     * @return mixed
     */
    function env(string $key = null, $default = null)
    {
        // Does not use the app() helper due to the self::$instance property
        // that Valkyrja::app() relies on has not been set yet when
        // this helper may be used.
        return Valkyrja::env($key, $default);
    }
}

if (! function_exists('events')) {
    /**
     * Get events.
     *
     * @return Events
     */
    function events(): Events
    {
        return app()->events();
    }
}

if (! function_exists('filesystem')) {
    /**
     * Get filesystem.
     *
     * @return Filesystem
     */
    function filesystem(): Filesystem
    {
        return app()->filesystem();
    }
}

if (! function_exists('input')) {
    /**
     * Get input.
     *
     * @return Input
     */
    function input(): Input
    {
        return container()->get(Input::class);
    }
}

if (! function_exists('kernel')) {
    /**
     * Get kernel.
     *
     * @return Kernel
     */
    function kernel(): Kernel
    {
        return app()->kernel();
    }
}

if (! function_exists('consoleKernel')) {
    /**
     * Get console kernel.
     *
     * @return \Valkyrja\Console\Kernel
     */
    function consoleKernel(): \Valkyrja\Console\Kernel
    {
        return app()->consoleKernel();
    }
}

if (! function_exists('vcrypt')) {
    /**
     * Get the crypt.
     *
     * @return Crypt
     */
    function vcrypt(): Crypt
    {
        return app()->crypt();
    }
}

if (! function_exists('logger')) {
    /**
     * Get logger.
     *
     * @return Logger
     */
    function logger(): Logger
    {
        return app()->logger();
    }
}

if (! function_exists('mail')) {
    /**
     * Get mail.
     *
     * @return Mail
     */
    function mail(): Mail
    {
        return app()->mail();
    }
}

if (! function_exists('orm')) {
    /**
     * Get the ORM manager.
     *
     * @return Manager
     */
    function orm(): Manager
    {
        return app()->orm();
    }
}

if (! function_exists('output')) {
    /**
     * Get output.
     *
     * @return Output
     */
    function output(): Output
    {
        return container()->get(Output::class);
    }
}

if (! function_exists('reflector')) {
    /**
     * Get reflector.
     *
     * @return \Valkyrja\Reflection\Reflector
     */
    function reflector(): \Valkyrja\Reflection\Reflector
    {
        return container()->get(\Valkyrja\Reflection\Reflector::class);
    }
}

if (! function_exists('request')) {
    /**
     * Get request.
     *
     * @return Request
     */
    function request(): Request
    {
        return app()->request();
    }
}

if (! function_exists('router')) {
    /**
     * Get router.
     *
     * @return Router
     */
    function router(): Router
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
     * @return Route
     */
    function route(string $name): Route
    {
        return router()->getRoute($name);
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
        return router()->getUrl($name, $data, $absolute);
    }
}

if (! function_exists('responseBuilder')) {
    /**
     * Get the response builder.
     *
     * @return ResponseFactory
     */
    function responseBuilder(): ResponseFactory
    {
        return app()->responseFactory();
    }
}

if (! function_exists('response')) {
    /**
     * Return a new response from the application.
     *
     * @param string|null $content    [optional] The content to set
     * @param int|null    $statusCode [optional] The status code to set
     * @param array|null  $headers    [optional] The headers to set
     *
     * @return Response
     */
    function response(string $content = null, int $statusCode = null, array $headers = null): Response
    {
        return app()->response($content, $statusCode, $headers);
    }
}

if (! function_exists('json')) {
    /**
     * Return a new json response from the application.
     *
     * @param array|null $data       [optional] An array of data
     * @param int|null   $statusCode [optional] The status code to set
     * @param array|null $headers    [optional] The headers to set
     *
     * @return JsonResponse
     */
    function json(array $data = null, int $statusCode = null, array $headers = null): JsonResponse
    {
        return app()->json($data, $statusCode, $headers);
    }
}

if (! function_exists('redirect')) {
    /**
     * Return a new redirect response from the application.
     *
     * @param string|null $uri        [optional] The URI to redirect to
     * @param int|null    $statusCode [optional] The response status code
     * @param array|null  $headers    [optional] An array of response headers
     *
     * @return RedirectResponse
     */
    function redirect(string $uri = null, int $statusCode = null, array $headers = null): RedirectResponse
    {
        return app()->redirect($uri, $statusCode, $headers);
    }
}

if (! function_exists('redirectRoute')) {
    /**
     * Return a new redirect response from the application for a given route.
     *
     * @param string     $route      The route to match
     * @param array|null $parameters [optional] Any parameters to set for dynamic routes
     * @param int|null   $statusCode [optional] The response status code
     * @param array|null $headers    [optional] An array of response headers
     *
     * @return RedirectResponse
     */
    function redirectRoute(
        string $route,
        array $parameters = null,
        int $statusCode = null,
        array $headers = null
    ): RedirectResponse {
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
     * @throws HttpRedirectException
     *
     * @return void
     */
    function redirectTo(
        string $uri = null,
        int $statusCode = StatusCode::FOUND,
        array $headers = []
    ): void {
        app()->redirectTo($uri, $statusCode, $headers);
    }
}

if (! function_exists('session')) {
    /**
     * Return the session.
     *
     * @return Session
     */
    function session(): Session
    {
        return app()->session();
    }
}

if (! function_exists('view')) {
    /**
     * Helper function to get a new view.
     *
     * @param string|null $template  [optional] The template to use
     * @param array       $variables [optional] The variables to use
     *
     * @return View
     */
    function view(string $template = null, array $variables = []): View
    {
        return app()->view($template, $variables);
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
        var_dump(func_get_args());

        die(1);
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
        return ValkyrjaDirectory::basePath($path);
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
        return ValkyrjaDirectory::appPath($path);
    }
}

if (! function_exists('bootstrapPath')) {
    /**
     * Helper function to get bootstrap path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    function bootstrapPath(string $path = null): string
    {
        return ValkyrjaDirectory::bootstrapPath($path);
    }
}

if (! function_exists('envPath')) {
    /**
     * Helper function to get env path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    function envPath(string $path = null): string
    {
        return ValkyrjaDirectory::envPath($path);
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
        return ValkyrjaDirectory::configPath($path);
    }
}

if (! function_exists('commandsPath')) {
    /**
     * Helper function to get commands path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    function commandsPath(string $path = null): string
    {
        return ValkyrjaDirectory::commandsPath($path);
    }
}

if (! function_exists('eventsPath')) {
    /**
     * Helper function to get events path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    function eventsPath(string $path = null): string
    {
        return ValkyrjaDirectory::eventsPath($path);
    }
}

if (! function_exists('routesPath')) {
    /**
     * Helper function to get routes path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    function routesPath(string $path = null): string
    {
        return ValkyrjaDirectory::routesPath($path);
    }
}

if (! function_exists('servicesPath')) {
    /**
     * Helper function to get services path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    function servicesPath(string $path = null): string
    {
        return ValkyrjaDirectory::servicesPath($path);
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
        return ValkyrjaDirectory::publicPath($path);
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
        return ValkyrjaDirectory::resourcesPath($path);
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
        return ValkyrjaDirectory::storagePath($path);
    }
}

if (! function_exists('frameworkStoragePath')) {
    /**
     * Helper function to get framework storage path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    function frameworkStoragePath(string $path = null): string
    {
        return ValkyrjaDirectory::frameworkStoragePath($path);
    }
}

if (! function_exists('cachePath')) {
    /**
     * Helper function to get cache path.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    function cachePath(string $path = null): string
    {
        return ValkyrjaDirectory::cachePath($path);
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
        return ValkyrjaDirectory::testsPath($path);
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
        return ValkyrjaDirectory::vendorPath($path);
    }
}
