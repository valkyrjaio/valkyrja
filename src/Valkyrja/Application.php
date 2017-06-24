<?php

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
use Valkyrja\Dispatcher\Dispatcher;
use Valkyrja\Events\Events;
use Valkyrja\Filesystem\Filesystem;
use Valkyrja\Http\JsonResponse;
use Valkyrja\Http\Kernel;
use Valkyrja\Http\RedirectResponse;
use Valkyrja\Http\Request;
use Valkyrja\Http\Response;
use Valkyrja\Http\ResponseBuilder;
use Valkyrja\Http\StatusCode;
use Valkyrja\Logger\Logger;
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
    public const VERSION = '0.7.1';

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
     * @return \Valkyrja\Application
     */
    public static function app(): self;

    /**
     * Get an environment variable.
     *
     * @param string $variable [optional] The variable to get
     * @param string $default  [optional] The default value to return
     *
     * @return mixed|\Valkyrja\Config\Env||config|Env
     */
    public static function env(string $variable = null, $default = null);

    /**
     * Get environment variables.
     *
     * @return \Valkyrja\Config\Env||config|Env
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
     * @param string $default [optional] The default value if the key is not found
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
     * @return \Valkyrja\Container\Container
     */
    public function container(): Container;

    /**
     * Get the dispatcher instance.
     *
     * @return \Valkyrja\Dispatcher\Dispatcher
     */
    public function dispatcher(): Dispatcher;

    /**
     * Get the events instance.
     *
     * @return \Valkyrja\Events\Events
     */
    public function events(): Events;

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
     * @return string
     */
    public function debug(): string;

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
     * @param int    $statusCode The status code to use
     * @param string $message    [optional] The Exception message to throw
     * @param array  $headers    [optional] The headers to send
     * @param int    $code       [optional] The Exception code
     *
     * @return void
     */
    public function abort(
        int $statusCode = StatusCode::NOT_FOUND,
        string $message = '',
        array $headers = [],
        int $code = 0
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
    public function redirectTo(
        string $uri = null,
        int $statusCode = StatusCode::FOUND,
        array $headers = []
    ): void;

    /**
     * Return the annotations instance from the container.
     *
     * @return \Valkyrja\Annotations\Annotations
     */
    public function annotations(): Annotations;

    /**
     * Return the client instance from the container.
     *
     * @return \Valkyrja\Client\Client
     */
    public function client(): Client;

    /**
     * Return the console instance from the container.
     *
     * @return \Valkyrja\Console\Console
     */
    public function console(): Console;

    /**
     * Return the console kernel instance from the container.
     *
     * @return \Valkyrja\Console\Kernel
     */
    public function consoleKernel(): ConsoleKernel;

    /**
     * Return the filesystem instance from the container.
     *
     * @return \Valkyrja\Filesystem\Filesystem
     */
    public function filesystem(): Filesystem;

    /**
     * Return the kernel instance from the container.
     *
     * @return \Valkyrja\Http\Kernel
     */
    public function kernel(): Kernel;

    /**
     * Return the logger instance from the container.
     *
     * @return \Valkyrja\Logger\Logger
     */
    public function logger(): Logger;

    /**
     * Return the path generator instance from the container.
     *
     * @return \Valkyrja\Path\PathGenerator
     */
    public function pathGenerator(): PathGenerator;

    /**
     * Return the path parser instance from the container.
     *
     * @return \Valkyrja\Path\PathParser
     */
    public function pathParser(): PathParser;

    /**
     * Return the request instance from the container.
     *
     * @return \Valkyrja\Http\Request
     */
    public function request(): Request;

    /**
     * Return the router instance from the container.
     *
     * @return \Valkyrja\Routing\Router
     */
    public function router(): Router;

    /**
     * Return a new response from the application.
     *
     * @param string $content    [optional] The content to set
     * @param int    $statusCode [optional] The status code to set
     * @param array  $headers    [optional] The headers to set
     *
     * @return \Valkyrja\Http\Response
     */
    public function response(
        string $content = '',
        int $statusCode = StatusCode::OK,
        array $headers = []
    ): Response;

    /**
     * Return a new json response from the application.
     *
     * @param array $data       [optional] An array of data
     * @param int   $statusCode [optional] The status code to set
     * @param array $headers    [optional] The headers to set
     *
     * @return \Valkyrja\Http\JsonResponse
     */
    public function json(
        array $data = [],
        int $statusCode = StatusCode::OK,
        array $headers = []
    ): JsonResponse;

    /**
     * Return a new redirect response from the application.
     *
     * @param string $uri        [optional] The URI to redirect to
     * @param int    $statusCode [optional] The response status code
     * @param array  $headers    [optional] An array of response headers
     *
     * @return \Valkyrja\Http\RedirectResponse
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
     * @param array  $parameters [optional] Any parameters to set for dynamic routes
     * @param int    $statusCode [optional] The response status code
     * @param array  $headers    [optional] An array of response headers
     *
     * @return \Valkyrja\Http\RedirectResponse
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
     * @return \Valkyrja\Http\ResponseBuilder
     */
    public function responseBuilder(): ResponseBuilder;

    /**
     * Return the session.
     *
     * @return \Valkyrja\Session\Session
     */
    public function session(): Session;

    /**
     * Helper function to get a new view.
     *
     * @param string $template  [optional] The template to use
     * @param array  $variables [optional] The variables to use
     *
     * @return \Valkyrja\View\View
     */
    public function view(string $template = '', array $variables = []): View;
}
