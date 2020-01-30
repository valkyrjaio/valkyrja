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

use Valkyrja\Annotations\Annotations;
use Valkyrja\Client\Client;
use Valkyrja\Console\Console;
use Valkyrja\Console\Kernel as ConsoleKernel;
use Valkyrja\Container\Container;
use Valkyrja\Crypt\Crypt;
use Valkyrja\Dispatcher\Dispatcher;
use Valkyrja\Env\Env;
use Valkyrja\Events\Events;
use Valkyrja\Exceptions\ExceptionHandler;
use Valkyrja\Filesystem\Filesystem;
use Valkyrja\Http\Enums\StatusCode;
use Valkyrja\Http\JsonResponse;
use Valkyrja\Http\Kernel;
use Valkyrja\Http\RedirectResponse;
use Valkyrja\Http\Request;
use Valkyrja\Http\Response;
use Valkyrja\Http\ResponseBuilder;
use Valkyrja\Logger\Logger;
use Valkyrja\Mail\Mail;
use Valkyrja\ORM\EntityManager;
use Valkyrja\Path\PathGenerator;
use Valkyrja\Path\PathParser;
use Valkyrja\Routing\Router;
use Valkyrja\Session\Session;
use Valkyrja\View\View;

/**
 * Interface Application.
 *
 * @author Melech Mizrachi
 */
interface Application
{
    /**
     * The Application framework version.
     *
     * @constant string
     */
    public const VERSION = '1.0';

    /**
     * Setup the application.
     *
     * @param array $config [optional] The config to use
     * @param bool  $force  [optional] Whether to force a setup
     *
     * @return void
     */
    public function setup(array $config = null, bool $force = false): void;

    /**
     * Get the application instance.
     *
     * @return Application
     */
    public static function app(): self;

    /**
     * Get an environment variable.
     *
     * @param string $key     [optional] The variable to get
     * @param string $default [optional] The default value to return
     *
     * @return mixed|Env||config|Env
     */
    public static function env(string $key = null, $default = null);

    /**
     * Get environment variables.
     *
     * @return Env||config|Env
     */
    public static function getEnv(): string;

    /**
     * Set the environment variables class.
     *
     * @param string $env [optional] The env file to use
     *
     * @return void
     */
    public static function setEnv(string $env = null): void;

    /**
     * Get the config.
     *
     * @param string $key     [optional] The key to get
     * @param string $default [optional] The default value if the key is not
     *                        found
     *
     * @return mixed
     */
    public function config(string $key = null, $default = null);

    /**
     * Add to the global config array.
     *
     * @param array $newConfig The new config to add
     *
     * @return void
     */
    public function addConfig(array $newConfig): void;

    /**
     * Get the container instance.
     *
     * @return Container
     */
    public function container(): Container;

    /**
     * Get the dispatcher instance.
     *
     * @return Dispatcher
     */
    public function dispatcher(): Dispatcher;

    /**
     * Get the events instance.
     *
     * @return Events
     */
    public function events(): Events;

    /**
     * Get the exception handler instance.
     *
     * @return ExceptionHandler
     */
    public function exceptionHandler(): ExceptionHandler;

    /**
     * Get the application version.
     *
     * @return string
     */
    public function version(): string;

    /**
     * Get the environment with which the application is running in.
     *
     * @return string
     */
    public function environment(): string;

    /**
     * Whether the application is running in debug mode or not.
     *
     * @return bool
     */
    public function debug(): bool;

    /**
     * Get whether the application is using a compiled version.
     *
     * @return bool
     */
    public function isCompiled(): bool;

    /**
     * Set the application as using compiled.
     *
     * @return void
     */
    public function setCompiled(): void;

    /**
     * Abort the application due to error.
     *
     * @param int      $statusCode The status code to use
     * @param string   $message    [optional] The Exception message to throw
     * @param array    $headers    [optional] The headers to send
     * @param int      $code       [optional] The Exception code
     * @param Response $response   [optional] The Response to send
     *
     * @return void
     */
    public function abort(
        int $statusCode = StatusCode::NOT_FOUND,
        string $message = '',
        array $headers = [],
        int $code = 0,
        Response $response = null
    ): void;

    /**
     * Redirect to a given uri, and abort the application.
     *
     * @param string $uri        [optional] The URI to redirect to
     * @param int    $statusCode [optional] The response status code
     * @param array  $headers    [optional] An array of response headers
     *
     * @return void
     */
    public function redirectTo(string $uri = null, int $statusCode = StatusCode::FOUND, array $headers = []): void;

    /**
     * Return the annotations instance from the container.
     *
     * @return Annotations
     */
    public function annotations(): Annotations;

    /**
     * Return the client instance from the container.
     *
     * @return Client
     */
    public function client(): Client;

    /**
     * Return the console instance from the container.
     *
     * @return Console
     */
    public function console(): Console;

    /**
     * Return the console kernel instance from the container.
     *
     * @return ConsoleKernel
     */
    public function consoleKernel(): ConsoleKernel;

    /**
     * Return the crypt instance from the container.
     *
     * @return Crypt
     */
    public function crypt(): Crypt;

    /**
     * Return the entity manager instance from the container.
     *
     * @return EntityManager
     */
    public function entityManager(): EntityManager;

    /**
     * Return the filesystem instance from the container.
     *
     * @return Filesystem
     */
    public function filesystem(): Filesystem;

    /**
     * Return the kernel instance from the container.
     *
     * @return Kernel
     */
    public function kernel(): Kernel;

    /**
     * Return the logger instance from the container.
     *
     * @return Logger
     */
    public function logger(): Logger;

    /**
     * Return the mail instance from the container.
     *
     * @return Mail
     */
    public function mail(): Mail;

    /**
     * Return the path generator instance from the container.
     *
     * @return PathGenerator
     */
    public function pathGenerator(): PathGenerator;

    /**
     * Return the path parser instance from the container.
     *
     * @return PathParser
     */
    public function pathParser(): PathParser;

    /**
     * Return the request instance from the container.
     *
     * @return Request
     */
    public function request(): Request;

    /**
     * Return the router instance from the container.
     *
     * @return Router
     */
    public function router(): Router;

    /**
     * Return a new response from the application.
     *
     * @param string $content    [optional] The content to set
     * @param int    $statusCode [optional] The status code to set
     * @param array  $headers    [optional] The headers to set
     *
     * @return Response
     */
    public function response(string $content = '', int $statusCode = StatusCode::OK, array $headers = []): Response;

    /**
     * Return a new json response from the application.
     *
     * @param array $data       [optional] An array of data
     * @param int   $statusCode [optional] The status code to set
     * @param array $headers    [optional] The headers to set
     *
     * @return JsonResponse
     */
    public function json(array $data = [], int $statusCode = StatusCode::OK, array $headers = []): JsonResponse;

    /**
     * Return a new redirect response from the application.
     *
     * @param string $uri        [optional] The URI to redirect to
     * @param int    $statusCode [optional] The response status code
     * @param array  $headers    [optional] An array of response headers
     *
     * @return RedirectResponse
     */
    public function redirect(
        string $uri = null,
        int $statusCode = StatusCode::FOUND,
        array $headers = []
    ): RedirectResponse;

    /**
     * Return a new redirect response from the application for a given route.
     *
     * @param string $route      The route to match
     * @param array  $parameters [optional] Any parameters to set for dynamic
     *                           routes
     * @param int    $statusCode [optional] The response status code
     * @param array  $headers    [optional] An array of response headers
     *
     * @return RedirectResponse
     */
    public function redirectRoute(
        string $route,
        array $parameters = [],
        int $statusCode = StatusCode::FOUND,
        array $headers = []
    ): RedirectResponse;

    /**
     * Return a new response from the application.
     *
     * @return ResponseBuilder
     */
    public function responseBuilder(): ResponseBuilder;

    /**
     * Return the session.
     *
     * @return Session
     */
    public function session(): Session;

    /**
     * Helper function to get a new view.
     *
     * @param string $template  [optional] The template to use
     * @param array  $variables [optional] The variables to use
     *
     * @return View
     */
    public function view(string $template = '', array $variables = []): View;
}
