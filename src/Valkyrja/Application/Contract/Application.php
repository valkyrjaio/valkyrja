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

use Valkyrja\Application\Env;
use Valkyrja\Config\Config as ConfigModel;
use Valkyrja\Config\Config\Config;
use Valkyrja\Config\Config\ValkyrjaDataConfig;
use Valkyrja\Console\Kernel\Contract\Kernel as ConsoleKernel;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Dispatcher\Contract\Dispatcher;
use Valkyrja\Event\Contract\Dispatcher as Events;
use Valkyrja\Http\Server\Contract\RequestHandler;

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
    public const VERSION = '1.0.0';

    /**
     * Get the application instance.
     *
     * @return Application
     */
    public static function app(): self;

    /**
     * Get an environment variable.
     *
     * @param string|null $key     [optional] The variable to get
     * @param mixed       $default [optional] The default value to return
     *
     * @return mixed
     */
    public static function env(string|null $key = null, mixed $default = null): mixed;

    /**
     * Get environment variables.
     *
     * @return class-string<Env>|null
     */
    public static function getEnv(): string|null;

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
     * @param class-string<Config>|null $config [optional] The config to use
     * @param bool                      $force  [optional] Whether to force a setup
     *
     * @return void
     */
    public function setup(string|null $config = null, string|null $dataConfig = null, bool $force = false): void;

    /**
     * Add to the global config array.
     *
     * @param Config $config The config to add
     *
     * @return static
     */
    public function withConfig(Config $config): static;

    /**
     * Add to the global config array.
     *
     * @param ValkyrjaDataConfig $config The config to add
     *
     * @return static
     */
    public function withDataConfig(ValkyrjaDataConfig $config): static;

    /**
     * Get the config.
     *
     * @param string|null $key     [optional] The key to get
     * @param mixed       $default [optional] The default value if the key is not found
     *
     * @return mixed
     */
    public function config(string|null $key = null, mixed $default = null): mixed;

    /**
     * Get the config.
     *
     * @param string|null $key     [optional] The key to get
     * @param mixed       $default [optional] The default value if the key is not found
     *
     * @return mixed
     */
    public function dataConfig(string|null $key = null, mixed $default = null): mixed;

    /**
     * Add to the global config array.
     *
     * @param ConfigModel $newConfig The new config to add
     * @param string      $key       The key to use
     *
     * @return void
     */
    public function addConfig(ConfigModel $newConfig, string $key): void;

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
     * Get the dispatcher instance.
     *
     * @return Dispatcher
     */
    public function dispatcher(): Dispatcher;

    /**
     * Get the events instance.
     *
     * @return Events
     */
    public function events(): Events;

    /**
     * Get the console kernel instance from the container.
     *
     * @return ConsoleKernel
     */
    public function consoleKernel(): ConsoleKernel;

    /**
     * Get the kernel instance from the container.
     *
     * @return RequestHandler
     */
    public function kernel(): RequestHandler;

    /**
     * Whether the application is running in debug mode or not.
     *
     * @return bool
     */
    public function debug(): bool;

    /**
     * Get the environment with which the application is running in.
     *
     * @return string
     */
    public function environment(): string;

    /**
     * Get the application version.
     *
     * @return string
     */
    public function version(): string;
}
