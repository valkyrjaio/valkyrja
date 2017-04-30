<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Console;

use Valkyrja\Console\Exceptions\CommandNotFound;
use Valkyrja\Contracts\Application;
use Valkyrja\Contracts\Console\Annotations\CommandAnnotations;
use Valkyrja\Contracts\Console\Console as ConsoleContract;
use Valkyrja\Dispatcher\Dispatcher;

/**
 * Class Console
 *
 * @package Valkyrja\Console
 *
 * @author  Melech Mizrachi
 */
class Console implements ConsoleContract
{
    use Dispatcher;

    /**
     * The application.
     *
     * @var \Valkyrja\Contracts\Application
     */
    protected $app;

    /**
     * The commands.
     *
     * @var \Valkyrja\Console\Command[]
     */
    protected static $commands = [];

    /**
     * Whether the console has been setup.
     *
     * @var bool
     */
    protected static $setup = false;

    /**
     * Console constructor.
     *
     * @param \Valkyrja\Contracts\Application $application The application
     *
     * @throws \ReflectionException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidClosureException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidDispatchCapabilityException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidFunctionException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidMethodException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidPropertyException
     */
    public function __construct(Application $application)
    {
        $this->app = $application;

        $this->setup();
    }

    /**
     * Add a new command.
     *
     * @param \Valkyrja\Console\Command $command The command
     *
     * @return void
     *
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidClosureException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidDispatchCapabilityException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidFunctionException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidMethodException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidPropertyException
     */
    public function addCommand(Command $command): void
    {
        $this->verifyDispatch($command);

        self::$commands[$command->getName()] = $command;
    }

    /**
     * Get a command.
     *
     * @param string $command The command name
     *
     * @return \Valkyrja\Console\Command
     */
    public function command(string $command):? Command
    {
        if ($this->hasCommand($command)) {
            return self::$commands[$command];
        }

        return null;
    }

    /**
     * Determine whether a command exists.
     *
     * @param string $command The command
     *
     * @return bool
     */
    public function hasCommand(string $command): bool
    {
        return isset(self::$commands[$command]);
    }

    /**
     * Remove a command.
     *
     * @param string $command The command
     *
     * @return void
     */
    public function removeCommand(string $command): void
    {
        if ($this->hasCommand($command)) {
            unset(self::$commands[$command]);
        }
    }

    /**
     * Dispatch a command.
     *
     * @param string $commandName The command name
     * @param array  $arguments   The arguments
     *
     * @return mixed
     *
     * @throws \Valkyrja\Console\Exceptions\CommandNotFound
     */
    public function dispatch(string $commandName, array $arguments = [])
    {
        // Verify the command exists
        if (null === $command = $this->command($commandName)) {
            throw new CommandNotFound();
        }

        // Trigger an event before dispatching
        $this->app->events()->trigger('Command.dispatching', [$command]);

        // Dispatch the command
        $dispatch = $this->dispatchCallable($command, $arguments);

        // Trigger an event after dispatching
        $this->app->events()->trigger('Command.dispatched', [$command, $dispatch]);

        return $dispatch;
    }

    /**
     * Get all commands.
     *
     * @return \Valkyrja\Console\Command[]
     */
    public function all(): array
    {
        return self::$commands;
    }

    /**
     * Set the commands.
     *
     * @param \Valkyrja\Console\Command[] $commands The commands
     *
     * @return void
     */
    public function set(Command ...$commands): void
    {
        self::$commands = $commands;
    }

    /**
     * Setup the console.
     *
     * @return void
     *
     * @throws \ReflectionException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidClosureException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidDispatchCapabilityException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidFunctionException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidMethodException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidPropertyException
     */
    public function setup(): void
    {
        // If the console was already setup no need to do it again
        if (self::$setup) {
            return;
        }

        // The console is setting up
        self::$setup = true;

        // If the application should use the events cache files
        if ($this->app->config()->console->useCacheFile) {
            // Set the application routes with said file
            self::$commands = require $this->app->config()->console->cacheFilePath;

            // Then return out of routes setup
            return;
        }

        // Setup the bootstrap
        $this->setupBootstrap();

        // If annotations are enabled and the events should use annotations
        if ($this->app->config()->console->useAnnotations && $this->app->config()->annotations->enabled) {
            // Setup annotated event listeners
            $this->setupAnnotations();

            // If only annotations should be used
            if ($this->app->config()->console->useAnnotationsExclusively) {
                // Return to avoid loading events file
                return;
            }
        }

        // Include the events file
        require $this->app->config()->console->filePath;
    }

    /**
     * Setup console bootstrapping.
     *
     * @return void
     */
    protected function setupBootstrap(): void
    {
        // Bootstrap the console
        new BootstrapConsole($this->app, $this);
    }

    /**
     * Setup annotations.
     *
     * @return void
     *
     * @throws \ReflectionException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidClosureException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidDispatchCapabilityException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidFunctionException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidMethodException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidPropertyException
     */
    protected function setupAnnotations(): void
    {
        /** @var CommandAnnotations $containerAnnotations */
        $containerAnnotations = $this->app->container()->get(CommandAnnotations::class);

        // Get all the annotated commands from the list of handlers
        $commands = $containerAnnotations->getCommands(...$this->app->config()->console->handlers);

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
        return self::$commands;
    }
}
