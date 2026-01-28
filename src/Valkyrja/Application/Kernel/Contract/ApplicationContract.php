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

use Valkyrja\Application\Env\Env;
use Valkyrja\Application\Provider\Provider;
use Valkyrja\Container\Manager\Contract\ContainerContract;

interface ApplicationContract
{
    /**
     * The Application framework version.
     *
     * @var non-empty-string
     */
    public const string VERSION = '25.23.0';

    /**
     * The Application framework version build datetime.
     *
     * @var non-empty-string
     */
    public const string VERSION_BUILD_DATE_TIME = 'January 27 2026 22:31:34 MST';

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
     * Add a component to the application.
     *
     * @param class-string<Provider> $component The component class
     */
    public function addComponent(string $component): void;

    /**
     * Get environment variables.
     */
    public function getEnv(): Env;

    /**
     * Set the environment variables class.
     *
     * @param Env $env The env file to use
     */
    public function setEnv(Env $env): void;

    /**
     * Get the container.
     */
    public function getContainer(): ContainerContract;

    /**
     * Set the container.
     *
     * @param ContainerContract $container The container
     */
    public function setContainer(ContainerContract $container): static;

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
