<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Console\Cacheables;

use ReflectionException;
use Valkyrja\Application\Application;
use Valkyrja\Config\Configs\ConsoleConfig;
use Valkyrja\Console\Annotation\CommandAnnotator;
use Valkyrja\Console\Command;
use Valkyrja\Dispatcher\Exceptions\InvalidClosureException;
use Valkyrja\Dispatcher\Exceptions\InvalidDispatchCapabilityException;
use Valkyrja\Dispatcher\Exceptions\InvalidFunctionException;
use Valkyrja\Dispatcher\Exceptions\InvalidMethodException;
use Valkyrja\Dispatcher\Exceptions\InvalidPropertyException;
use Valkyrja\Support\Cacheables\Cacheable;
use Valkyrja\Support\Providers\ProvidersAwareTrait;

/**
 * Trait CacheableConsole.
 *
 * @author Melech Mizrachi
 */
trait CacheableConsole
{
    use Cacheable;
    use ProvidersAwareTrait;

    /**
     * The commands.
     *
     * @var Command[]
     */
    protected static array $commands = [];
    /**
     * The command paths.
     *
     * @var string[]
     */
    protected static array $paths = [];
    /**
     * The commands by name.
     *
     * @var string[]
     */
    protected static array $namedCommands = [];
    /**
     * The application.
     *
     * @var Application
     */
    protected Application $app;

    /**
     * Get the application.
     *
     * @return Application
     */
    protected function getApplication(): Application
    {
        return $this->app;
    }

    /**
     * Get the config.
     *
     * @return ConsoleConfig|array
     */
    protected function getConfig()
    {
        return $this->app->config()['console'];
    }

    /**
     * Setup the console from cache.
     *
     * @param ConsoleConfig|array $config
     *
     * @return void
     */
    protected function setupFromCache(array $config): void
    {
        /** @var CacheConfig $cache */
        $cache = $config['cache'] ?? require $config['cacheFilePath'];

        self::$commands      = unserialize(
            base64_decode($cache['commands'], true),
            [
                'allowed_classes' => [
                    Command::class,
                ],
            ]
        );
        self::$paths         = $cache['paths'];
        self::$namedCommands = $cache['namedCommands'];
        self::$provided      = $cache['provided'];
    }

    /**
     * Set not cached.
     *
     * @param ConsoleConfig|object $config
     *
     * @return void
     */
    protected function setupNotCached(object $config): void
    {
        self::$paths         = [];
        self::$commands      = [];
        self::$namedCommands = [];

        // Setup command providers
        $this->setupCommandProviders($config);
    }

    /**
     * Setup annotations.
     *
     * @param ConsoleConfig|object $config
     *
     * @throws ReflectionException
     *
     * @return void
     */
    protected function setupAnnotations(object $config): void
    {
        /** @var CommandAnnotator $commandAnnotations */
        $commandAnnotations = $this->app->container()->getSingleton(CommandAnnotator::class);

        // Get all the annotated commands from the list of handlers
        // Iterate through the commands
        foreach ($commandAnnotations->getCommands(...$config->handlers) as $command) {
            // Set the service
            $this->addCommand($command);
        }
    }

    /**
     * Get a cacheable representation of the commands.
     *
     * @return CacheConfig|object
     */
    public function getCacheable(): object
    {
        $this->setup(true, false);

        $config                = new CacheConfig();
        $config->commands      = base64_encode(serialize(self::$commands));
        $config->paths         = self::$paths;
        $config->namedCommands = self::$namedCommands;
        $config->provided      = self::$provided;

        return $config;
    }

    /**
     * Setup command providers.
     *
     * @param ConsoleConfig|object $config
     *
     * @return void
     */
    protected function setupCommandProviders(object $config): void
    {
        // Iterate through all the providers
        foreach ($config->providers as $provider) {
            $this->register($provider);
        }

        // If this is not a dev environment
        if (! $this->app->debug()) {
            return;
        }

        // Iterate through all the providers
        foreach ($config->devProviders as $provider) {
            $this->register($provider);
        }
    }

    /**
     * Add a new command.
     *
     * @param Command $command The command
     *
     * @throws InvalidDispatchCapabilityException
     * @throws InvalidFunctionException
     * @throws InvalidMethodException
     * @throws InvalidPropertyException
     * @throws InvalidClosureException
     *
     * @return void
     */
    abstract public function addCommand(Command $command): void;
}
