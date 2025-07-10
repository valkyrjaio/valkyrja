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

use Override;
use Valkyrja\Application\Constant\ComponentClass;
use Valkyrja\Application\Contract\Application;
use Valkyrja\Application\Exception\RuntimeException;
use Valkyrja\Application\Support\Component;
use Valkyrja\Cli\Routing\Data as CliData;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Data as ContainerData;
use Valkyrja\Event\Data as EventData;
use Valkyrja\Http\Routing\Data as HttpData;

/**
 * Class Valkyrja.
 *
 * @author Melech Mizrachi
 */
class Valkyrja implements Application
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
    protected Container $container;

    /**
     * Whether the application was setup.
     */
    protected bool $setup = false;

    /**
     * Application constructor.
     */
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
        $this->bootstrapTimezone();
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

        $this->config->aliases = [
            ...$this->config->aliases,
            ...$component::getContainerAliases(),
        ];

        $this->config->services = [
            ...$this->config->services,
            ...$component::getContainerServices(),
        ];

        array_map(
            [$this->container, 'register'],
            $component::getContainerProviders(),
        );

        $this->config->listeners = [
            ...$this->config->listeners,
            ...$component::getEventListeners(),
        ];

        $this->config->commands = [
            ...$this->config->commands,
            ...$component::getCliControllers(),
        ];

        $this->config->controllers = [
            ...$this->config->controllers,
            ...$component::getHttpControllers(),
        ];
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
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getContainer(): Container
    {
        return $this->container;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function setContainer(Container $container): static
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
     * Bootstrap all the components set in the config.
     */
    protected function bootstrapComponents(): void
    {
        // All all the components
        $this->addComponent(component: ComponentClass::CONTAINER);
        $this->addComponent(component: ComponentClass::APPLICATION);
        $this->addComponent(component: ComponentClass::ATTRIBUTE);
        $this->addComponent(component: ComponentClass::CLI);
        $this->addComponent(component: ComponentClass::DISPATCHER);
        $this->addComponent(component: ComponentClass::EVENT);
        $this->addComponent(component: ComponentClass::HTTP);
        $this->addComponent(component: ComponentClass::REFLECTION);

        /** @var class-string<Component>[] $components */
        $components = $this->env::APP_COMPONENTS;

        foreach ($components as $component) {
            $this->addComponent($component);
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
        $container = new \Valkyrja\Container\Container();

        $this->setContainer($container);
    }

    /**
     * Bootstrap container services.
     */
    protected function bootstrapServices(): void
    {
        $container = $this->container;

        $container->setSingleton(Application::class, $this);
        $container->setSingleton(Env::class, $this->env);
        $container->setSingleton(Container::class, $container);

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
