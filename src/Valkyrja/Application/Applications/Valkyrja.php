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

namespace Valkyrja\Application\Applications;

use Valkyrja\Application\Application;
use Valkyrja\Application\Helpers\ContainerHelpers;
use Valkyrja\Application\Helpers\Helpers;
use Valkyrja\Config\Config;
use Valkyrja\Config\Enums\EnvKey;
use Valkyrja\Container\Container;
use Valkyrja\Container\Enums\Contract;
use Valkyrja\Dispatcher\Dispatcher;
use Valkyrja\Event\Events;
use Valkyrja\Exception\ExceptionHandler;
use Valkyrja\Support\Directory;

use function define;
use function defined;

use const E_ALL;

/**
 * Class Valkyrja.
 *
 * @author Melech Mizrachi
 */
class Valkyrja implements Application
{
    use ContainerHelpers;
    use Helpers;

    /**
     * Get the instance of the application.
     *
     * @var Application
     */
    protected static Application $app;

    /**
     * Application env.
     *
     * @var string
     */
    protected static string $env;

    /**
     * Application config.
     *
     * @var Config|object
     */
    protected static object $config;

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
     * Whether the application was setup.
     *
     * @var bool
     */
    protected static bool $setup = false;
    /**
     * Is the app using a compiled version?
     *
     * @var bool
     */
    protected bool $isCompiled = false;

    /**
     * Application constructor.
     *
     * @param string|null $config [optional] The config class to use
     */
    public function __construct(string $config = null)
    {
        $this->setup($config);
    }

    /**
     * Setup the application.
     *
     * @param string|null $config [optional] The config to use
     * @param bool        $force  [optional] Whether to force a setup
     *
     * @return void
     */
    public function setup(string $config = null, bool $force = false): void
    {
        // If the application was already setup, no need to do it again
        if (self::$setup && ! $force) {
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
     * Add to the global config array.
     *
     * @param Config $config The config to add
     *
     * @return static
     */
    public function withConfig(Config $config): self
    {
        self::$config = $config;

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

        return $this;
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
     * Bootstrap the config.
     *
     * @param string|null $config [optional] The config class to use
     *
     * @return void
     */
    protected function bootstrapConfig(string $config = null): void
    {
        $envCacheFile  = self::env(EnvKey::CONFIG_CACHE_FILE_PATH);
        $cacheFilePath = Directory::cachePath('config.php');

        // If an env variable for cache file path was set
        if (null !== $envCacheFile) {
            $envCacheFilePath = Directory::basePath((string) self::env(EnvKey::CONFIG_CACHE_FILE_PATH));
            $cacheFilePath    = is_file($envCacheFilePath) ? $envCacheFilePath : $cacheFilePath;
        }

        // If we should use the config cache file
        if (is_file($cacheFilePath)) {
            // Get the config from the cache file's contents
            $this->setupFromCacheFile($cacheFilePath);

            return;
        }

        $config ??= $config ?? self::env(EnvKey::CONFIG_CLASS, Config::class);

        self::$config = new $config();

        $this->publishConfigProviders();
    }

    /**
     * Setup the application from a cache file.
     *
     * @param string $cacheFilePath The cache file path
     *
     * @return void
     */
    protected function setupFromCacheFile(string $cacheFilePath): void
    {
        self::$config = unserialize(
            file_get_contents($cacheFilePath),
            [
                'allowed_classes' => true,
            ]
        );
    }

    /**
     * Get cache allowed classes for unserialize.
     *
     * @return array
     */
    protected function getCacheAllowedClasses(): array
    {
        return self::env(EnvKey::CONFIG_CACHE_ALLOWED_CLASSES);
    }

    /**
     * Publish config providers.
     *
     * @return void
     */
    protected function publishConfigProviders(): void
    {
        foreach (self::$config->providers as $provider) {
            // Config providers are NOT deferred and will not follow the deferred value
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
        $exceptionHandlerImpl = self::$config->app->exceptionHandler;

        // Set the exception handler to a new instance of the exception handler implementation
        self::$exceptionHandler = new $exceptionHandlerImpl($this);

        // If debug is on, enable debug handling
        if ($this->debug()) {
            // Enable exception handling
            self::$exceptionHandler::enable(E_ALL, true);
        }
    }

    /**
     * Bootstrap core functionality.
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
        // Set the container to a new instance of the container implementation
        self::$container = new $containerImpl($this);
        // Set the dispatcher to a new instance of the dispatcher implementation
        self::$dispatcher = new $dispatcherImpl($this);
    }

    /**
     * Bootstrap the container.
     *
     * @return void
     */
    protected function bootstrapContainer(): void
    {
        // Set the application instance in the container
        self::$container->setSingleton(Contract::APP, $this);
        // Set the events instance in the container
        self::$container->setSingleton('env', self::$env);
        // Set the events instance in the container
        self::$container->setSingleton('config', self::$config);
        // Set the container instance in the container
        self::$container->setSingleton(Contract::CONTAINER, self::$container);
        // Set the dispatcher instance in the dispatcher
        self::$container->setSingleton(Contract::DISPATCHER, self::$dispatcher);
        // Set the events instance in the container
        self::$container->setSingleton(Contract::EVENTS, self::$events);
        // Set the exception handler instance in the container
        self::$container->setSingleton(Contract::EXCEPTION_HANDLER, self::$exceptionHandler);
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
        date_default_timezone_set(self::$config->app->timezone);
    }
}
