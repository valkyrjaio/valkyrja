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

use Valkyrja\Application\Config\Valkyrja as ValkyrjaConfig;
use Valkyrja\Application\Env;
use Valkyrja\Application\Support\Component;
use Valkyrja\Config\Config;
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
     * @var string
     */
    public const string VERSION = '2025.1.0';

    /**
     * Setup the application.
     *
     * @param class-string<ValkyrjaConfig> $config The config to use
     * @param bool                         $force  [optional] Whether to force a setup
     *
     * @return void
     */
    public function setup(string $config, bool $force = false): void;

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
     * @return class-string<Env>
     */
    public function getEnv(): string;

    /**
     * Set the environment variables class.
     *
     * @param class-string<Env> $env The env file to use
     *
     * @return void
     */
    public function setEnv(string $env): void;

    /**
     * Get an environment variable.
     *
     * @param non-empty-string $name The env name
     */
    public function getEnvValue(string $name, mixed $default = null): mixed;

    /**
     * Add to the global config array.
     */
    public function setConfig(ValkyrjaConfig $config): static;

    /**
     * Get the config.
     */
    public function getConfig(): ValkyrjaConfig;

    /**
     * Get a config value.
     *
     * @param non-empty-string $name The config name
     */
    public function getConfigValue(string $name, mixed $default = null): mixed;

    /**
     * Add to the global config array.
     *
     * @param non-empty-string $name The config name
     */
    public function addConfig(string $name, Config $config): void;

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
