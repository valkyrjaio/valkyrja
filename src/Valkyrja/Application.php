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

use Valkyrja\Config\Config;
use Valkyrja\Container\Enums\CoreComponent;
use Valkyrja\Contracts\Annotations\Annotations;
use Valkyrja\Contracts\Application as ApplicationContract;
use Valkyrja\Contracts\Config\Env;
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
use Valkyrja\Debug\Debug;
use Valkyrja\Exceptions\InvalidContainerImplementation;
use Valkyrja\Exceptions\InvalidDispatcherImplementation;
use Valkyrja\Exceptions\InvalidEventsImplementation;
use Valkyrja\Http\Enums\StatusCode;
use Valkyrja\Http\Exceptions\HttpException;
use Valkyrja\Http\Exceptions\HttpRedirectException;

/**
 * Class Application.
 *
 * @author Melech Mizrachi
 */
class Application implements ApplicationContract
{
    /**
     * Get the instance of the application.
     *
     * @var \Valkyrja\Contracts\Application
     */
    protected static $app;

    /**
     * Whether the application was setup.
     *
     * @var bool
     */
    protected static $setup = false;

    /**
     * Application config.
     *
     * @var \Valkyrja\Config\Config
     */
    protected static $config;

    /**
     * Get the instance of the container.
     *
     * @var \Valkyrja\Contracts\Container\Container
     */
    protected static $container;

    /**
     * Get the instance of the dispatcher.
     *
     * @var \Valkyrja\Contracts\Dispatcher\Dispatcher
     */
    protected static $dispatcher;

    /**
     * Get the instance of the events.
     *
     * @var \Valkyrja\Contracts\Events\Events
     */
    protected static $events;

    /**
     * Is the app using a compiled version?
     *
     * @var bool
     */
    protected $isCompiled = false;

    /**
     * Application constructor.
     *
     * @param \Valkyrja\Config\Config $config The config to use
     *
     * @throws \Valkyrja\Exceptions\InvalidContainerImplementation
     * @throws \Valkyrja\Exceptions\InvalidDispatcherImplementation
     * @throws \Valkyrja\Exceptions\InvalidEventsImplementation
     */
    public function __construct(Config $config)
    {
        $this->setup($config);
    }

    /**
     * Setup the application.
     *
     * @param \Valkyrja\Config\Config $config The config
     * @param bool                    $force  Whether to force a setup
     *
     * @throws \Valkyrja\Exceptions\InvalidContainerImplementation
     * @throws \Valkyrja\Exceptions\InvalidDispatcherImplementation
     * @throws \Valkyrja\Exceptions\InvalidEventsImplementation
     *
     * @return void
     */
    public function setup(Config $config, bool $force = null): void
    {
        // If the application was already setup, no need to do it again
        if (self::$setup && ! $force) {
            return;
        }

        // Avoid re-setting up the app later
        self::$setup = true;

        // If the VALKYRJA_START constant hasn't already been set
        if (! defined('VALKYRJA_START')) {
            // Set a global constant for when the framework started
            define('VALKYRJA_START', microtime(true));
        }

        // Set the app static
        self::$app = $this;
        // Set the config within the application
        self::$config = $config;

        // Bootstrap debug capabilities
        $this->bootstrapDebug();
        // Bootstrap core functionality
        $this->bootstrapCore();
        // Bootstrap the container
        $this->bootstrapContainer();
        // Bootstrap setup
        $this->bootstrapSetup();
        // Bootstrap the timezone
        $this->bootstrapTimezone();
    }

    /**
     * Bootstrap debug capabilities.
     *
     * @return void
     */
    protected function bootstrapDebug(): void
    {
        // If debug is on, enable debug handling
        if (self::$config->app->debug) {
            // Debug to output exceptions
            Debug::enable(E_ALL, self::$config->app->debug);
        }
    }

    /**
     * Bootstrap core functionality.
     *
     * @throws \Valkyrja\Exceptions\InvalidContainerImplementation
     * @throws \Valkyrja\Exceptions\InvalidDispatcherImplementation
     * @throws \Valkyrja\Exceptions\InvalidEventsImplementation
     *
     * @return void
     */
    protected function bootstrapCore(): void
    {
        // The events class to use from the config
        $eventsImpl = self::$config->app->events;
        // The container class to use from the config
        $containerImpl = self::$config->app->container;
        // The dispatcher class to use from the config
        $dispatcherImpl = self::$config->app->dispatcher;

        // Set the events to a new instance of the events implementation
        self::$events = new $eventsImpl($this);

        // If the events implementation specified does not adhere to the events contract
        if (! self::$events instanceof Events) {
            throw new InvalidEventsImplementation('Invalid Events implementation');
        }

        // Set the container to a new instance of the container implementation
        self::$container = new $containerImpl($this, self::$events);

        // If the container implementation specified does not adhere to the container contract
        if (! self::$container instanceof Container) {
            throw new InvalidContainerImplementation('Invalid Container implementation');
        }

        // Set the dispatcher to a new instance of the dispatcher implementation
        self::$dispatcher = new $dispatcherImpl(self::$container, self::$events);

        // If the dispatcher implementation specified does not adhere to the dispatcher contract
        if (! self::$dispatcher instanceof Dispatcher) {
            throw new InvalidDispatcherImplementation('Invalid Dispatcher implementation');
        }
    }

    /**
     * Bootstrap the container.
     *
     * @return void
     */
    protected function bootstrapContainer(): void
    {
        // Set the application instance in the container
        self::$container->singleton(CoreComponent::APP, $this);
        // Set the events instance in the container
        self::$container->singleton(CoreComponent::ENV, self::$config->env);
        // Set the events instance in the container
        self::$container->singleton(CoreComponent::CONFIG, self::$config);
        // Set the container instance in the container
        self::$container->singleton(CoreComponent::CONTAINER, self::$container);
        // Set the dispatcher instance in the dispatcher
        self::$container->singleton(CoreComponent::DISPATCHER, self::$dispatcher);
        // Set the events instance in the container
        self::$container->singleton(CoreComponent::EVENTS, self::$events);
    }

    /**
     * Bootstrap main components setup.
     *
     * @return void
     */
    protected function bootstrapSetup(): void
    {
        // Setup the container
        // NOTE: Not done in container construct to avoid container()
        // helper returning null self::$container
        self::$container->setup();
        // Setup the events
        // NOTE: Not done in events construct to avoid container dependency
        // not existing within setup (for ListenerAnnotations)
        self::$events->setup();
    }

    /**
     * Bootstrap the timezone.
     *
     * @return void
     */
    protected function bootstrapTimezone(): void
    {
        date_default_timezone_set($this->config()->app->timezone ?? 'UTC');
    }

    /**
     * Get the application instance.
     *
     * @return \Valkyrja\Contracts\Application
     */
    public static function app(): ApplicationContract
    {
        return self::$app;
    }

    /**
     * Get the container instance.
     *
     * @return \Valkyrja\Contracts\Container\Container
     */
    public function container(): Container
    {
        return self::$container;
    }

    /**
     * Get the dispatcher instance.
     *
     * @return \Valkyrja\Contracts\Dispatcher\Dispatcher
     */
    public function dispatcher(): Dispatcher
    {
        return self::$dispatcher;
    }

    /**
     * Get the events instance.
     *
     * @return \Valkyrja\Contracts\Events\Events
     */
    public function events(): Events
    {
        return self::$events;
    }

    /**
     * Get the application version.
     *
     * @return string
     */
    public function version(): string
    {
        return static::VERSION;
    }

    /**
     * Get the config class instance.
     *
     * @return \Valkyrja\Config\Config|\config\Config
     */
    public function config(): Config
    {
        return self::$config;
    }

    /**
     * Get environment variables.
     *
     * @return \Valkyrja\Contracts\Config\Env||config|Env
     */
    public function env(): Env
    {
        return $this->config()->env;
    }

    /**
     * Get the environment with which the application is running in.
     *
     * @return string
     */
    public function environment(): string
    {
        return $this->config()->app->env ?? 'production';
    }

    /**
     * Whether the application is running in debug mode or not.
     *
     * @return string
     */
    public function debug(): string
    {
        return $this->config()->app->debug ?? false;
    }

    /**
     * Get whether the application is using a compiled version.
     *
     * @return bool
     */
    public function isCompiled(): bool
    {
        return $this->isCompiled;
    }

    /**
     * Set the application as using compiled.
     *
     * @return void
     */
    public function setCompiled(): void
    {
        $this->isCompiled = true;
    }

    /**
     * Abort the application due to error.
     *
     * @param int    $statusCode The status code to use
     * @param string $message    [optional] The Exception message to throw
     * @param array  $headers    [optional] The headers to send
     * @param int    $code       [optional] The Exception code
     *
     * @throws \Valkyrja\Http\Exceptions\HttpException
     *
     * @return void
     */
    public function abort(
        int $statusCode = StatusCode::NOT_FOUND,
        string $message = '',
        array $headers = [],
        int $code = 0
    ): void {
        throw new HttpException($statusCode, $message, null, $headers, $code);
    }

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
    public function redirectTo(
        string $uri = null,
        int $statusCode = StatusCode::FOUND,
        array $headers = []
    ): void {
        throw new HttpRedirectException($statusCode, $uri, null, $headers, 0);
    }

    /**
     * Return the annotations instance from the container.
     *
     * @return \Valkyrja\Contracts\Annotations\Annotations
     */
    public function annotations(): Annotations
    {
        return $this->container()->get(Annotations::class);
    }

    /**
     * Return the client instance from the container.
     *
     * @return \Valkyrja\Contracts\Http\Client
     */
    public function client(): Client
    {
        return $this->container()->get(Client::class);
    }

    /**
     * Return the console instance from the container.
     *
     * @return \Valkyrja\Contracts\Console\Console
     */
    public function console(): Console
    {
        return $this->container()->get(Console::class);
    }

    /**
     * Return the console kernel instance from the container.
     *
     * @return \Valkyrja\Contracts\Console\Kernel
     */
    public function consoleKernel(): ConsoleKernel
    {
        return $this->container()->get(ConsoleKernel::class);
    }

    /**
     * Return the kernel instance from the container.
     *
     * @return \Valkyrja\Contracts\Http\Kernel
     */
    public function kernel(): Kernel
    {
        return $this->container()->get(Kernel::class);
    }

    /**
     * Return the logger instance from the container.
     *
     * @return \Valkyrja\Contracts\Logger\Logger
     */
    public function logger(): Logger
    {
        return $this->container()->get(Logger::class);
    }

    /**
     * Return the path generator instance from the container.
     *
     * @return \Valkyrja\Contracts\Path\PathGenerator
     */
    public function pathGenerator(): PathGenerator
    {
        return $this->container()->get(PathGenerator::class);
    }

    /**
     * Return the path parser instance from the container.
     *
     * @return \Valkyrja\Contracts\Path\PathParser
     */
    public function pathParser(): PathParser
    {
        return $this->container()->get(PathParser::class);
    }

    /**
     * Return the request instance from the container.
     *
     * @return \Valkyrja\Contracts\Http\Request
     */
    public function request(): Request
    {
        return $this->container()->get(Request::class);
    }

    /**
     * Return the router instance from the container.
     *
     * @return \Valkyrja\Contracts\Routing\Router
     */
    public function router(): Router
    {
        return $this->container()->get(Router::class);
    }

    /**
     * Return a new response from the application.
     *
     * @param string $content    [optional] The content to set
     * @param int    $statusCode [optional] The status code to set
     * @param array  $headers    [optional] The headers to set
     *
     * @throws \InvalidArgumentException
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function response(
        string $content = '',
        int $statusCode = StatusCode::OK,
        array $headers = []
    ): Response {
        /** @var Response $response */
        $response = $this->container()->get(Response::class);

        if (func_num_args() === 0) {
            return $response;
        }

        return $response->create($content, $statusCode, $headers);
    }

    /**
     * Return a new json response from the application.
     *
     * @param array $data       [optional] An array of data
     * @param int   $statusCode [optional] The status code to set
     * @param array $headers    [optional] The headers to set
     *
     * @throws \InvalidArgumentException
     *
     * @return \Valkyrja\Contracts\Http\JsonResponse
     */
    public function json(
        array $data = [],
        int $statusCode = StatusCode::OK,
        array $headers = []
    ): JsonResponse {
        /** @var JsonResponse $response */
        $response = $this->container()->get(JsonResponse::class);

        if (func_num_args() === 0) {
            return $response;
        }

        return $response->createJson('', $statusCode, $headers, $data);
    }

    /**
     * Return a new json response from the application.
     *
     * @param string $uri        [optional] The URI to redirect to
     * @param int    $statusCode [optional] The response status code
     * @param array  $headers    [optional] An array of response headers
     *
     * @throws \InvalidArgumentException
     * @throws \Valkyrja\Http\Exceptions\InvalidStatusCodeException
     *
     * @return \Valkyrja\Contracts\Http\RedirectResponse
     */
    public function redirect(
        string $uri = null,
        int $statusCode = StatusCode::FOUND,
        array $headers = []
    ): RedirectResponse {
        /** @var RedirectResponse $response */
        $response = $this->container()->get(RedirectResponse::class);

        if (func_num_args() === 0) {
            return $response;
        }

        return $response->createRedirect($uri, $statusCode, $headers);
    }

    /**
     * Return a new redirect response from the application for a given route.
     *
     * @param string $route      The route to match
     * @param array  $parameters [optional] Any parameters to set for dynamic routes
     * @param int    $statusCode [optional] The response status code
     * @param array  $headers    [optional] An array of response headers
     *
     * @throws \InvalidArgumentException
     * @throws \Valkyrja\Http\Exceptions\InvalidStatusCodeException
     *
     * @return \Valkyrja\Contracts\Http\RedirectResponse
     */
    public function redirectRoute(
        string $route,
        array $parameters = [],
        int $statusCode = StatusCode::FOUND,
        array $headers = []
    ): RedirectResponse {
        // Get the uri from the router using the route and parameters
        $uri = $this->router()->routeUrl($route, $parameters);

        return $this->redirect($uri, $statusCode, $headers);
    }

    /**
     * Return a new response from the application.
     *
     * @return \Valkyrja\Contracts\Http\ResponseBuilder
     */
    public function responseBuilder(): ResponseBuilder
    {
        return $this->container()->get(ResponseBuilder::class);
    }

    /**
     * Return the session.
     *
     * @return \Valkyrja\Contracts\Session\Session
     */
    public function session(): Session
    {
        return $this->container()->get(Session::class);
    }

    /**
     * Helper function to get a new view.
     *
     * @param string $template  [optional] The template to use
     * @param array  $variables [optional] The variables to use
     *
     * @return \Valkyrja\Contracts\View\View
     */
    public function view(string $template = '', array $variables = []): View
    {
        return $this->container()->get(
            View::class,
            [
                $template,
                $variables,
            ]
        );
    }
}
