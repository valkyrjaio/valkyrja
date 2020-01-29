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

use InvalidArgumentException;
use Valkyrja\Annotations\Annotations;
use Valkyrja\Client\Client;
use Valkyrja\Config\Enums\ConfigKeyPart;
use Valkyrja\Console\Console;
use Valkyrja\Console\Kernel as ConsoleKernel;
use Valkyrja\Container\Container;
use Valkyrja\Crypt\Crypt;
use Valkyrja\Dispatcher\Dispatcher;
use Valkyrja\Env\Env;
use Valkyrja\Events\Events;
use Valkyrja\Exceptions\ExceptionHandler;
use Valkyrja\Exceptions\InvalidContainerImplementation;
use Valkyrja\Exceptions\InvalidDispatcherImplementation;
use Valkyrja\Exceptions\InvalidEventsImplementation;
use Valkyrja\Exceptions\InvalidExceptionHandlerImplementation;
use Valkyrja\Filesystem\Filesystem;
use Valkyrja\Http\Enums\StatusCode;
use Valkyrja\Http\Exceptions\HttpException;
use Valkyrja\Http\Exceptions\HttpRedirectException;
use Valkyrja\Http\Exceptions\InvalidStatusCodeException;
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
use Valkyrja\Support\Directory;
use Valkyrja\Support\Providers\Provider;
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
     * @var Application
     */
    protected static Application $app;

    /**
     * Whether the application was setup.
     *
     * @var bool
     */
    protected static bool $setup = false;

    /**
     * Application env.
     *
     * @var Env|\env\Env
     */
    protected static $env;

    /**
     * Application config.
     *
     * @var array
     */
    protected static array $config;

    /**
     * Get the instance of the container.
     *
     * @var Container
     */
    protected static Container $container;

    /**
     * Get the instance of the dispatcher.
     *
     * @var Dispatcher
     */
    protected static Dispatcher $dispatcher;

    /**
     * Get the instance of the events.
     *
     * @var Events
     */
    protected static Events $events;

    /**
     * Get the instance of the exception handler.
     *
     * @var ExceptionHandler
     */
    protected static ExceptionHandler $exceptionHandler;

    /**
     * Is the app using a compiled version?
     *
     * @var bool
     */
    protected bool $isCompiled = false;

    /**
     * Application constructor.
     *
     * @param array $config [optional] The config to use
     *
     * @throws InvalidContainerImplementation
     * @throws InvalidDispatcherImplementation
     * @throws InvalidEventsImplementation
     * @throws InvalidExceptionHandlerImplementation
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
     * @throws InvalidDispatcherImplementation
     * @throws InvalidEventsImplementation
     * @throws InvalidContainerImplementation
     * @throws InvalidExceptionHandlerImplementation
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
        $this->bootstrapExceptionHandler();
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
        /** @var Provider[] $providers */
        $providers = self::$config[ConfigKeyPart::PROVIDERS];

        foreach ($providers as $provider) {
            // Config providers are NOT deferred and will not follow the
            // deferred value
            $provider::publish($this);
        }
    }

    /**
     * Bootstrap debug capabilities.
     *
     * @return void
     */
    protected function bootstrapExceptionHandler(): void
    {
        // The exception handler class to use from the config
        $exceptionHandlerImpl = self::$config[ConfigKeyPart::APP][ConfigKeyPart::EXCEPTION_HANDLER];

        // Set the exception handler to a new instance of the exception handler implementation
        self::$exceptionHandler = new $exceptionHandlerImpl($this);

        // If the dispatcher implementation specified does not adhere to the dispatcher contract
        if (! self::$exceptionHandler instanceof ExceptionHandler) {
            throw new InvalidExceptionHandlerImplementation('Invalid ExceptionHandler implementation');
        }

        // If debug is on, enable debug handling
        if ($this->debug()) {
            // Enable exception handling
            self::$exceptionHandler::enable(E_ALL, true);
        }
    }

    /**
     * Bootstrap core functionality.
     *
     * @throws InvalidDispatcherImplementation
     * @throws InvalidEventsImplementation
     * @throws InvalidContainerImplementation
     * @throws InvalidExceptionHandlerImplementation
     *
     * @return void
     */
    protected function bootstrapCore(): void
    {
        // The events class to use from the config
        $eventsImpl = self::$config[ConfigKeyPart::APP][ConfigKeyPart::EVENTS];
        // The container class to use from the config
        $containerImpl = self::$config[ConfigKeyPart::APP][ConfigKeyPart::CONTAINER];
        // The dispatcher class to use from the config
        $dispatcherImpl = self::$config[ConfigKeyPart::APP][ConfigKeyPart::DISPATCHER];

        // Set the events to a new instance of the events implementation
        self::$events = new $eventsImpl($this);

        // If the events implementation specified does not adhere to the events contract
        if (! self::$events instanceof Events) {
            throw new InvalidEventsImplementation('Invalid Events implementation');
        }

        // Set the container to a new instance of the container implementation
        self::$container = new $containerImpl($this);

        // If the container implementation specified does not adhere to the container contract
        if (! self::$container instanceof Container) {
            throw new InvalidContainerImplementation('Invalid Container implementation');
        }

        // Set the dispatcher to a new instance of the dispatcher implementation
        self::$dispatcher = new $dispatcherImpl($this);

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
        // Set the exception handler instance in the container
        self::$container->singleton(ExceptionHandler::class, self::$exceptionHandler);
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
        date_default_timezone_set(self::$config[ConfigKeyPart::APP][ConfigKeyPart::TIMEZONE]);
    }

    /**
     * Get the application instance.
     *
     * @return Application
     */
    public static function app(): Application
    {
        return self::$app;
    }

    /**
     * Get an environment variable.
     *
     * @param string $key     [optional] The variable to get
     * @param string $default [optional] The default value to return
     *
     * @return mixed|Env||config|Env
     */
    public static function env(string $key = null, $default = null)
    {
        // If there was no variable requested
        if (null === $key) {
            // Return the env class
            return static::getEnv();
        }

        // If the env has this variable defined and the variable isn't null
        if (
            defined(static::getEnv() . '::' . $key)
            && null !== $env = constant(static::getEnv() . '::' . $key)
        ) {
            // Return the variable
            return $env;
        }

        // Otherwise return the default
        return $default;
    }

    /**
     * Get the environment variables class.
     *
     * @return Env||config|Env
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
        self::$env = $env ?? self::$env ?? Env::class;
    }

    /**
     * Get the config.
     *
     * @param string $key     [optional] The key to get
     * @param string $default [optional] The default value if the key is not
     *                        found
     *
     * @return mixed
     */
    public function config(string $key = null, $default = null)
    {
        // If no key was specified
        if (null === $key) {
            // Return all the entire config
            return self::$config;
        }

        // Explode the keys on period
        $keys = explode(ConfigKeyPart::SEP, $key);
        // Set the config to return
        $config = self::$config;

        // Iterate through the keys
        foreach ($keys as $configItem) {
            // Trying to get the item from the config
            // or load the default
            $config = $config[$configItem] ?? $default;

            // If the item was not found, might as well return out from here
            // instead of continuing to iterate through the remaining keys
            if ($default === $config) {
                return $default;
            }
        }

        // do while($current !== $default);

        // Return the found config
        return $config;
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
     * @return Container
     */
    public function container(): Container
    {
        return self::$container;
    }

    /**
     * Get the dispatcher instance.
     *
     * @return Dispatcher
     */
    public function dispatcher(): Dispatcher
    {
        return self::$dispatcher;
    }

    /**
     * Get the events instance.
     *
     * @return Events
     */
    public function events(): Events
    {
        return self::$events;
    }

    /**
     * Get the exception handler instance.
     *
     * @return ExceptionHandler
     */
    public function exceptionHandler(): ExceptionHandler
    {
        return self::$exceptionHandler;
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
        return self::$config[ConfigKeyPart::APP][ConfigKeyPart::ENV];
    }

    /**
     * Whether the application is running in debug mode or not.
     *
     * @return bool
     */
    public function debug(): bool
    {
        return self::$config[ConfigKeyPart::APP][ConfigKeyPart::DEBUG];
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
    ): void {
        throw new self::$config[ConfigKeyPart::APP][ConfigKeyPart::HTTP_EXCEPTION_CLASS](
            $statusCode,
            $message,
            null,
            $headers,
            $code,
            $response
        );
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
    public function redirectTo(string $uri = null, int $statusCode = StatusCode::FOUND, array $headers = []): void
    {
        throw new HttpRedirectException($statusCode, $uri, null, $headers, 0);
    }

    /**
     * Return the annotations instance from the container.
     *
     * @return Annotations
     */
    public function annotations(): Annotations
    {
        return self::$container->getSingleton(Annotations::class);
    }

    /**
     * Return the client instance from the container.
     *
     * @return Client
     */
    public function client(): Client
    {
        return self::$container->getSingleton(Client::class);
    }

    /**
     * Return the console instance from the container.
     *
     * @return Console
     */
    public function console(): Console
    {
        return self::$container->getSingleton(Console::class);
    }

    /**
     * Return the console kernel instance from the container.
     *
     * @return ConsoleKernel
     */
    public function consoleKernel(): ConsoleKernel
    {
        return self::$container->getSingleton(ConsoleKernel::class);
    }

    /**
     * Return the crypt instance from the container.
     *
     * @return Crypt
     */
    public function crypt(): Crypt
    {
        return self::$container->getSingleton(Crypt::class);
    }

    /**
     * Return the entity manager instance from the container.
     *
     * @return EntityManager
     */
    public function entityManager(): EntityManager
    {
        return self::$container->getSingleton(EntityManager::class);
    }

    /**
     * Return the filesystem instance from the container.
     *
     * @return Filesystem
     */
    public function filesystem(): Filesystem
    {
        return self::$container->getSingleton(Filesystem::class);
    }

    /**
     * Return the kernel instance from the container.
     *
     * @return Kernel
     */
    public function kernel(): Kernel
    {
        return self::$container->getSingleton(Kernel::class);
    }

    /**
     * Return the logger instance from the container.
     *
     * @return Logger
     */
    public function logger(): Logger
    {
        return self::$container->getSingleton(Logger::class);
    }

    /**
     * Return the mail instance from the container.
     *
     * @return Mail
     */
    public function mail(): Mail
    {
        return self::$container->getSingleton(Mail::class);
    }

    /**
     * Return the path generator instance from the container.
     *
     * @return PathGenerator
     */
    public function pathGenerator(): PathGenerator
    {
        return self::$container->getSingleton(PathGenerator::class);
    }

    /**
     * Return the path parser instance from the container.
     *
     * @return PathParser
     */
    public function pathParser(): PathParser
    {
        return self::$container->getSingleton(PathParser::class);
    }

    /**
     * Return the request instance from the container.
     *
     * @return Request
     */
    public function request(): Request
    {
        return self::$container->getSingleton(Request::class);
    }

    /**
     * Return the router instance from the container.
     *
     * @return Router
     */
    public function router(): Router
    {
        return self::$container->getSingleton(Router::class);
    }

    /**
     * Return a new response from the application.
     *
     * @param string $content    [optional] The content to set
     * @param int    $statusCode [optional] The status code to set
     * @param array  $headers    [optional] The headers to set
     *
     * @throws InvalidArgumentException
     *
     * @return Response
     */
    public function response(string $content = '', int $statusCode = StatusCode::OK, array $headers = []): Response
    {
        /** @var Response $response */
        $response = self::$container->getSingleton(Response::class);

        if (func_num_args() === 0) {
            return $response;
        }

        return $response::create($content, $statusCode, $headers);
    }

    /**
     * Return a new json response from the application.
     *
     * @param array $data       [optional] An array of data
     * @param int   $statusCode [optional] The status code to set
     * @param array $headers    [optional] The headers to set
     *
     * @throws InvalidArgumentException
     *
     * @return JsonResponse
     */
    public function json(array $data = [], int $statusCode = StatusCode::OK, array $headers = []): JsonResponse
    {
        /** @var JsonResponse $response */
        $response = self::$container->getSingleton(JsonResponse::class);

        if (func_num_args() === 0) {
            return $response;
        }

        return $response::createJson('', $statusCode, $headers, $data);
    }

    /**
     * Return a new json response from the application.
     *
     * @param string $uri        [optional] The URI to redirect to
     * @param int    $statusCode [optional] The response status code
     * @param array  $headers    [optional] An array of response headers
     *
     * @throws InvalidStatusCodeException
     * @throws InvalidArgumentException
     *
     * @return RedirectResponse
     */
    public function redirect(
        string $uri = null,
        int $statusCode = StatusCode::FOUND,
        array $headers = []
    ): RedirectResponse {
        /** @var RedirectResponse $response */
        $response = self::$container->getSingleton(RedirectResponse::class);

        if (func_num_args() === 0) {
            return $response;
        }

        return $response::createRedirect($uri, $statusCode, $headers);
    }

    /**
     * Return a new redirect response from the application for a given route.
     *
     * @param string $route      The route to match
     * @param array  $parameters [optional] Any parameters to set for dynamic
     *                           routes
     * @param int    $statusCode [optional] The response status code
     * @param array  $headers    [optional] An array of response headers
     *
     * @throws InvalidStatusCodeException
     * @throws InvalidArgumentException
     *
     * @return RedirectResponse
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
     * @return ResponseBuilder
     */
    public function responseBuilder(): ResponseBuilder
    {
        return self::$container->getSingleton(ResponseBuilder::class);
    }

    /**
     * Return the session.
     *
     * @return Session
     */
    public function session(): Session
    {
        return self::$container->getSingleton(Session::class);
    }

    /**
     * Helper function to get a new view.
     *
     * @param string $template  [optional] The template to use
     * @param array  $variables [optional] The variables to use
     *
     * @return View
     */
    public function view(string $template = '', array $variables = []): View
    {
        /** @var View $view */
        $view = self::$container->getSingleton(View::class);

        if (func_num_args() === 0) {
            return $view;
        }

        return $view->make($template, $variables);
    }
}
