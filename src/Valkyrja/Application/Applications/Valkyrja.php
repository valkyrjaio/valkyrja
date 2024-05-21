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

use RuntimeException;
use Valkyrja\Application\Application;
use Valkyrja\Application\Env;
use Valkyrja\Application\Support\Provider;
use Valkyrja\Config\Config as ConfigModel;
use Valkyrja\Config\Config\Config;
use Valkyrja\Console\Kernel as ConsoleKernel;
use Valkyrja\Container\Container;
use Valkyrja\Dispatcher\Contract\Dispatcher;
use Valkyrja\Event\Dispatcher as Events;
use Valkyrja\HttpKernel\Kernel;
use Valkyrja\Support\Directory;
use Valkyrja\Type\BuiltIn\Support\Arr;

use function assert;
use function constant;
use function define;
use function defined;
use function is_file;
use function microtime;

/**
 * Class Valkyrja.
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
     * Application env.
     *
     * @var class-string<Env>|null
     */
    protected static string|null $env = null;

    /**
     * Application config.
     *
     * @var Config|array
     */
    protected static Config|array $config;

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
     * @param class-string<Config>|null $config [optional] The config class to use
     */
    public function __construct(string|null $config = null)
    {
        $this->setup($config);
    }

    /**
     * @inheritDoc
     */
    public static function app(): Application
    {
        return self::$app;
    }

    /**
     * @inheritDoc
     */
    public static function env(string|null $key = null, $default = null): mixed
    {
        $env = self::$env;

        if ($env === null) {
            return $default;
        }

        // If there was no variable requested
        if ($key === null) {
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
     * @inheritDoc
     */
    public static function getEnv(): string|null
    {
        return self::$env;
    }

    /**
     * @inheritDoc
     */
    public static function setEnv(string $env): void
    {
        // Set the env class to use
        self::$env = $env;
    }

    /**
     * @inheritDoc
     */
    public function setup(string|null $config = null, bool $force = false): void
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
     * @inheritDoc
     */
    public function withConfig(Config $config): static
    {
        self::$config = $config;

        $this->publishConfigProviders();
        $this->publishProviders();

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function config(string|null $key = null, mixed $default = null): mixed
    {
        // If no key was specified
        if ($key === null) {
            // Return all the entire config
            return self::$config;
        }

        return Arr::getValueDotNotation(self::$config, $key, $default);
    }

    /**
     * @inheritDoc
     */
    public function addConfig(ConfigModel $newConfig, string $key): void
    {
        // Set the config within the application
        self::$config[$key] = $newConfig;
    }

    /**
     * @inheritDoc
     */
    public function container(): Container
    {
        return self::$container;
    }

    /**
     * @inheritDoc
     */
    public function setContainer(Container $container): static
    {
        self::$container = $container;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function dispatcher(): Dispatcher
    {
        return self::$container->getSingleton(Dispatcher::class);
    }

    /**
     * @inheritDoc
     */
    public function events(): Events
    {
        return self::$container->getSingleton(Events::class);
    }

    /**
     * @inheritDoc
     */
    public function consoleKernel(): ConsoleKernel
    {
        return self::$container->getSingleton(ConsoleKernel::class);
    }

    /**
     * @inheritDoc
     */
    public function kernel(): Kernel
    {
        return self::$container->getSingleton(Kernel::class);
    }

    /**
     * @inheritDoc
     */
    public function debug(): bool
    {
        return self::$config['app']['debug'];
    }

    /**
     * @inheritDoc
     */
    public function environment(): string
    {
        return self::$config['app']['env'];
    }

    /**
     * @inheritDoc
     */
    public function version(): string
    {
        return static::VERSION;
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value): void
    {
        /** @var class-string $offset */
        // Let the container and PHP do the type handling here. Let's add the docblock to avoid static analyzer errors.
        self::$container->bind($offset, $value);
    }

    /**
     * @inheritDoc
     */
    public function offsetExists($offset): bool
    {
        return self::$container->has($offset);
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset($offset): void
    {
        throw new RuntimeException('Cannot unset service: ' . $offset);
    }

    /**
     * @inheritDoc
     */
    public function offsetGet($offset): mixed
    {
        if ($offset === Config::class) {
            return self::$config;
        }

        if ($offset === Env::class) {
            return self::$env;
        }

        if ($offset === Container::class) {
            return self::$container;
        }

        return self::$container->get($offset);
    }

    /**
     * Bootstrap the config.
     *
     * @param class-string<Config>|null $config [optional] The config class to use
     *
     * @return void
     */
    protected function bootstrapConfig(string|null $config = null): void
    {
        // Get the cache file
        $cacheFilePath = $this->getCacheFilePath();

        // If we should use the config cache file
        if (is_file($cacheFilePath)) {
            // Get the config from the cache file's contents
            $this->setupFromCacheFile($cacheFilePath);

            return;
        }

        $config ??= self::env('CONFIG_CLASS', Config::class);

        assert(is_a($config, Config::class, true));

        $this->withConfig(new $config(null, true));
    }

    /**
     * Get cache file path.
     *
     * @return string
     */
    protected function getCacheFilePath(): string
    {
        return self::env('CONFIG_CACHE_FILE_PATH', Directory::cachePath('config.php'));
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
        /** @var Config $config */
        $config = self::$config;

        /** @var class-string<\Valkyrja\Config\Support\Provider> $provider */
        foreach ($config->providers as $provider) {
            // Config providers are NOT deferred
            $provider::publish($config);
        }
    }
}
