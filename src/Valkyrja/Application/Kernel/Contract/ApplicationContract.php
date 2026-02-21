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

use Valkyrja\Application\Provider\Contract\ProviderContract;
use Valkyrja\Cli\Routing\Provider\Contract\ProviderContract as CliProvider;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Contract\ProviderContract as ContainerProvider;
use Valkyrja\Event\Provider\Contract\ProviderContract as EventProvider;
use Valkyrja\Http\Routing\Provider\Contract\ProviderContract as HttpProvider;

interface ApplicationContract
{
    /**
     * Get the container.
     */
    public function getContainer(): ContainerContract;

    /**
     * Get the registered component providers.
     *
     * @return class-string<ProviderContract>[]
     */
    public function getProviders(): array;

    /**
     * Get all the registered components' container service providers.
     *
     * @return class-string<ContainerProvider>[]
     */
    public function getContainerProviders(): array;

    /**
     * Get all the registered components' event providers.
     *
     * @return class-string<EventProvider>[]
     */
    public function getEventProviders(): array;

    /**
     * Get all the registered components' cli providers.
     *
     * @return class-string<CliProvider>[]
     */
    public function getCliProviders(): array;

    /**
     * Get all the registered components' http providers.
     *
     * @return class-string<HttpProvider>[]
     */
    public function getHttpProviders(): array;

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
