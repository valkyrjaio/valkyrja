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

namespace Valkyrja\Application\Contract;

use Valkyrja\Application\Config;
use Valkyrja\Application\Data;
use Valkyrja\Application\Env;
use Valkyrja\Application\Support\Component;
use Valkyrja\Container\Contract\Container;

/**
 * Interface Application.
 *
 * @author Melech Mizrachi
 */
interface Application
{
    /**
     * The Application framework version.
     *
     * @var non-empty-string
     */
    public const string VERSION = '25.3.1';

    /**
     * The Application framework version build datetime.
     *
     * @var non-empty-string
     */
    public const string VERSION_BUILD_DATE_TIME = 'December 17 2025 23:03:00 MST';

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
     * Setup the application.
     *
     * @param Env         $env        The env to use
     * @param Config|Data $configData The config to use
     * @param bool        $force      [optional] Whether to force a setup
     *
     * @return void
     */
    public function setup(Env $env, Config|Data $configData = new Config(), bool $force = false): void;

    /**
     * Add a component to the application.
     *
     * @param class-string<Component> $component The component class
     *
     * @return void
     */
    public function addComponent(string $component): void;

    /**
     * Get environment variables.
     *
     * @return Env
     */
    public function getEnv(): Env;

    /**
     * Set the environment variables class.
     *
     * @param Env $env The env file to use
     *
     * @return void
     */
    public function setEnv(Env $env): void;

    /**
     * Get the container.
     *
     * @return Container
     */
    public function getContainer(): Container;

    /**
     * Set the container.
     *
     * @param Container $container The container
     *
     * @return static
     */
    public function setContainer(Container $container): static;

    /**
     * Whether the application is running in debug mode or not.
     *
     * @return bool
     */
    public function getDebugMode(): bool;

    /**
     * Get the environment with which the application is running in.
     *
     * @return string
     */
    public function getEnvironment(): string;

    /**
     * Get the application version.
     *
     * @return string
     */
    public function getVersion(): string;
}
