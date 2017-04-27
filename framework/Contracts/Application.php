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

use Valkyrja\Config\Config;
use Valkyrja\Contracts\Config\Env;
use Valkyrja\Contracts\Container\Container;
use Valkyrja\Contracts\Events\Events;
use Valkyrja\Contracts\Http\JsonResponse;
use Valkyrja\Contracts\Http\RedirectResponse;
use Valkyrja\Contracts\Http\Request;
use Valkyrja\Contracts\Http\Response;
use Valkyrja\Contracts\Http\ResponseBuilder;
use Valkyrja\Contracts\Logger\Logger;
use Valkyrja\Contracts\Routing\Router;
use Valkyrja\Contracts\View\View;
use Valkyrja\Http\ResponseCode;

/**
 * Interface Application
 *
 * @package Valkyrja\Contracts
 *
 * @author  Melech Mizrachi
 */
interface Application
{
    /**
     * The Application framework version.
     *
     * @constant string
     */
    const VERSION = 'Valkyrja (1.0.0 Alpha)';

    /**
     * Application constructor.
     *
     * @param \Valkyrja\Contracts\Container\Container $container The container to use
     * @param \Valkyrja\Contracts\Events\Events       $events    The events to use
     * @param \Valkyrja\Config\Config                 $config    The config to use
     */
    public function __construct(Container $container, Events $events, Config $config);

    /**
     * Get the application instance.
     *
     * @return \Valkyrja\Contracts\Application
     */
    public static function app(): self;

    /**
     * Get the container instance.
     *
     * @return \Valkyrja\Contracts\Container\Container
     */
    public function container(): Container;

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
     * Get the config class instance.
     *
     * @return \Valkyrja\Config\Config|\config\Config
     */
    public function config(): Config;

    /**
     * Get environment variables.
     *
     * @return \Valkyrja\Contracts\Config\Env||config|Env
     */
    public function env(): Env;

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
     * Is twig enabled?
     *
     * @return bool
     */
    public function isTwigEnabled(): bool;

    /**
     * Set the timezone for the application process.
     *
     * @return void
     */
    public function setTimezone(): void;

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
        int $statusCode = ResponseCode::HTTP_NOT_FOUND,
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
        int $statusCode = ResponseCode::HTTP_FOUND,
        array $headers = []
    ): void;

    /**
     * Handle a request.
     *
     * @param \Valkyrja\Contracts\Http\Request $request The request
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function handle(Request $request): Response;

    /**
     * Run the application.
     *
     * @return void
     */
    public function run(): void;

    /**
     * Register a service provider.
     *
     * @param string $serviceProvider The service provider
     *
     * @return void
     */
    public function register(string $serviceProvider): void;

    /**
     * Return the logger instance from the container.
     *
     * @return \Valkyrja\Contracts\Logger\Logger
     */
    public function logger(): Logger;

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
        int $statusCode = ResponseCode::HTTP_OK,
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
        int $statusCode = ResponseCode::HTTP_OK,
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
        int $statusCode = ResponseCode::HTTP_FOUND,
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
        int $statusCode = ResponseCode::HTTP_FOUND,
        array $headers = []
    ): RedirectResponse;

    /**
     * Return a new response from the application.
     *
     * @return \Valkyrja\Contracts\Http\ResponseBuilder
     */
    public function responseBuilder(): ResponseBuilder;

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
