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

namespace Valkyrja;

use Valkyrja\Annotation\Annotator;
use Valkyrja\Api\Api;
use Valkyrja\Application\Application;
use Valkyrja\Application\Applications\Valkyrja;
use Valkyrja\Auth\Auth;
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
use Valkyrja\Http\Exceptions\HttpException;
use Valkyrja\Console\Kernel as ConsoleKernel;
use Valkyrja\Http\Kernel;
use Valkyrja\Http\Response;
use Valkyrja\Log\Logger;
use Valkyrja\Mail\Mail;
use Valkyrja\ORM\ORM;
use Valkyrja\Reflection\Reflector;
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

use function func_get_args;
use function var_dump;

/**
 * Return the global $app variable.
 *
 * @return Application
 */
function app(): Application
{
    return Valkyrja::app();
}

/**
 * Abort the application due to error.
 *
 * @param int      $statusCode The status code to use
 * @param string   $message    [optional] The Exception message to throw
 * @param array    $headers    [optional] The headers to send
 * @param int      $code       [optional] The Exception code
 * @param Response $response   [optional] The Response to send
 *
 * @throws HttpException
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

/**
 * Abort the application due to error with a given response to send.
 *
 * @param Response $response The Response to send
 *
 * @throws HttpException
 *
 * @return void
 */
function abortResponse(Response $response): void
{
    app()->abort(0, '', [], 0, $response);
}

/**
 * Return the annotator instance from the container.
 *
 * @return Annotator
 */
function annotator(): Annotator
{
    return app()->annotator();
}

/**
 * Return the api instance from the container.
 *
 * @return Api
 */
function api(): Api
{
    return app()->container()->getSingleton(Api::class);
}

/**
 * Return the auth instance from the container.
 *
 * @return Auth
 */
function auth(): Auth
{
    return app()->container()->getSingleton(Auth::class);
}

/**
 * Return the cache instance from the container.
 *
 * @return Cache
 */
function cache(): Cache
{
    return app()->cache();
}

/**
 * Return the client instance from the container.
 *
 * @return Client
 */
function client(): Client
{
    return app()->client();
}

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

/**
 * Get console.
 *
 * @return Console
 */
function console(): Console
{
    return app()->console();
}

/**
 * Get container.
 *
 * @return Container
 */
function container(): Container
{
    return app()->container();
}

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

/**
 * Get events.
 *
 * @return Events
 */
function events(): Events
{
    return app()->events();
}

/**
 * Get filesystem.
 *
 * @return Filesystem
 */
function filesystem(): Filesystem
{
    return app()->filesystem();
}

/**
 * Get input.
 *
 * @return Input
 */
function input(): Input
{
    return container()->get(Input::class);
}

/**
 * Get kernel.
 *
 * @return Kernel
 */
function kernel(): Kernel
{
    return app()->kernel();
}

/**
 * Get console kernel.
 *
 * @return ConsoleKernel
 */
function consoleKernel(): ConsoleKernel
{
    return app()->consoleKernel();
}

/**
 * Get the crypt.
 *
 * @return Crypt
 */
function crypt(): Crypt
{
    return app()->crypt();
}

/**
 * Get logger.
 *
 * @return Logger
 */
function logger(): Logger
{
    return app()->logger();
}

/**
 * Get mail.
 *
 * @return Mail
 */
function mail(): Mail
{
    return app()->mail();
}

/**
 * Get the ORM manager.
 *
 * @return ORM
 */
function orm(): ORM
{
    return app()->orm();
}

/**
 * Get output.
 *
 * @return Output
 */
function output(): Output
{
    return container()->get(Output::class);
}

/**
 * Get reflector.
 *
 * @return Reflector
 */
function reflector(): Reflector
{
    return container()->get(Reflector::class);
}

/**
 * Get request.
 *
 * @return Request
 */
function request(): Request
{
    return app()->request();
}

/**
 * Get router.
 *
 * @return Router
 */
function router(): Router
{
    return app()->router();
}

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

/**
 * Get the response builder.
 *
 * @return ResponseFactory
 */
function responseBuilder(): ResponseFactory
{
    return app()->responseFactory();
}

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

/**
 * Return the session.
 *
 * @return Session
 */
function session(): Session
{
    return app()->session();
}

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
