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
use Valkyrja\Config\Enums\ConfigKey;
use Valkyrja\Config\Enums\ConfigKeyPart;
use Valkyrja\Console\Annotation\CommandAnnotations;
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
     * The application.
     *
     * @var Application
     */
    protected Application $app;

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
     * @return array
     */
    protected function getConfig(): array
    {
        return $this->app->config(ConfigKeyPart::CONSOLE);
    }

    /**
     * Set not cached.
     *
     * @return void
     */
    protected function setupNotCached(): void
    {
        self::$paths         = [];
        self::$commands      = [];
        self::$namedCommands = [];

        // Setup command providers
        $this->setupCommandProviders();
    }

    /**
     * Setup the console from cache.
     *
     * @return void
     */
    protected function setupFromCache(): void
    {
        // Set the application console with said file
        $cache = $this->app->config(ConfigKey::CACHE_CONSOLE)
            ?? require $this->app->config(ConfigKey::CONSOLE_CACHE_FILE_PATH);

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
     * Setup command providers.
     *
     * @return void
     */
    protected function setupCommandProviders(): void
    {
        /** @var string[] $providers */
        $providers = $this->app->config(ConfigKey::CONSOLE_PROVIDERS);

        // Iterate through all the providers
        foreach ($providers as $provider) {
            $this->register($provider);
        }

        // If this is not a dev environment
        if (! $this->app->debug()) {
            return;
        }

        /** @var string[] $devProviders */
        $devProviders = $this->app->config(ConfigKey::CONSOLE_DEV_PROVIDERS);

        // Iterate through all the providers
        foreach ($devProviders as $provider) {
            $this->register($provider);
        }
    }

    /**
     * Setup annotations.
     *
     * @throws InvalidClosureException
     * @throws InvalidDispatchCapabilityException
     * @throws InvalidFunctionException
     * @throws InvalidMethodException
     * @throws InvalidPropertyException
     * @throws ReflectionException
     *
     * @return void
     */
    protected function setupAnnotations(): void
    {
        /** @var CommandAnnotations $commandAnnotations */
        $commandAnnotations = $this->app->container()->getSingleton(CommandAnnotations::class);

        // Get all the annotated commands from the list of handlers
        $commands = $commandAnnotations->getCommands(...$this->app->config(ConfigKey::CONSOLE_HANDLERS));

        // Iterate through the commands
        foreach ($commands as $command) {
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
