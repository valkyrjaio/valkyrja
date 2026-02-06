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

namespace Valkyrja\Application\Kernel\Contract;

use Valkyrja\Application\Provider\Provider;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Provider as ContainerProvider;

interface ApplicationContract
{
    /**
     * Get the container.
     */
    public function getContainer(): ContainerContract;

    /**
     * Get the registered component providers.
     *
     * @return class-string<Provider>[]
     */
    public function getProviders(): array;

    /**
     * Get all the registered components' container service providers.
     *
     * @return class-string<ContainerProvider>[]
     */
    public function getContainerProviders(): array;

    /**
     * Get all the registered components' event listeners.
     *
     * @return class-string[]
     */
    public function getEventListeners(): array;

    /**
     * Get all the registered components' cli controllers.
     *
     * @return class-string[]
     */
    public function getCliControllers(): array;

    /**
     * Get all the registered components' http controllers.
     *
     * @return class-string[]
     */
    public function getHttpControllers(): array;

    /**
     * Whether the application is running in debug mode or not.
     */
    public function getDebugMode(): bool;

    /**
     * Get the environment with which the application is running in.
     */
    public function getEnvironment(): string;

    /**
     * Get the application version.
     */
    public function getVersion(): string;
}
