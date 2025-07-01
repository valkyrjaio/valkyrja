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

use Valkyrja\Application\Config as AppConfig;
use Valkyrja\Application\Config\ValkyrjaConfig;
use Valkyrja\Application\Constant\ComponentClass;
use Valkyrja\Application\Contract\Application;
use Valkyrja\Application\Exception\InvalidArgumentException;
use Valkyrja\Cli\Component as CliComponent;
use Valkyrja\Cli\Config as CliConfig;
use Valkyrja\Container\Component as ContainerComponent;
use Valkyrja\Container\Config as ContainerConfig;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Event\Component as EventComponent;
use Valkyrja\Event\Config as EventConfig;
use Valkyrja\Exception\RuntimeException;
use Valkyrja\Http\Component as HttpComponent;
use Valkyrja\Http\Config as HttpConfig;
use Valkyrja\Support\Config;
use Valkyrja\Support\Directory;

use function is_file;
use function is_string;

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
    protected string $env;

    /**
     * Application config.
     */
    protected ValkyrjaConfig $config;

    /**
     * Get the instance of the container.
     */
    protected Container $container;

    /**
     * Whether the application was setup.
     */
    protected bool $setup = false;

    /**
     * Application constructor.
     *
     * @param class-string<Env>            $env    The env file to use
     * @param class-string<ValkyrjaConfig> $config The config class to use
     */
    public function __construct(string $env, string $config)
    {
        $this->setEnv(env: $env);
        $this->setup(config: $config);
    }

    /**
     * @inheritDoc
     */
    public function setup(string $config, bool $force = false): void
    {
        // If the application was already setup, no need to do it again
        if ($this->setup && ! $force) {
            return;
        }

        // Avoid re-setting up the app later
        $this->setup = true;

        // Bootstrap debug capabilities
        $this->bootstrapConfig(config: $config);
    }

    /**
     * @inheritDoc
     */
    public function addComponent(string $component): void
    {
        $componentConfig = $component::getConfig();

        if ($componentConfig !== null) {
            $name = strtolower($component::getName());

            $this->addConfig(
                name: $name,
                config: $componentConfig::fromEnv($this->env)
            );
        }

        $this->config->container->aliases = [
            ...$this->config->container->aliases,
            ...$component::getContainerAliases(),
        ];

        $this->config->container->services = [
            ...$this->config->container->services,
            ...$component::getContainerServices(),
        ];

        $this->config->container->contextAliases = [
            ...$this->config->container->contextAliases,
            ...$component::getContainerContextAliases(),
        ];

        $this->config->container->contextServices = [
            ...$this->config->container->contextServices,
            ...$component::getContainerContextServices(),
        ];

        $this->config->container->providers = [
            ...$this->config->container->providers,
            ...$component::getContainerProviders(),
        ];

        $this->config->event->listeners = [
            ...$this->config->event->listeners,
            ...$component::getEventListeners(),
        ];

        $this->config->cli->routing->controllers = [
            ...$this->config->cli->routing->controllers,
            ...$component::getCliControllers(),
        ];

        $this->config->http->routing->controllers = [
            ...$this->config->http->routing->controllers,
            ...$component::getHttpControllers(),
        ];
    }

    /**
     * @inheritDoc
     *
     * @return class-string<Env>
     */
    public function getEnv(): string
    {
        return $this->env;
    }

    /**
     * @inheritDoc
     */
    public function setEnv(string $env): void
    {
        if (class_exists($env)) {
            // Set the env class to use
            $this->env = $env;

            return;
        }

        throw new InvalidArgumentException('Env must be a valid class');
    }

    /**
     * @inheritDoc
     */
    public function setConfig(ValkyrjaConfig $config): static
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getConfig(): ValkyrjaConfig
    {
        return $this->config;
    }

    /**
     * @inheritDoc
     */
    public function addConfig(string $name, Config $config): void
    {
        if (! isset($this->config->$name)) {
            // Set the config within the application
            $this->config->$name = $config;
        }
    }

    /**
     * @inheritDoc
     */
    public function getContainer(): Container
    {
        return $this->container;
    }

    /**
     * @inheritDoc
     */
    public function setContainer(Container $container): static
    {
        $this->container = $container;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getDebugMode(): bool
    {
        return $this->config->app->debugMode;
    }

    /**
     * @inheritDoc
     */
    public function getEnvironment(): string
    {
        return $this->config->app->env;
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
     * @param class-string<ValkyrjaConfig> $config The config class to use
     */
    protected function bootstrapConfig(string $config): void
    {
        // Get the cache file
        $cacheFilePath = $this->getConfigCacheFilePath();

        // If we should use the config cache file
        if (is_file($cacheFilePath)) {
            // Get the config from the cache file's contents
            $this->setupFromConfigCacheFile($cacheFilePath);

            return;
        }

        if (is_a($config, ValkyrjaConfig::class, true)) {
            $this->config = $newConfig = new $config(env: $this->getEnv());

            $this->setConfig($newConfig);

            $this->bootstrapComponents();

            return;
        }

        throw new InvalidArgumentException('Config must be an instance of AppConfig');
    }

    /**
     * Get cache file path.
     */
    protected function getConfigCacheFilePath(): string
    {
        $cacheFilePath = $this->env::APP_CACHE_FILE_PATH
            ?? Directory::cachePath('config.php');

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

            $this->config = ValkyrjaConfig::fromSerializedString(cached: $cacheFileContents);

            return;
        }

        throw new InvalidArgumentException("Invalid $cacheFilePath provided");
    }

    /**
     * Bootstrap all the components set in the config.
     */
    protected function bootstrapComponents(): void
    {
        $env = $this->env;

        // Bootstrap required configs
        $appConfig       = AppConfig::fromEnv(env: $env);
        $containerConfig = ContainerConfig::fromEnv(env: $env);
        $cliConfig       = CliConfig::fromEnv(env: $env);
        $eventConfig     = EventConfig::fromEnv(env: $env);
        $httpConfig      = HttpConfig::fromEnv(env: $env);

        $this->addConfig(name: Component::getName(), config: $appConfig);
        $this->addConfig(name: ContainerComponent::getName(), config: $containerConfig);
        $this->addConfig(name: CliComponent::getName(), config: $cliConfig);
        $this->addConfig(name: EventComponent::getName(), config: $eventConfig);
        $this->addConfig(name: HttpComponent::getName(), config: $httpConfig);

        // All all the components
        $this->addComponent(component: ComponentClass::CONTAINER);
        $this->addComponent(component: ComponentClass::APPLICATION);
        $this->addComponent(component: ComponentClass::ATTRIBUTE);
        $this->addComponent(component: ComponentClass::CLI);
        $this->addComponent(component: ComponentClass::DISPATCHER);
        $this->addComponent(component: ComponentClass::EVENT);
        $this->addComponent(component: ComponentClass::HTTP);
        $this->addComponent(component: ComponentClass::REFLECTION);

        foreach ($this->config->app->components as $component) {
            $this->addComponent($component);
        }

        $this->config->setConfigFromEnv(env: $env);
    }
}
