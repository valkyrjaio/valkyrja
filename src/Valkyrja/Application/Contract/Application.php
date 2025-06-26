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
    public const string VERSION = '1.0.0';

    /**
     * Get the application instance.
     *
     * @return Application
     */
    public static function app(): self;

    /**
     * Get an environment variable.
     */
    public static function getEnvValue(string $key, mixed $default = null): mixed;

    /**
     * Get environment variables.
     *
     * @return class-string<Env>
     */
    public static function getEnv(): string;

    /**
     * Set the environment variables class.
     *
     * @param class-string<Env> $env The env file to use
     *
     * @return void
     */
    public static function setEnv(string $env): void;

    /**
     * Setup the application.
     *
     * @param class-string<ValkyrjaConfig>|null $dataConfig [optional] The config to use
     * @param bool                              $force      [optional] Whether to force a setup
     *
     * @return void
     */
    public function setup(string|null $dataConfig = null, bool $force = false): void;

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
     */
    public function getConfigValue(string $key, mixed $default = null): mixed;

    /**
     * Add to the global config array.
     */
    public function addConfig(Config $newConfig, string $key): void;

    /**
     * Get the container instance.
     *
     * @return Container
     */
    public function container(): Container;

    /**
     * Set the container instance.
     *
     * @param Container $container The container instance
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
