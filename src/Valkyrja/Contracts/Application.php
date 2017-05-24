<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Contracts;

use Valkyrja\Contracts\Annotations\Annotations;
use Valkyrja\Contracts\Console\Console;
use Valkyrja\Contracts\Console\Kernel as ConsoleKernel;
use Valkyrja\Contracts\Container\Container;
use Valkyrja\Contracts\Dispatcher\Dispatcher;
use Valkyrja\Contracts\Events\Events;
use Valkyrja\Contracts\Http\Client;
use Valkyrja\Contracts\Http\JsonResponse;
use Valkyrja\Contracts\Http\Kernel;
use Valkyrja\Contracts\Http\RedirectResponse;
use Valkyrja\Contracts\Http\Request;
use Valkyrja\Contracts\Http\Response;
use Valkyrja\Contracts\Http\ResponseBuilder;
use Valkyrja\Contracts\Logger\Logger;
use Valkyrja\Contracts\Path\PathGenerator;
use Valkyrja\Contracts\Path\PathParser;
use Valkyrja\Contracts\Routing\Router;
use Valkyrja\Contracts\Session\Session;
use Valkyrja\Contracts\View\View;
use Valkyrja\Http\Enums\StatusCode;

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
    public const VERSION = '0.1.1';

    /**
     * Application constructor.
     *
     * @param array  $config [optional] The config to use
     */
    public function __construct(array $config = null);

    /**
     * Setup the application.
     *
     * @param array  $config [optional] The config to use
     * @param bool   $force  [optional] Whether to force a setup
     *
     * @return void
     */
    public function setup(array $config = null, bool $force = null): void;

    /**
     * Get the application instance.
     *
     * @return \Valkyrja\Contracts\Application
     */
    public static function app(): self;

    /**
     * Get environment variables.
     *
     * @param string $env [optional] The env file to use
     *
     * @return \Valkyrja\Contracts\Config\Env||config|Env
     */
    public static function env(string $env = null): string;

    /**
     * Get the config class instance.
     *
     * @return array
     */
    public function config(): array;

    /**
     * Get the container instance.
     *
     * @return \Valkyrja\Contracts\Container\Container
     */
    public function container(): Container;

    /**
     * Get the dispatcher instance.
     *
     * @return \Valkyrja\Contracts\Dispatcher\Dispatcher
     */
    public function dispatcher(): Dispatcher;

    /**
     * Get the events instance.
     *
     * @return \Valkyrja\Contracts\Events\Events
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
     * @return \Valkyrja\Contracts\Annotations\Annotations
     */
    public function annotations(): Annotations;

    /**
     * Return the client instance from the container.
     *
     * @return \Valkyrja\Contracts\Http\Client
     */
    public function client(): Client;

    /**
     * Return the console instance from the container.
     *
     * @return \Valkyrja\Contracts\Console\Console
     */
    public function console(): Console;

    /**
     * Return the console kernel instance from the container.
     *
     * @return \Valkyrja\Contracts\Console\Kernel
     */
    public function consoleKernel(): ConsoleKernel;

    /**
     * Return the kernel instance from the container.
     *
     * @return \Valkyrja\Contracts\Http\Kernel
     */
    public function kernel(): Kernel;

    /**
     * Return the logger instance from the container.
     *
     * @return \Valkyrja\Contracts\Logger\Logger
     */
    public function logger(): Logger;

    /**
     * Return the path generator instance from the container.
     *
     * @return \Valkyrja\Contracts\Path\PathGenerator
     */
    public function pathGenerator(): PathGenerator;

    /**
     * Return the path parser instance from the container.
     *
     * @return \Valkyrja\Contracts\Path\PathParser
     */
    public function pathParser(): PathParser;

    /**
     * Return the request instance from the container.
     *
     * @return \Valkyrja\Contracts\Http\Request
     */
    public function request(): Request;

    /**
     * Return the router instance from the container.
     *
     * @return \Valkyrja\Contracts\Routing\Router
     */
    public function router(): Router;

    /**
     * Return a new response from the application.
     *
     * @param string $content    [optional] The content to set
     * @param int    $statusCode [optional] The status code to set
     * @param array  $headers    [optional] The headers to set
     *
     * @return \Valkyrja\Contracts\Http\Response
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
     * @return \Valkyrja\Contracts\Http\JsonResponse
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
     * @return \Valkyrja\Contracts\Http\RedirectResponse
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
     * @return \Valkyrja\Contracts\Http\RedirectResponse
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
     * @return \Valkyrja\Contracts\Http\ResponseBuilder
     */
    public function responseBuilder(): ResponseBuilder;

    /**
     * Return the session.
     *
     * @return \Valkyrja\Contracts\Session\Session
     */
    public function session(): Session;

    /**
     * Helper function to get a new view.
     *
     * @param string $template  [optional] The template to use
     * @param array  $variables [optional] The variables to use
     *
     * @return \Valkyrja\Contracts\View\View
     */
    public function view(string $template = '', array $variables = []): View;
}
