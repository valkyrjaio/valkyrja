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
use Valkyrja\Container\Manager\Contract\ContainerContract;

class Valkyrja implements ApplicationContract
{
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
    public function getProviders(): array
    {
        return $this->config->providers;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getContainerProviders(): array
    {
        $providers = [];

        foreach ($this->getProviders() as $provider) {
            $providers[] = $provider::getContainerProviders($this);
        }

        return array_merge(...$providers);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getEventProviders(): array
    {
        $providers = [];

        foreach ($this->getProviders() as $provider) {
            $providers[] = $provider::getEventProviders($this);
        }

        return array_merge(...$providers);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getCliProviders(): array
    {
        $providers = [];

        foreach ($this->getProviders() as $provider) {
            $providers[] = $provider::getCliProviders($this);
        }

        return array_merge(...$providers);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getHttpProviders(): array
    {
        $providers = [];

        foreach ($this->getProviders() as $provider) {
            $providers[] = $provider::getHttpProviders($this);
        }

        return array_merge(...$providers);
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
