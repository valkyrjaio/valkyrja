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
     * The Application framework version.
     *
     * @var non-empty-string
     */
    public const string VERSION = '25.26.1';

    /**
     * The Application framework version build datetime.
     *
     * @var non-empty-string
     */
    public const string VERSION_BUILD_DATE_TIME = 'February 2 2026 17:21:52 MST';

    /**
     * The valkyrja framework ascii art.
     *
     * @var non-empty-string
     */
    public const string ASCII = <<<'TEXT'
                     _ _               _
         /\   /\__ _| | | ___   _ _ __(_) __ _
         \ \ / / _` | | |/ / | | | '__| |/ _` |
          \ V / (_| | |   <| |_| | |  | | (_| |
           \_/ \__,_|_|_|\_\\__, |_| _/ |\__,_|
                            |___/   |__/
        TEXT;

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
