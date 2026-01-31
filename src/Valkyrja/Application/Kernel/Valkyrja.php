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
            $providers[] = $provider::getContainerProviders();
        }

        return array_merge(...$providers);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getEventListeners(): array
    {
        $listeners = [];

        foreach ($this->getProviders() as $provider) {
            $listeners[] = $provider::getEventListeners();
        }

        return array_merge(...$listeners);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getCliControllers(): array
    {
        $controllers = [];

        foreach ($this->getProviders() as $provider) {
            $controllers[] = $provider::getCliControllers();
        }

        return array_merge(...$controllers);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getHttpControllers(): array
    {
        $controllers = [];

        foreach ($this->getProviders() as $provider) {
            $controllers[] = $provider::getHttpControllers();
        }

        return array_merge(...$controllers);
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
