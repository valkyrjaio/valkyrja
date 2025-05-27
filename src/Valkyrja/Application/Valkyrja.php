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

use Valkyrja\Application\Contract\Application;
use Valkyrja\Application\Support\Provider;
use Valkyrja\Config\Config as ConfigModel;
use Valkyrja\Config\Config\Config;
use Valkyrja\Config\Config\ValkyrjaDataConfig;
use Valkyrja\Config\Support\DataProvider;
use Valkyrja\Console\Kernel\Contract\Kernel as ConsoleKernel;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Dispatcher\Contract\Dispatcher;
use Valkyrja\Event\Contract\Dispatcher as Events;
use Valkyrja\Exception\InvalidArgumentException;
use Valkyrja\Exception\RuntimeException;
use Valkyrja\Http\Server\Contract\RequestHandler;
use Valkyrja\Support\Directory;
use Valkyrja\Type\BuiltIn\Support\Arr;
use Valkyrja\Type\BuiltIn\Support\Obj;

use function assert;
use function constant;
use function define;
use function defined;
use function is_file;
use function is_string;
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
     * @var class-string<Env>
     */
    protected static string $env;

    /**
     * Application config.
     *
     * @var Config|array<string, mixed>
     */
    protected static Config|array $config;

    /**
     * Application config.
     *
     * @var ValkyrjaDataConfig
     */
    protected static ValkyrjaDataConfig $dataConfig;

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
     * @param class-string<Config>|null             $config     [optional] The config class to use
     * @param class-string<ValkyrjaDataConfig>|null $dataConfig [optional] The config class to use
     */
    public function __construct(string|null $config = null, string|null $dataConfig = null)
    {
        $this->setup(
            config: $config,
            dataConfig: $dataConfig
        );
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
    public static function getEnvValue(string $key, mixed $default = null): mixed
    {
        $env = self::$env;

        // If the env has this variable defined and the variable isn't null
        if (defined($env . '::' . $key)) {
            // Return the variable
            return constant($env . '::' . $key)
                ?? $default;
        }

        // Otherwise return the default
        return $default;
    }

    /**
     * @inheritDoc
     *
     * @return class-string<Env>
     */
    public static function getEnv(): string
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
    public function setup(string|null $config = null, string|null $dataConfig = null, bool $force = false): void
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
        $this->bootstrapConfig(config: $config, dataConfig: $dataConfig);
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
    public function withDataConfig(ValkyrjaDataConfig $config): static
    {
        self::$dataConfig = $config;

        $this->publishDataConfigAppProviders();
        $this->publishDataConfigProviders();

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
    public function getDataConfig(): ValkyrjaDataConfig
    {
        return self::$dataConfig;
    }

    /**
     * @inheritDoc
     */
    public function getDataConfigValue(string $key, mixed $default = null): mixed
    {
        return Obj::getValueDotNotation(self::$dataConfig, $key, $default);
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
    public function kernel(): RequestHandler
    {
        return self::$container->getSingleton(RequestHandler::class);
    }

    /**
     * @inheritDoc
     */
    public function getDebugMode(): bool
    {
        return self::$dataConfig->app->debug;
    }

    /**
     * @inheritDoc
     */
    public function getEnvironment(): string
    {
        return self::$dataConfig->app->env;
    }

    /**
     * @inheritDoc
     */
    public function getVersion(): string
    {
        return static::VERSION;
    }

    /**
     * Bootstrap the config.
     *
     * @param class-string<Config>|null             $config     [optional] The config class to use
     * @param class-string<ValkyrjaDataConfig>|null $dataConfig [optional] The config class to use
     *
     * @return void
     */
    protected function bootstrapConfig(string|null $config = null, string|null $dataConfig = null): void
    {
        // Get the cache file
        $cacheFilePath = $this->getCacheFilePath();

        // If we should use the config cache file
        if (is_file($cacheFilePath)) {
            self::$dataConfig = new ValkyrjaDataConfig(env: self::getEnv());
            // Get the config from the cache file's contents
            $this->setupFromCacheFile($cacheFilePath);

            return;
        }

        // Get the cache file
        $cacheFilePath = $this->getConfigCacheFilePath();

        // If we should use the config cache file
        if (is_file($cacheFilePath)) {
            self::$config = new Config(null, true);
            // Get the config from the cache file's contents
            $this->setupFromDataConfigCacheFile($cacheFilePath);

            return;
        }

        $config     ??= self::getEnvValue('CONFIG_CLASS', Config::class);
        $dataConfig ??= self::getEnvValue('DATA_CONFIG_CLASS', ValkyrjaDataConfig::class);

        assert(is_string($config) && is_a($config, Config::class, true));
        assert(is_string($dataConfig) && is_a($dataConfig, ValkyrjaDataConfig::class, true));

        self::$config     = $newConfig = new $config(null, true);
        self::$dataConfig = $newDataConfig = new $dataConfig(env: self::getEnv());

        $this->withConfig($newConfig);
        $this->withDataConfig($newDataConfig);
    }

    /**
     * Get cache file path.
     *
     * @return string
     */
    protected function getCacheFilePath(): string
    {
        $cacheFilePath = self::getEnvValue('CONFIG_CACHE_FILE_PATH', Directory::cachePath('config.php'));

        if (! is_string($cacheFilePath)) {
            throw new InvalidArgumentException('Cache file path should be a string');
        }

        return $cacheFilePath;
    }

    /**
     * Get cache file path.
     *
     * @return string
     */
    protected function getConfigCacheFilePath(): string
    {
        $cacheFilePath = self::getEnvValue('CONFIG_CACHE_FILE_PATH', Directory::cachePath('data-config.php'));

        if (! is_string($cacheFilePath)) {
            throw new InvalidArgumentException('Config cache file path should be a string');
        }

        return $cacheFilePath;
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
        if (is_file($cacheFilePath)) {
            self::$config = require $cacheFilePath;

            $this->publishProviders();

            return;
        }

        throw new InvalidArgumentException("Invalid $cacheFilePath provided");
    }

    /**
     * Setup the application from a cache file.
     *
     * @param string $cacheFilePath The cache file path
     *
     * @return void
     */
    protected function setupFromDataConfigCacheFile(string $cacheFilePath): void
    {
        if (is_file($cacheFilePath)) {
            $cacheFileContents = file_get_contents($cacheFilePath);

            if ($cacheFileContents === '' || $cacheFileContents === false) {
                throw new RuntimeException('Invalid cache file contents');
            }

            self::$dataConfig = ValkyrjaDataConfig::fromSerializesString($cacheFileContents);

            $this->publishDataConfigAppProviders();

            return;
        }

        throw new InvalidArgumentException("Invalid $cacheFilePath provided");
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
     * Publish app providers.
     *
     * @return void
     */
    protected function publishDataConfigAppProviders(): void
    {
        foreach (self::$dataConfig->app->providers as $provider) {
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

    /**
     * Publish config providers.
     *
     * @return void
     */
    protected function publishDataConfigProviders(): void
    {
        $config = self::$dataConfig;

        /** @var class-string<DataProvider> $provider */
        foreach ($config->config->providers as $provider) {
            // Config providers are NOT deferred
            $provider::publish($config);
        }
    }
}
