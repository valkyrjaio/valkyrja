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

namespace Valkyrja\Console\Dispatchers;

use Valkyrja\Application\Application;
use Valkyrja\Config\Enums\ConfigKeyPart;
use Valkyrja\Console\Cacheables\CacheableConsole;
use Valkyrja\Console\Command;
use Valkyrja\Console\Console as ConsoleContract;
use Valkyrja\Console\Exceptions\CommandNotFound;
use Valkyrja\Console\Input;
use Valkyrja\Console\Output;
use Valkyrja\Console\Support\CommandProvider;
use Valkyrja\Container\Enums\Contract;
use Valkyrja\Dispatcher\Exceptions\InvalidClosureException;
use Valkyrja\Dispatcher\Exceptions\InvalidDispatchCapabilityException;
use Valkyrja\Dispatcher\Exceptions\InvalidFunctionException;
use Valkyrja\Dispatcher\Exceptions\InvalidMethodException;
use Valkyrja\Dispatcher\Exceptions\InvalidPropertyException;
use Valkyrja\Support\Providers\Provides;

/**
 * Class Console.
 *
 * @author Melech Mizrachi
 */
class Console implements ConsoleContract
{
    use CacheableConsole {
        register as traitRegister;
    }
    use Provides;

    /**
     * The run method to call within command handlers.
     */
    public const RUN_METHOD = 'run';

    /**
     * Console constructor.
     *
     * @param Application $application The application
     */
    public function __construct(Application $application)
    {
        $this->app = $application;
    }

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            Contract::CONSOLE,
        ];
    }

    /**
     * Publish the provider.
     *
     * @param Application $app The application
     *
     * @return void
     */
    public static function publish(Application $app): void
    {
        $app->container()->singleton(
            Contract::CONSOLE,
            new static($app)
        );

        $app->console()->setup();
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
    public function addCommand(Command $command): void
    {
        $command->setMethod($command->getMethod() ?? static::RUN_METHOD);
        $dispatcher = $this->app->dispatcher();

        $dispatcher->verifyDispatch($command);

        $this->addParsedCommand($command, $this->app->pathParser()->parse((string) $command->getPath()));
    }

    /**
     * Get a command by name.
     *
     * @param string $name The command name
     *
     * @return Command
     */
    public function command(string $name): ?Command
    {
        return $this->hasCommand($name)
            ? self::$commands[self::$namedCommands[$name]]
            : null;
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
            unset(self::$commands[self::$namedCommands[$name]], self::$namedCommands[$name]);
        }
    }

    /**
     * Get a command from an input.
     *
     * @param Input $input The input
     *
     * @throws CommandNotFound
     *
     * @return Command
     */
    public function inputCommand(Input $input): Command
    {
        return $this->matchCommand($input->getStringArguments());
    }

    /**
     * Match a command by path.
     *
     * @param string $path The path
     *
     * @throws CommandNotFound
     *
     * @return Command
     */
    public function matchCommand(string $path): Command
    {
        // If the path matches a set command path
        if (isset(self::$commands[$path])) {
            return self::$commands[$path];
        }

        $command = null;

        // Otherwise iterate through the commands and attempt to match via regex
        foreach (self::$paths as $regex => $commandPath) {
            // If the preg match is successful, we've found our command!
            if (preg_match($regex, $path, $matches)) {
                // Check if this command is provided
                if ($this->isProvided($commandPath)) {
                    // Initialize the provided command
                    $this->initializeProvided($commandPath);
                }

                // Clone the command to avoid changing the one set in the master
                // array
                $command = clone self::$commands[$commandPath];
                // The first match is the path itself
                unset($matches[0]);

                // Set the matches
                $command->setMatches($matches);

                break;
            }
        }

        // If a command was not found
        if (null === $command) {
            // Throw a not found exception
            throw new CommandNotFound('The command ' . $path . ' not found.');
        }

        return $command;
    }

    /**
     * Dispatch a command.
     *
     * @param Input  $input  The input
     * @param Output $output The output
     *
     * @throws CommandNotFound
     *
     * @return mixed
     */
    public function dispatch(Input $input, Output $output)
    {
        // Verify the command exists
        if (null === $command = $this->inputCommand($input)) {
            throw new CommandNotFound('Specified command does not exists.');
        }

        if ($input->hasOption('-h') || $input->hasOption('--help')) {
            $command->setMethod('help');
        }

        if ($input->hasOption('-V') || $input->hasOption('--version')) {
            $command->setMethod('version');
        }

        return $this->dispatchCommand($command);
    }

    /**
     * Dispatch a command.
     *
     * @param Command $command The command
     *
     * @return mixed
     */
    public function dispatchCommand(Command $command)
    {
        // Trigger an event before dispatching
        $this->app->events()->trigger('Command.dispatching', [$command]);

        // Dispatch the command
        $dispatch = $this->app->dispatcher()->dispatch($command, $command->getMatches());

        // Trigger an event after dispatching
        $this->app->events()->trigger('Command.dispatched', [$command, $dispatch]);

        return $dispatch;
    }

    /**
     * Get all commands.
     *
     * @return Command[]
     */
    public function all(): array
    {
        // Iterate through all the command providers to set any deferred commands
        foreach (self::$provided as $provided => $provider) {
            // Initialize the provided command
            $this->initializeProvided($provided);
        }

        return self::$commands;
    }

    /**
     * Set the commands.
     *
     * @param Command ...$commands The commands
     *
     * @return void
     */
    public function set(Command ...$commands): void
    {
        self::$commands = $commands;
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
     * Register a provider.
     *
     * @param string $provider The provider
     * @param bool   $force    [optional] Whether to force regardless of
     *                         deferred status
     *
     * @return void
     */
    public function register(string $provider, bool $force = false): void
    {
        // Do the default registration of the service provider
        $this->traitRegister($provider, $force);

        /* @var CommandProvider $provider */
        // Get the commands names provided
        $commands = $provider::commands();

        // Iterate through the provided commands
        foreach ($provider::provides() as $key => $provided) {
            // Parse the provided path
            $parsedPath = $this->app->pathParser()->parse($provided);

            // Set the path and regex in the paths list
            self::$paths[$parsedPath[ConfigKeyPart::REGEX]] = $provided;
            // Set the path and command in the named commands list
            self::$namedCommands[$commands[$key]] = $provided;
        }
    }

    /**
     * Add a parsed command.
     *
     * @param Command $command       The command
     * @param array   $parsedCommand The parsed command
     *
     * @return void
     */
    protected function addParsedCommand(Command $command, array $parsedCommand): void
    {
        // Set the properties
        $command->setRegex($parsedCommand['regex']);
        $command->setParams($parsedCommand['params']);
        $command->setSegments($parsedCommand['segments']);

        // Set the command in the commands list
        self::$commands[$command->getPath()] = $command;
        // Set the command in the commands paths list
        self::$paths[$command->getRegex()] = $command->getPath();

        // If the command has a name
        if (null !== $command->getName()) {
            // Set in the named commands list to find it more easily later
            self::$namedCommands[$command->getName()] = $command->getPath();
        }
    }
}
