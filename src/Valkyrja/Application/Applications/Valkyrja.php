<?php

declare(strict_types = 1);

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
use Valkyrja\Application\Helpers\Helpers;
use Valkyrja\Config\Enums\ConfigKeyPart;
use Valkyrja\Config\Enums\EnvKey;
use Valkyrja\Container\Container;
use Valkyrja\Container\Enums\Contract;
use Valkyrja\Dispatcher\Dispatcher;
use Valkyrja\Event\Events;
use Valkyrja\Exception\ExceptionHandler;
use Valkyrja\Exception\InvalidContainerImplementation;
use Valkyrja\Exception\InvalidDispatcherImplementation;
use Valkyrja\Exception\InvalidEventsImplementation;
use Valkyrja\Exception\InvalidExceptionHandlerImplementation;
use Valkyrja\Support\Directory;
use Valkyrja\Support\Providers\Provider;

/**
 * Class Valkyrja.
 *
 * @author Melech Mizrachi
 */
class Valkyrja implements Application
{
    use Helpers;

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
        $envCacheFile  = Directory::basePath((string) self::env(EnvKey::CONFIG_CACHE_FILE_PATH));
        $cacheFilePath = is_file($envCacheFile) ? $envCacheFile : Directory::cachePath('config.php');

        // If we should use the config cache file
        if (is_file($cacheFilePath)) {
            // Get the config from the cache file's contents
            self::$config = require $cacheFilePath;

            return;
        }

        $config         = $config ?? [];
        $envConfigFile  = Directory::basePath((string) self::env(EnvKey::CONFIG_FILE_PATH));
        $configFilePath = is_file($envConfigFile) ? $envConfigFile : Directory::configPath('config.php');
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
        self::$container->singleton(Contract::APP, $this);
        // Set the events instance in the container
        self::$container->singleton('env', self::$env);
        // Set the events instance in the container
        self::$container->singleton('config', self::$config);
        // Set the container instance in the container
        self::$container->singleton(Contract::CONTAINER, self::$container);
        // Set the dispatcher instance in the dispatcher
        self::$container->singleton(Contract::DISPATCHER, self::$dispatcher);
        // Set the events instance in the container
        self::$container->singleton(Contract::EVENTS, self::$events);
        // Set the exception handler instance in the container
        self::$container->singleton(Contract::EXCEPTION_HANDLER, self::$exceptionHandler);
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
}
