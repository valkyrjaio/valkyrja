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
use Valkyrja\Container\Enums\CoreComponent;
use Valkyrja\Contracts\Application;
use Valkyrja\Contracts\Console\Annotations\CommandAnnotations;
use Valkyrja\Contracts\Console\Console as ConsoleContract;
use Valkyrja\Contracts\Console\Input;
use Valkyrja\Contracts\Console\Output;
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
     * The run method to call within command handlers.
     */
    public const RUN_METHOD = 'run';

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
     * The commands by name.
     *
     * @var string[]
     */
    protected static $namedCommands = [];

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
        $command->setMethod($command->getMethod() ?? static::RUN_METHOD);

        $this->verifyClassMethod($command);
        $this->verifyFunction($command);
        $this->verifyClosure($command);

        /** @var \Valkyrja\Contracts\Parsers\PathParser $parser */
        $parser = $this->app->container()->get(CoreComponent::PATH_PARSER);

        $this->addParsedCommand($command, $parser->parse($command->getPath()));
    }

    /**
     * Add a parsed command.
     *
     * @param \Valkyrja\Console\Command $command       The command
     * @param array                     $parsedCommand The parsed command
     *
     * @return void
     */
    protected function addParsedCommand(Command $command, array $parsedCommand): void
    {
        // Set the properties
        $command->setRegex($parsedCommand['regex']);
        $command->setParams($parsedCommand['params']);

        // Set the command in the commands list
        self::$commands[$command->getPath()] = $command;

        // If the command has a name
        if (null !== $command->getName()) {
            // Set in the named commands list to find it more easily later
            self::$namedCommands[$command->getName()] = $command->getPath();
        }
    }

    /**
     * Get a command by name.
     *
     * @param string $name The command name
     *
     * @return \Valkyrja\Console\Command
     */
    public function command(string $name):? Command
    {
        if ($this->hasCommand($name)) {
            return self::$commands[self::$namedCommands[$name]];
        }

        return null;
    }

    /**
     * Determine whether a command exists by name.
     *
     * @param string $name The command name
     *
     * @return bool
     */
    public function hasCommand(string $name): bool
    {
        return isset(self::$namedCommands[$name]);
    }

    /**
     * Remove a command.
     *
     * @param string $name The command name
     *
     * @return void
     */
    public function removeCommand(string $name): void
    {
        if ($this->hasCommand($name)) {
            unset(
                self::$commands[self::$namedCommands[$name]],
                self::$namedCommands[$name]
            );
        }
    }

    /**
     * Get a command from an input.
     *
     * @param \Valkyrja\Contracts\Console\Input $input The input
     *
     * @return null|\Valkyrja\Console\Command
     */
    public function inputCommand(Input $input):? Command
    {
        return $this->matchCommand($input->getStringArguments());
    }

    /**
     * Match a command by path.
     *
     * @param string $path The path
     *
     * @return \Valkyrja\Console\Command
     */
    public function matchCommand(string $path):? Command
    {
        // If the path matches a set command path
        if (isset(self::$commands[$path])) {
            return self::$commands[$path];
        }

        // Otherwise iterate through the commands and attempt to match via regex
        foreach (self::$commands as $command) {
            // If the preg match is successful, we've found our command!
            if (preg_match($command->getRegex(), $path, $matches)) {
                // The first match is the path itself
                unset($matches[0]);

                // Set the matches
                $command->setMatches($matches);

                return $command;
            }
        }

        return null;
    }

    /**
     * Dispatch a command.
     *
     * @param \Valkyrja\Contracts\Console\Input  $input  The input
     * @param \Valkyrja\Contracts\Console\Output $output The output
     *
     * @return mixed
     *
     * @throws \Valkyrja\Console\Exceptions\CommandNotFound
     */
    public function dispatch(Input $input, Output $output)
    {
        // Verify the command exists
        if (null === $command = $this->inputCommand($input)) {
            throw new CommandNotFound();
        }

        return $this->dispatchCommand($command);
    }

    /**
     * Dispatch a command.
     *
     * @param \Valkyrja\Console\Command $command The command
     *
     * @return mixed
     */
    public function dispatchCommand(Command $command)
    {
        // Trigger an event before dispatching
        $this->app->events()->trigger('Command.dispatching', [$command]);

        // Dispatch the command
        $dispatch = $this->dispatchCallable($command, $command->getMatches());

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
            $cache = require $this->app->config()->console->cacheFilePath;

            self::$commands = $cache['commands'];
            self::$namedCommands = $cache['namedCommands'];

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
     * Get the named commands list.
     *
     * @return array
     */
    public function getNamedCommands(): array
    {
        return self::$namedCommands;
    }

    /**
     * Get a cacheable representation of the commands.
     *
     * @return array
     *
     * @throws \ReflectionException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidClosureException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidDispatchCapabilityException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidFunctionException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidMethodException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidPropertyException
     */
    public function getCacheable(): array
    {
        self::$commands = [];
        self::$namedCommands = [];

        // The original use cache file value (may not be using cache to begin with)
        $originalUseCacheFile = $this->app->config()->console->useCacheFile;
        // Avoid using the cache file we already have
        $this->app->config()->console->useCacheFile = false;
        self::$setup = false;
        $this->setup();

        // Reset the use cache file value
        $this->app->config()->console->useCacheFile = $originalUseCacheFile;

        return [
            'commands'      => self::$commands,
            'namedCommands' => self::$namedCommands,
        ];
    }
}
