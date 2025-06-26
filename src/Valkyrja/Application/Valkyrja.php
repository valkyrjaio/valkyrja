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

use Valkyrja\Application\Config\Valkyrja as ValkyrjaConfig;
use Valkyrja\Application\Contract\Application;
use Valkyrja\Application\Support\Provider;
use Valkyrja\Config\Config;
use Valkyrja\Config\Support\Provider as ConfigProvider;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Exception\InvalidArgumentException;
use Valkyrja\Exception\RuntimeException;
use Valkyrja\Support\Directory;
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
     * Application env.
     *
     * @var class-string<Env>
     */
    protected static string $env;

    /**
     * Application config.
     */
    protected static ValkyrjaConfig $config;

    /**
     * Get the instance of the container.
     */
    protected static Container $container;

    /**
     * Whether the application was setup.
     */
    protected static bool $setup = false;

    /**
     * Application constructor.
     *
     * @param class-string<ValkyrjaConfig>|null $dataConfig [optional] The config class to use
     */
    public function __construct(string|null $dataConfig = null)
    {
        $this->setup(
            dataConfig: $dataConfig
        );
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
    public function setup(string|null $dataConfig = null, bool $force = false): void
    {
        // If the application was already setup, no need to do it again
        if (self::$setup && ! $force) {
            return;
        }

        // Avoid re-setting up the app later
        self::$setup = true;

        // Set a global constant for when the framework started
        define('VALKYRJA_START', microtime(true));

        // Bootstrap debug capabilities
        $this->bootstrapConfig(dataConfig: $dataConfig);
    }

    /**
     * @inheritDoc
     */
    public function setConfig(ValkyrjaConfig $config): static
    {
        self::$config = $config;

        $this->publishConfigAppProviders();
        $this->publishConfigProviders();

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getConfig(): ValkyrjaConfig
    {
        return self::$config;
    }

    /**
     * @inheritDoc
     */
    public function getConfigValue(string $key, mixed $default = null): mixed
    {
        return Obj::getValueDotNotation(self::$config, $key, $default);
    }

    /**
     * @inheritDoc
     */
    public function addConfig(Config $newConfig, string $key): void
    {
        // Set the config within the application
        self::$config[$key] = $newConfig;
    }

    /**
     * @inheritDoc
     */
    public function getContainer(): Container
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
    public function getDebugMode(): bool
    {
        return self::$config->app->debug;
    }

    /**
     * @inheritDoc
     */
    public function getEnvironment(): string
    {
        return self::$config->app->env;
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
     * @param class-string<ValkyrjaConfig>|null $dataConfig [optional] The config class to use
     */
    protected function bootstrapConfig(string|null $dataConfig = null): void
    {
        // Get the cache file
        $cacheFilePath = $this->getConfigCacheFilePath();

        // If we should use the config cache file
        if (is_file($cacheFilePath)) {
            // Get the config from the cache file's contents
            $this->setupFromConfigCacheFile($cacheFilePath);

            return;
        }

        $dataConfig ??= self::getEnvValue('DATA_CONFIG_CLASS', ValkyrjaConfig::class);

        assert(is_string($dataConfig) && is_a($dataConfig, ValkyrjaConfig::class, true));

        self::$config = $newDataConfig = new $dataConfig(env: self::getEnv());

        $this->setConfig($newDataConfig);
    }

    /**
     * Get cache file path.
     */
    protected function getConfigCacheFilePath(): string
    {
        $cacheFilePath = self::getEnvValue('CONFIG_CACHE_FILE_PATH', Directory::cachePath('config.php'));

        if (! is_string($cacheFilePath)) {
            throw new InvalidArgumentException('Config cache file path should be a string');
        }

        return $cacheFilePath;
    }

    /**
     * Setup the application from a cache file.
     */
    protected function setupFromConfigCacheFile(string $cacheFilePath): void
    {
        if (is_file($cacheFilePath)) {
            $cacheFileContents = file_get_contents($cacheFilePath);

            if ($cacheFileContents === '' || $cacheFileContents === false) {
                throw new RuntimeException('Invalid cache file contents');
            }

            self::$config = ValkyrjaConfig::fromSerializedString($cacheFileContents);

            $this->publishConfigAppProviders();

            return;
        }

        throw new InvalidArgumentException("Invalid $cacheFilePath provided");
    }

    /**
     * Publish app providers.
     */
    protected function publishConfigAppProviders(): void
    {
        foreach (self::$config->app->providers as $provider) {
            /** @var Provider $provider */
            // App providers are NOT deferred
            $provider::publish($this);
        }
    }

    /**
     * Publish config providers.
     */
    protected function publishConfigProviders(): void
    {
        $config = self::$config;

        /** @var class-string<ConfigProvider> $provider */
        foreach ($config->config->providers as $provider) {
            // Config providers are NOT deferred
            $provider::publish($config);
        }
    }
}
