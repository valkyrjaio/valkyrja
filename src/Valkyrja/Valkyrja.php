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
use Valkyrja\Config\Env;
use Valkyrja\Console\Console;
use Valkyrja\Console\Kernel as ConsoleKernel;
use Valkyrja\Container\Container;
use Valkyrja\Debug\Debug;
use Valkyrja\Dispatcher\Dispatcher;
use Valkyrja\Events\Events;
use Valkyrja\Exceptions\InvalidContainerImplementation;
use Valkyrja\Exceptions\InvalidDispatcherImplementation;
use Valkyrja\Exceptions\InvalidEventsImplementation;
use Valkyrja\Filesystem\Filesystem;
use Valkyrja\Http\Exceptions\HttpException;
use Valkyrja\Http\Exceptions\HttpRedirectException;
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
use Valkyrja\Support\Directory;
use Valkyrja\View\View;

/**
 * Class Application.
 *
 * @author Melech Mizrachi
 */
class Valkyrja implements Application
{
    /**
     * Get the instance of the application.
     *
     * @var \Valkyrja\Application
     */
    protected static $app;

    /**
     * Whether the application was setup.
     *
     * @var bool
     */
    protected static $setup = false;

    /**
     * Application env.
     *
     * @var \Valkyrja\Config\Env|\config\Env
     */
    protected static $env;

    /**
     * Application config.
     *
     * @var array
     */
    protected static $config;

    /**
     * Get the instance of the container.
     *
     * @var \Valkyrja\Container\Container
     */
    protected static $container;

    /**
     * Get the instance of the dispatcher.
     *
     * @var \Valkyrja\Dispatcher\Dispatcher
     */
    protected static $dispatcher;

    /**
     * Get the instance of the events.
     *
     * @var \Valkyrja\Events\Events
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
     * @param array $config [optional] The config to use
     *
     * @throws \Valkyrja\Exceptions\InvalidContainerImplementation
     * @throws \Valkyrja\Exceptions\InvalidDispatcherImplementation
     * @throws \Valkyrja\Exceptions\InvalidEventsImplementation
     */
    public function __construct(array $config = null)
    {
        $this->setup($config);
    }

    /**
     * Setup the application.
     *
     * @param array $config [optional] The config to use
     * @param bool  $force  [optional] Whether to force a setup
     *
     * @throws \Valkyrja\Exceptions\InvalidContainerImplementation
     * @throws \Valkyrja\Exceptions\InvalidDispatcherImplementation
     * @throws \Valkyrja\Exceptions\InvalidEventsImplementation
     *
     * @return void
     */
    public function setup(array $config = null, bool $force = false): void
    {
        // If the application was already setup, no need to do it again
        if (self::$setup && false === $force) {
            return;
        }

        // Avoid re-setting up the app later
        self::$setup = true;
        // Set the app static
        self::$app = $this;
        // Ensure the env has been set
        self::setEnv();

        // If the VALKYRJA_START constant hasn't already been set
        if (! defined('VALKYRJA_START')) {
            // Set a global constant for when the framework started
            define('VALKYRJA_START', microtime(true));
        }

        // Bootstrap debug capabilities
        $this->bootstrapConfig($config);
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
     * Bootstrap the config.
     *
     * @param array $config [optional] The config
     *
     * @return void
     */
    protected function bootstrapConfig(array $config = null): void
    {
        $cacheFilePath = Directory::cachePath('config.php');

        // If we should use the config cache file
        if (is_file($cacheFilePath)) {
            // Get the config from the cache file's contents
            self::$config = require $cacheFilePath;

            return;
        }

        $config         = $config ?? [];
        $configFilePath = Directory::configPath('config.php');
        $configFilePath = is_file($configFilePath) ? $configFilePath : __DIR__ . '/Config/config.php';
        $defaultConfigs = require $configFilePath;

        self::$config = array_replace_recursive($defaultConfigs, $config);

        /* @var \Valkyrja\Support\Providers\Provider $provider */
        foreach (self::$config['providers'] as $provider) {
            // Config providers are NOT deferred and will not follow the deferred value
            $provider::publish($this);
        }
    }

    /**
     * Bootstrap debug capabilities.
     *
     * @return void
     */
    protected function bootstrapDebug(): void
    {
        // If debug is on, enable debug handling
        if (self::$config['app']['debug']) {
            // Debug to output exceptions
            Debug::enable(E_ALL, true);
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
        $eventsImpl = self::$config['app']['events'];
        // The container class to use from the config
        $containerImpl = self::$config['app']['container'];
        // The dispatcher class to use from the config
        $dispatcherImpl = self::$config['app']['dispatcher'];

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
        self::$container->singleton(Application::class, $this);
        // Set the events instance in the container
        self::$container->singleton('env', self::$env);
        // Set the events instance in the container
        self::$container->singleton('config', self::$config);
        // Set the container instance in the container
        self::$container->singleton(Container::class, self::$container);
        // Set the dispatcher instance in the dispatcher
        self::$container->singleton(Dispatcher::class, self::$dispatcher);
        // Set the events instance in the container
        self::$container->singleton(Events::class, self::$events);
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
        date_default_timezone_set(self::$config['app']['timezone']);
    }

    /**
     * Get the application instance.
     *
     * @return \Valkyrja\Application
     */
    public static function app(): Application
    {
        return self::$app;
    }

    /**
     * Get an environment variable.
     *
     * @param string $variable [optional] The variable to get
     * @param string $default  [optional] The default value to return
     *
     * @return mixed|\Valkyrja\Config\Env||config|Env
     */
    public static function env(string $variable = null, $default = null)
    {
        // If there was no variable requested
        if (null === $variable) {
            // Return the env class
            return static::getEnv();
        }

        // If the env has this variable defined and the variable isn't null
        if (defined(static::getEnv() . '::' . $variable) && null !== $env = constant(static::getEnv() . '::' . $variable)) {
            // Return the variable
            return $env;
        }

        // Otherwise return the default
        return $default;
    }

    /**
     * Get the environment variables class.
     *
     * @return \Valkyrja\Config\Env||config|Env
     */
    public static function getEnv(): string
    {
        return self::$env ?? self::$env = Env::class;
    }

    /**
     * Set the environment variables class.
     *
     * @param string $env [optional] The env file to use
     *
     * @return void
     */
    public static function setEnv(string $env = null): void
    {
        // Set the env class to use
        self::$env = $env ?? Env::class;
    }

    /**
     * Get the config class instance.
     *
     * @return array
     */
    public function config(): array
    {
        return self::$config;
    }

    /**
     * Add to the global config array.
     *
     * @param array $newConfig The new config to add
     *
     * @return void
     */
    public function addConfig(array $newConfig): void
    {
        // Set the config within the application
        self::$config = array_replace_recursive(self::$config, $newConfig);
    }

    /**
     * Get the container instance.
     *
     * @return \Valkyrja\Container\Container
     */
    public function container(): Container
    {
        return self::$container;
    }

    /**
     * Get the dispatcher instance.
     *
     * @return \Valkyrja\Dispatcher\Dispatcher
     */
    public function dispatcher(): Dispatcher
    {
        return self::$dispatcher;
    }

    /**
     * Get the events instance.
     *
     * @return \Valkyrja\Events\Events
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
     * Get the environment with which the application is running in.
     *
     * @return string
     */
    public function environment(): string
    {
        return self::$config['app']['env'];
    }

    /**
     * Whether the application is running in debug mode or not.
     *
     * @return string
     */
    public function debug(): string
    {
        return self::$config['app']['debug'];
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
     * @return \Valkyrja\Annotations\Annotations
     */
    public function annotations(): Annotations
    {
        return $this->container()->getSingleton(Annotations::class);
    }

    /**
     * Return the client instance from the container.
     *
     * @return \Valkyrja\Client\Client
     */
    public function client(): Client
    {
        return $this->container()->getSingleton(Client::class);
    }

    /**
     * Return the console instance from the container.
     *
     * @return \Valkyrja\Console\Console
     */
    public function console(): Console
    {
        return $this->container()->getSingleton(Console::class);
    }

    /**
     * Return the console kernel instance from the container.
     *
     * @return \Valkyrja\Console\Kernel
     */
    public function consoleKernel(): ConsoleKernel
    {
        return $this->container()->getSingleton(ConsoleKernel::class);
    }

    /**
     * Return the filesystem instance from the container.
     *
     * @return \Valkyrja\Filesystem\Filesystem
     */
    public function filesystem(): Filesystem
    {
        return $this->container()->getSingleton(Filesystem::class);
    }

    /**
     * Return the kernel instance from the container.
     *
     * @return \Valkyrja\Http\Kernel
     */
    public function kernel(): Kernel
    {
        return $this->container()->getSingleton(Kernel::class);
    }

    /**
     * Return the logger instance from the container.
     *
     * @return \Valkyrja\Logger\Logger
     */
    public function logger(): Logger
    {
        return $this->container()->getSingleton(Logger::class);
    }

    /**
     * Return the path generator instance from the container.
     *
     * @return \Valkyrja\Path\PathGenerator
     */
    public function pathGenerator(): PathGenerator
    {
        return $this->container()->getSingleton(PathGenerator::class);
    }

    /**
     * Return the path parser instance from the container.
     *
     * @return \Valkyrja\Path\PathParser
     */
    public function pathParser(): PathParser
    {
        return $this->container()->getSingleton(PathParser::class);
    }

    /**
     * Return the request instance from the container.
     *
     * @return \Valkyrja\Http\Request
     */
    public function request(): Request
    {
        return $this->container()->getSingleton(Request::class);
    }

    /**
     * Return the router instance from the container.
     *
     * @return \Valkyrja\Routing\Router
     */
    public function router(): Router
    {
        return $this->container()->getSingleton(Router::class);
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
     * @return \Valkyrja\Http\Response
     */
    public function response(
        string $content = '',
        int $statusCode = StatusCode::OK,
        array $headers = []
    ): Response {
        /** @var Response $response */
        $response = $this->container()->getSingleton(Response::class);

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
     * @return \Valkyrja\Http\JsonResponse
     */
    public function json(
        array $data = [],
        int $statusCode = StatusCode::OK,
        array $headers = []
    ): JsonResponse {
        /** @var JsonResponse $response */
        $response = $this->container()->getSingleton(JsonResponse::class);

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
     * @return \Valkyrja\Http\RedirectResponse
     */
    public function redirect(
        string $uri = null,
        int $statusCode = StatusCode::FOUND,
        array $headers = []
    ): RedirectResponse {
        /** @var RedirectResponse $response */
        $response = $this->container()->getSingleton(RedirectResponse::class);

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
     * @return \Valkyrja\Http\RedirectResponse
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
     * @return \Valkyrja\Http\ResponseBuilder
     */
    public function responseBuilder(): ResponseBuilder
    {
        return $this->container()->getSingleton(ResponseBuilder::class);
    }

    /**
     * Return the session.
     *
     * @return \Valkyrja\Session\Session
     */
    public function session(): Session
    {
        return $this->container()->getSingleton(Session::class);
    }

    /**
     * Helper function to get a new view.
     *
     * @param string $template  [optional] The template to use
     * @param array  $variables [optional] The variables to use
     *
     * @return \Valkyrja\View\View
     */
    public function view(string $template = '', array $variables = []): View
    {
        /** @var \Valkyrja\View\View $view */
        $view = $this->container()->getSingleton(View::class);

        if (func_num_args() === 0) {
            return $view;
        }

        return $view->make($template, $variables);
    }
}