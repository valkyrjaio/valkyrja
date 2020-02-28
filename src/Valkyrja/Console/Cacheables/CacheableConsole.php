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
use Valkyrja\Config\Enums\ConfigKeyPart;
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
     * @return ConsoleConfig
     */
    protected function getConfig(): ConsoleConfig
    {
        return $this->app->config()->console;
    }

    /**
     * Set not cached.
     *
     * @param ConsoleConfig $config
     *
     * @return void
     */
    protected function setupNotCached(ConsoleConfig $config): void
    {
        self::$paths         = [];
        self::$commands      = [];
        self::$namedCommands = [];

        // Setup command providers
        $this->setupCommandProviders($config);
    }

    /**
     * Setup the console from cache.
     *
     * @param ConsoleConfig $config
     *
     * @return void
     */
    protected function setupFromCache(ConsoleConfig $config): void
    {
        // Set the application console with said file
        $cache = $config->cache ?? require $config->cacheFilePath;

        self::$commands      = unserialize(
            base64_decode($cache[ConfigKeyPart::COMMANDS], true),
            [
                'allowed_classes' => [
                    Command::class,
                ],
            ]
        );
        self::$paths         = $cache[ConfigKeyPart::PATHS];
        self::$namedCommands = $cache[ConfigKeyPart::NAMED_COMMANDS];
        self::$provided      = $cache[ConfigKeyPart::PROVIDED];
    }

    /**
     * Setup annotations.
     *
     * @param ConsoleConfig $config
     *
     * @throws ReflectionException
     * @return void
     */
    protected function setupAnnotations(ConsoleConfig $config): void
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
     * @return array
     */
    public function getCacheable(): array
    {
        $this->setup(true, false);

        return [
            ConfigKeyPart::COMMANDS       => base64_encode(serialize(self::$commands)),
            ConfigKeyPart::PATHS          => self::$paths,
            ConfigKeyPart::NAMED_COMMANDS => self::$namedCommands,
            ConfigKeyPart::PROVIDED       => self::$provided,
        ];
    }

    /**
     * Setup command providers.
     *
     * @param ConsoleConfig $config
     *
     * @return void
     */
    protected function setupCommandProviders(ConsoleConfig $config): void
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
