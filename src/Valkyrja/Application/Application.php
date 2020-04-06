<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Application;

use Valkyrja\Annotation\Annotator;
use Valkyrja\Cache\Cache;
use Valkyrja\Client\Client;
use Valkyrja\Config\Config as ConfigModel;
use Valkyrja\Config\Config\Config;
use Valkyrja\Console\Console;
use Valkyrja\Console\Kernel as ConsoleKernel;
use Valkyrja\Container\Container;
use Valkyrja\Crypt\Crypt;
use Valkyrja\Dispatcher\Dispatcher;
use Valkyrja\Event\Events;
use Valkyrja\Exception\ExceptionHandler;
use Valkyrja\Filesystem\Filesystem;
use Valkyrja\Http\Enums\StatusCode;
use Valkyrja\Http\Exceptions\HttpException;
use Valkyrja\Http\Exceptions\HttpRedirectException;
use Valkyrja\Http\JsonResponse;
use Valkyrja\Http\RedirectResponse;
use Valkyrja\Http\Request;
use Valkyrja\Http\Response;
use Valkyrja\Http\ResponseFactory;
use Valkyrja\HttpKernel\Kernel;
use Valkyrja\Log\Logger;
use Valkyrja\Mail\Mail;
use Valkyrja\ORM\ORM;
use Valkyrja\Path\PathGenerator;
use Valkyrja\Path\PathParser;
use Valkyrja\Reflection\Reflector;
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
    public const VERSION = '1.0.0';

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
     * @param mixed  $default [optional] The default value to return
     *
     * @return mixed
     */
    public static function env(string $key = null, $default = null);

    /**
     * Get environment variables.
     *
     * @return string|null
     */
    public static function getEnv(): ?string;

    /**
     * Set the environment variables class.
     *
     * @param string $env [optional] The env file to use
     *
     * @return void
     */
    public static function setEnv(string $env = null): void;

    /**
     * Setup the application.
     *
     * @param string|null $config [optional] The config to use
     * @param bool        $force  [optional] Whether to force a setup
     *
     * @return void
     */
    public function setup(string $config = null, bool $force = false): void;

    /**
     * Add to the global config array.
     *
     * @param Config $config The config to add
     *
     * @return static
     */
    public function withConfig(Config $config): self;

    /**
     * Get the config.
     *
     * @param string $key     [optional] The key to get
     * @param mixed  $default [optional] The default value if the key is not found
     *
     * @return mixed|Config|null
     */
    public function config(string $key = null, $default = null);

    /**
     * Add to the global config array.
     *
     * @param ConfigModel $newConfig The new config to add
     * @param string      $key       The key to use
     *
     * @return void
     */
    public function addConfig(ConfigModel $newConfig, string $key): void;

    /**
     * Get the container instance.
     *
     * @return Container
     */
    public function container(): Container;

    /**
     * Set the container instance.
     *
     * @param Container $container The container instance
     *
     * @return static
     */
    public function setContainer(Container $container): self;

    /**
     * Get the dispatcher instance.
     *
     * @return Dispatcher
     */
    public function dispatcher(): Dispatcher;

    /**
     * Set the dispatcher instance.
     *
     * @param Dispatcher $dispatcher The dispatcher instance
     *
     * @return static
     */
    public function setDispatcher(Dispatcher $dispatcher): self;

    /**
     * Get the events instance.
     *
     * @return Events
     */
    public function events(): Events;

    /**
     * Set the events instance.
     *
     * @param Events $events The events instance
     *
     * @return static
     */
    public function setEvents(Events $events): self;

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
     * @throws HttpRedirectException
     *
     * @return void
     */
    public function redirectTo(string $uri = null, int $statusCode = StatusCode::FOUND, array $headers = []): void;

    /**
     * Return the annotator instance from the container.
     *
     * @return Annotator
     */
    public function annotator(): Annotator;

    /**
     * Return the cache instance from the container.
     *
     * @return Cache
     */
    public function cache(): Cache;

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
     * Return the ORM manager instance from the container.
     *
     * @return ORM
     */
    public function orm(): ORM;

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
     * Return the reflector instance from the container.
     *
     * @return Reflector
     */
    public function reflector(): Reflector;

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
     * @param string|null $content    [optional] The content to set
     * @param int|null    $statusCode [optional] The status code to set
     * @param array|null  $headers    [optional] The headers to set
     *
     * @return Response
     */
    public function response(string $content = null, int $statusCode = null, array $headers = null): Response;

    /**
     * Return a new json response from the application.
     *
     * @param array|null $data       [optional] An array of data
     * @param int|null   $statusCode [optional] The status code to set
     * @param array|null $headers    [optional] The headers to set
     *
     * @return JsonResponse
     */
    public function json(array $data = null, int $statusCode = null, array $headers = null): JsonResponse;

    /**
     * Return a new redirect response from the application.
     *
     * @param string|null $uri        [optional] The URI to redirect to
     * @param int|null    $statusCode [optional] The response status code
     * @param array|null  $headers    [optional] An array of response headers
     *
     * @return RedirectResponse
     */
    public function redirect(string $uri = null, int $statusCode = null, array $headers = null): RedirectResponse;

    /**
     * Return a new redirect response from the application for a given route.
     *
     * @param string|null $route      The route to match
     * @param array|null  $parameters [optional] Any parameters to set for dynamic routes
     * @param int|null    $statusCode [optional] The response status code
     * @param array|null  $headers    [optional] An array of response headers
     *
     * @return RedirectResponse
     */
    public function redirectRoute(
        string $route,
        array $parameters = null,
        int $statusCode = null,
        array $headers = null
    ): RedirectResponse;

    /**
     * Return a new response from the application.
     *
     * @return ResponseFactory
     */
    public function responseFactory(): ResponseFactory;

    /**
     * Return the session.
     *
     * @return Session
     */
    public function session(): Session;

    /**
     * Helper function to get a new view.
     *
     * @param string|null $template  [optional] The template to use
     * @param array       $variables [optional] The variables to use
     *
     * @return View
     */
    public function view(string $template = null, array $variables = []): View;
}
