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

namespace Valkyrja\Application\Applications;

use Valkyrja\Application\Application;
use Valkyrja\Application\Helpers\ApplicationHelpersTrait;
use Valkyrja\Application\Support\Provider;
use Valkyrja\Config\Config as ConfigModel;
use Valkyrja\Config\Config\Config;
use Valkyrja\Config\Constants\ConfigKeyPart;
use Valkyrja\Config\Constants\EnvKey;
use Valkyrja\Console\Kernel as ConsoleKernel;
use Valkyrja\Container\Container;
use Valkyrja\Dispatcher\Dispatcher;
use Valkyrja\Event\Events;
use Valkyrja\HttpKernel\Kernel;
use Valkyrja\Support\Directory;
use Valkyrja\Support\Exception\ExceptionHandler;

use function constant;
use function date_default_timezone_set;
use function define;
use function defined;
use function explode;
use function is_file;
use function microtime;

use const E_ALL;

/**
 * Class Valkyrja.
 *
 * @author Melech Mizrachi
 */
class Valkyrja implements Application
{
    use ApplicationHelpersTrait;

    /**
     * Get the instance of the application.
     *
     * @var Application
     */
    protected static Application $app;

    /**
     * Application env.
     *
     * @var string|null
     */
    protected static ?string $env = null;

    /**
     * Application config.
     *
     * @var Config|array
     */
    protected static $config;

    /**
     * Get the instance of the container.
     *
     * @var Container
     */
    protected static Container $container;

    /**
     * Whether the application was setup.
     *
     * @var bool
     */
    protected static bool $setup = false;

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

        // Set a global constant for when the framework started
        define('VALKYRJA_START', microtime(true));

        // Bootstrap debug capabilities
        $this->bootstrapConfig($config);
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

        $this->publishConfigProviders();
        $this->publishProviders();
        $this->bootstrapAfterProviders();

        return $this;
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
     * @param mixed  $default [optional] The default value to return
     *
     * @return mixed
     */
    public static function env(string $key = null, $default = null)
    {
        $env = self::$env;

        if (null === $env) {
            return $default;
        }

        // If there was no variable requested
        if (null === $key) {
            // Return the env class
            return $env;
        }

        // If the env has this variable defined and the variable isn't null
        if (defined($env . '::' . $key)) {
            // Return the variable
            return constant($env . '::' . $key) ?? $default;
        }

        // Otherwise return the default
        return $default;
    }

    /**
     * Get the environment variables class.
     *
     * @return string|null
     */
    public static function getEnv(): ?string
    {
        return self::$env;
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
        self::$env = $env;
    }

    /**
     * Get the config.
     *
     * @param string $key     [optional] The key to get
     * @param mixed  $default [optional] The default value if the key is not found
     *
     * @return mixed|Config|null
     */
    public function config(string $key = null, $default = null)
    {
        // Set the config to return
        $config = self::$config;

        // If no key was specified
        if (null === $key) {
            // Return all the entire config
            return $config;
        }

        // Explode the keys on period and iterate through the keys
        foreach (explode(ConfigKeyPart::SEP, $key) as $configItem) {
            // Trying to get the item from the config or set the default
            $config = $config[$configItem] ?? $default;

            // If the item was not found, might as well return out from here
            // instead of continuing to iterate through the remaining keys
            if ($default === $config) {
                return $default;
            }
        }

        // Return the found config
        return $config;
    }

    /**
     * Add to the global config array.
     *
     * @param ConfigModel $newConfig The new config to add
     * @param string      $key       The key to use
     *
     * @return void
     */
    public function addConfig(ConfigModel $newConfig, string $key): void
    {
        // Set the config within the application
        self::$config[$key] = $newConfig;
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
     * Set the container instance.
     *
     * @param Container $container The container instance
     *
     * @return static
     */
    public function setContainer(Container $container): self
    {
        self::$container = $container;

        return $this;
    }

    /**
     * Get the dispatcher instance.
     *
     * @return Dispatcher
     */
    public function dispatcher(): Dispatcher
    {
        return self::$container->getSingleton(Dispatcher::class);
    }

    /**
     * Get the events instance.
     *
     * @return Events
     */
    public function events(): Events
    {
        return self::$container->getSingleton(Events::class);
    }

    /**
     * Get the console kernel instance from the container.
     *
     * @return ConsoleKernel
     */
    public function consoleKernel(): ConsoleKernel
    {
        return self::$container->getSingleton(ConsoleKernel::class);
    }

    /**
     * Get the kernel instance from the container.
     *
     * @return Kernel
     */
    public function kernel(): Kernel
    {
        return self::$container->getSingleton(Kernel::class);
    }

    /**
     * Whether the application is running in debug mode or not.
     *
     * @return bool
     */
    public function debug(): bool
    {
        return self::$config['app']['debug'];
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
     * Get the application version.
     *
     * @return string
     */
    public function version(): string
    {
        return static::VERSION;
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
        // Get the cache file
        $cacheFilePath = $this->getCacheFilePath();

        // If we should use the config cache file
        if (is_file($cacheFilePath)) {
            // Get the config from the cache file's contents
            $this->setupFromCacheFile($cacheFilePath);

            return;
        }

        $config ??= self::env(EnvKey::CONFIG_CLASS, Config::class);

        $this->withConfig(new $config());
    }

    /**
     * Get cache file path.
     *
     * @return string
     */
    protected function getCacheFilePath(): string
    {
        return self::env(EnvKey::CONFIG_CACHE_FILE_PATH, Directory::cachePath('config.php'));
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
        self::$config = require $cacheFilePath;

        $this->publishProviders();
        $this->bootstrapAfterProviders();
    }

    /**
     * Publish app providers.
     *
     * @return void
     */
    protected function publishProviders(): void
    {
        foreach (self::$config['app']['providers'] as $provider) {
            /** @var Provider $provider */
            // App providers are NOT deferred
            $provider::publish($this);
        }
    }

    /**
     * Publish config providers.
     *
     * @return void
     */
    protected function publishConfigProviders(): void
    {
        foreach (self::$config['providers'] as $provider) {
            /** @var \Valkyrja\Config\Support\Provider $provider */
            // Config providers are NOT deferred
            $provider::publish(self::$config);
        }
    }

    /**
     * Run bootstraps after config bootstrap.
     *
     * @return void
     */
    protected function bootstrapAfterProviders(): void
    {
        // Bootstrap debug capabilities
        $this->bootstrapExceptionHandler();
        // Bootstrap the timezone
        $this->bootstrapTimezone();
    }

    /**
     * Bootstrap debug capabilities.
     *
     * @return void
     */
    protected function bootstrapExceptionHandler(): void
    {
        // If debug is on, enable debug handling
        if ($this->debug()) {
            /** @var ExceptionHandler $exceptionHandler */
            $exceptionHandler = self::$config->app->exceptionHandler;

            // Enable exception handling
            $exceptionHandler::enable(E_ALL, true);
        }
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
}
