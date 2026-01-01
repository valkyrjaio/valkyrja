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

namespace Valkyrja\Application\Kernel;

use Override;
use Valkyrja\Application\Data\Config;
use Valkyrja\Application\Data\Data;
use Valkyrja\Application\Env\Env;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Application\Provider\Provider;
use Valkyrja\Application\Throwable\Exception\RuntimeException;
use Valkyrja\Cli\Routing\Data\Data as CliData;
use Valkyrja\Container\Data\Data as ContainerData;
use Valkyrja\Container\Manager\Container as ContainerManager;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Event\Data\Data as EventData;
use Valkyrja\Http\Routing\Data\Data as HttpData;

class Valkyrja implements ApplicationContract
{
    /**
     * Application env.
     */
    protected Env $env;

    /**
     * Application config.
     */
    protected Config|null $config = null;

    /**
     * Application data.
     */
    protected Data|null $data = null;

    /**
     * Get the instance of the container.
     */
    protected ContainerContract $container;

    /**
     * Whether the application was setup.
     */
    protected bool $setup = false;

    public function __construct(Env $env, Config|Data $configData = new Config())
    {
        $this->setup(env: $env, configData: $configData);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function setup(Env $env, Config|Data $configData = new Config(), bool $force = false): void
    {
        // If the application was already setup, no need to do it again
        if ($this->setup && ! $force) {
            return;
        }

        // Avoid re-setting up the app later
        $this->setup = true;

        $this->setEnv(env: $env);

        $this->bootstrapContainer();

        if ($configData instanceof Config) {
            $this->bootstrapConfig(config: $configData);
        } else {
            $this->bootstrapData(data: $configData);
        }

        $this->bootstrapServices();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function addComponent(string $component): void
    {
        if ($this->config === null) {
            throw new RuntimeException('Cannot add components to an app setup with Data');
        }

        $config = $this->config;

        $this->addComponentContainerAliases($config, $component);
        $this->addComponentContainerServices($config, $component);
        $this->addComponentContainerProviders($component);
        $this->addComponentEventListeners($config, $component);
        $this->addComponentCliControllers($config, $component);
        $this->addComponentHttpControllers($config, $component);
    }

    /**
     * @inheritDoc
     *
     * @return Env
     */
    #[Override]
    public function getEnv(): Env
    {
        return $this->env;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function setEnv(Env $env): void
    {
        // Set the env class to use
        $this->env = $env;

        $this->bootstrapTimezone();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getContainer(): ContainerContract
    {
        return $this->container;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function setContainer(ContainerContract $container): static
    {
        $this->container = $container;

        return $this;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getDebugMode(): bool
    {
        /** @var bool $debugMode */
        $debugMode = $this->env::APP_DEBUG_MODE;

        return $debugMode;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getEnvironment(): string
    {
        /** @var non-empty-string $env */
        $env = $this->env::APP_ENV;

        return $env;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getVersion(): string
    {
        /** @var non-empty-string $version */
        $version = $this->env::APP_VERSION;

        return $version;
    }

    /**
     * Bootstrap the config.
     */
    protected function bootstrapConfig(Config $config): void
    {
        $this->config = $config;

        $this->bootstrapComponents();
    }

    /**
     * Bootstrap all the components types for the application.
     */
    protected function bootstrapComponents(): void
    {
        $this->bootstrapRequiredComponents();
        $this->bootstrapCoreComponents();
        $this->bootstrapOptionalComponents();
        $this->bootstrapCustomComponents();
    }

    /**
     * Bootstrap all the required components for the application to run.
     */
    protected function bootstrapRequiredComponents(): void
    {
        /** @var class-string<Provider>[] $components */
        $components = $this->env::APP_REQUIRED_COMPONENTS;

        foreach ($components as $component) {
            $this->addComponent(component: $component);
        }
    }

    /**
     * Bootstrap all the core components to run specific parts of the application.
     */
    protected function bootstrapCoreComponents(): void
    {
        /** @var class-string<Provider>[] $components */
        $components = $this->env::APP_CORE_COMPONENTS;

        foreach ($components as $component) {
            $this->addComponent(component: $component);
        }
    }

    /**
     * Bootstrap all the optional components.
     */
    protected function bootstrapOptionalComponents(): void
    {
        /** @var class-string<Provider>[] $components */
        $components = $this->env::APP_COMPONENTS;

        foreach ($components as $component) {
            $this->addComponent(component: $component);
        }
    }

    /**
     * Bootstrap all the custom components.
     */
    protected function bootstrapCustomComponents(): void
    {
        /** @var class-string<Provider>[] $components */
        $components = $this->env::APP_CUSTOM_COMPONENTS;

        foreach ($components as $component) {
            $this->addComponent(component: $component);
        }
    }

    /**
     * Add a component's container aliases.
     *
     * @param class-string<Provider> $component The component class
     */
    protected function addComponentContainerAliases(Config $config, string $component): void
    {
        $config->aliases = [
            ...$config->aliases,
            ...$component::getContainerAliases(),
        ];
    }

    /**
     * Add a component's container services.
     *
     * @param class-string<Provider> $component The component class
     */
    protected function addComponentContainerServices(Config $config, string $component): void
    {
        $config->services = [
            ...$config->services,
            ...$component::getContainerServices(),
        ];
    }

    /**
     * Add a component's container services.
     *
     * @param class-string<Provider> $component The component class
     */
    protected function addComponentContainerProviders(string $component): void
    {
        array_map(
            [$this->container, 'register'],
            $component::getContainerProviders(),
        );
    }

    /**
     * Add a component's event listeners.
     *
     * @param class-string<Provider> $component The component class
     */
    protected function addComponentEventListeners(Config $config, string $component): void
    {
        if ($this->env::APP_ADD_EVENT_LISTENERS) {
            $config->listeners = [
                ...$config->listeners,
                ...$component::getEventListeners(),
            ];
        }
    }

    /**
     * Add a component's cli controllers.
     *
     * @param class-string<Provider> $component The component class
     */
    protected function addComponentCliControllers(Config $config, string $component): void
    {
        if ($this->env::APP_ADD_CLI_CONTROLLERS) {
            $config->commands = [
                ...$config->commands,
                ...$component::getCliControllers(),
            ];
        }
    }

    /**
     * Add a component's http controllers.
     *
     * @param class-string<Provider> $component The component class
     */
    protected function addComponentHttpControllers(Config $config, string $component): void
    {
        if ($this->env::APP_ADD_HTTP_CONTROLLERS) {
            $config->controllers = [
                ...$config->controllers,
                ...$component::getHttpControllers(),
            ];
        }
    }

    /**
     * Bootstrap the data.
     */
    protected function bootstrapData(Data $data): void
    {
        $this->data = $data;
    }

    /**
     * Create the container.
     */
    protected function bootstrapContainer(): void
    {
        $container = new ContainerManager();

        $this->setContainer($container);
    }

    /**
     * Bootstrap container services.
     */
    protected function bootstrapServices(): void
    {
        $container = $this->container;

        $container->setSingleton(ApplicationContract::class, $this);
        $container->setSingleton(Env::class, $this->env);
        $container->setSingleton(ContainerContract::class, $container);

        if ($this->data !== null) {
            $container->setSingleton(ContainerData::class, $this->data->container);
            $container->setSingleton(EventData::class, $this->data->event);
            $container->setSingleton(CliData::class, $this->data->cli);
            $container->setSingleton(HttpData::class, $this->data->http);

            $container->setFromData($this->data->container);
        }

        if ($this->config !== null) {
            $container->setSingleton(Config::class, $this->config);

            $data = $container->getSingleton(ContainerData::class);
            $container->setFromData($data);
        }
    }

    /**
     * Bootstrap the timezone.
     */
    protected function bootstrapTimezone(): void
    {
        /** @var non-empty-string $timezone */
        $timezone = $this->env::APP_TIMEZONE;

        date_default_timezone_set($timezone);
    }
}
