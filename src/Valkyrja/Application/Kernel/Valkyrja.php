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
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Application\Provider\Contract\ComponentProviderContract;
use Valkyrja\Cli\Routing\Provider\Contract\CliRouteProviderContract;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Contract\ServiceProviderContract;
use Valkyrja\Event\Provider\Contract\ListenerProviderContract;
use Valkyrja\Http\Routing\Provider\Contract\HttpRouteProviderContract;

class Valkyrja implements ApplicationContract
{
    /** @var class-string<ComponentProviderContract>[] */
    protected array $providers = [];
    /** @var class-string<ServiceProviderContract>[] */
    protected array $serviceProviders = [];
    /** @var class-string<ListenerProviderContract>[] */
    protected array $eventProviders = [];
    /** @var class-string<CliRouteProviderContract>[] */
    protected array $cliRouteProviders = [];
    /** @var class-string<HttpRouteProviderContract>[] */
    protected array $httpRouteProviders = [];

    public function __construct(
        protected ContainerContract $container,
        protected Config $config = new Config(),
    ) {
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
    public function publishProviderCallbacks(): void
    {
        foreach ($this->config->callbacks as $callback) {
            $callback($this);
        }
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getProviders(): array
    {
        if ($this->providers !== []) {
            return $this->providers;
        }

        $providers = [];

        foreach ($this->config->providers as $provider) {
            $providers[] = $provider::getComponentProviders($this);
            // Ensure that the dependencies are loaded before the provider requiring them
            $providers[] = [$provider];
        }

        $this->providers = array_unique(array_merge(...$providers));

        return $this->providers;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getContainerProviders(): array
    {
        if ($this->serviceProviders !== []) {
            return $this->serviceProviders;
        }

        $providers = [];

        foreach ($this->getProviders() as $provider) {
            $providers[] = $provider::getContainerProviders($this);
        }

        $this->serviceProviders = array_unique(array_merge(...$providers));

        return $this->serviceProviders;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getEventProviders(): array
    {
        if ($this->eventProviders !== []) {
            return $this->eventProviders;
        }

        $providers = [];

        foreach ($this->getProviders() as $provider) {
            $providers[] = $provider::getEventProviders($this);
        }

        $this->eventProviders = array_unique(array_merge(...$providers));

        return $this->eventProviders;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getCliProviders(): array
    {
        if ($this->cliRouteProviders !== []) {
            return $this->cliRouteProviders;
        }

        $providers = [];

        foreach ($this->getProviders() as $provider) {
            $providers[] = $provider::getCliProviders($this);
        }

        $this->cliRouteProviders = array_unique(array_merge(...$providers));

        return $this->cliRouteProviders;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getHttpProviders(): array
    {
        if ($this->httpRouteProviders !== []) {
            return $this->httpRouteProviders;
        }

        $providers = [];

        foreach ($this->getProviders() as $provider) {
            $providers[] = $provider::getHttpProviders($this);
        }

        $this->httpRouteProviders = array_unique(array_merge(...$providers));

        return $this->httpRouteProviders;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getDebugMode(): bool
    {
        return $this->config->debugMode;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getEnvironment(): string
    {
        return $this->config->environment;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getVersion(): string
    {
        return $this->config->version;
    }

    /**
     * Bootstrap the timezone.
     */
    protected function bootstrapTimezone(): void
    {
        date_default_timezone_set($this->config->timezone);
    }
}
