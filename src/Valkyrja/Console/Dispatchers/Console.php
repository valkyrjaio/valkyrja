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

namespace Valkyrja\Console\Dispatchers;

use Valkyrja\Config\Constants\ConfigKeyPart;
use Valkyrja\Console\Command;
use Valkyrja\Console\Console as Contract;
use Valkyrja\Console\Exceptions\CommandNotFound;
use Valkyrja\Console\Input;
use Valkyrja\Console\Output;
use Valkyrja\Console\Support\Provider;
use Valkyrja\Container\Container;
use Valkyrja\Dispatcher\Dispatcher;
use Valkyrja\Dispatcher\Exceptions\InvalidClosureException;
use Valkyrja\Dispatcher\Exceptions\InvalidDispatchCapabilityException;
use Valkyrja\Dispatcher\Exceptions\InvalidFunctionException;
use Valkyrja\Dispatcher\Exceptions\InvalidMethodException;
use Valkyrja\Dispatcher\Exceptions\InvalidPropertyException;
use Valkyrja\Event\Events;
use Valkyrja\Path\PathParser;
use Valkyrja\Support\Provider\Traits\ProvidersAwareTrait;

use function preg_match;

/**
 * Class Console.
 *
 * @author Melech Mizrachi
 */
class Console implements Contract
{
    use ProvidersAwareTrait {
        register as traitRegister;
    }

    /**
     * The run method to call within command handlers.
     */
    public const RUN_METHOD = 'run';

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
     * The container.
     *
     * @var Container
     */
    protected Container $container;

    /**
     * The dispatcher.
     *
     * @var Dispatcher
     */
    protected Dispatcher $dispatcher;

    /**
     * The events.
     *
     * @var Events
     */
    protected Events $events;

    /**
     * The path parser.
     *
     * @var PathParser
     */
    protected PathParser $pathParser;

    /**
     * The config.
     *
     * @var array
     */
    protected array $config;

    /**
     * Whether to run in debug or not.
     *
     * @var bool
     */
    protected bool $debug = false;

    /**
     * Console constructor.
     *
     * @param Container  $container
     * @param Dispatcher $dispatcher
     * @param Events     $events
     * @param PathParser $pathParser
     * @param array      $config
     * @param bool       $debug
     */
    public function __construct(
        Container $container,
        Dispatcher $dispatcher,
        Events $events,
        PathParser $pathParser,
        array $config,
        bool $debug = false
    ) {
        $this->container  = $container;
        $this->dispatcher = $dispatcher;
        $this->events     = $events;
        $this->pathParser = $pathParser;
        $this->config     = $config;
        $this->debug      = $debug;
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

        $this->dispatcher->verifyDispatch($command);

        $this->addParsedCommand($command, $this->pathParser->parse((string) $command->getPath()));
    }

    /**
     * @inheritDoc
     */
    public function getCommand(string $name): ?Command
    {
        return $this->hasCommand($name)
            ? self::$commands[self::$namedCommands[$name]]
            : null;
    }

    /**
     * @inheritDoc
     */
    public function hasCommand(string $name): bool
    {
        return isset(self::$namedCommands[$name]);
    }

    /**
     * @inheritDoc
     */
    public function removeCommand(string $name): void
    {
        if ($this->hasCommand($name)) {
            unset(self::$commands[self::$namedCommands[$name]], self::$namedCommands[$name]);
        }
    }

    /**
     * @inheritDoc
     */
    public function inputCommand(Input $input): Command
    {
        return $this->matchCommand($input->getStringArguments());
    }

    /**
     * @inheritDoc
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
                    $this->publishProvided($commandPath);
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
     * @inheritDoc
     */
    public function dispatch(Input $input, Output $output): int
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
     * @inheritDoc
     */
    public function dispatchCommand(Command $command): int
    {
        // Trigger an event before dispatching
        $this->events->trigger('Command.dispatching', [$command]);

        // Dispatch the command
        $dispatch = $this->dispatcher->dispatch($command, $command->getMatches());

        // Trigger an event after dispatching
        $this->events->trigger('Command.dispatched', [$command, $dispatch]);

        return $dispatch;
    }

    /**
     * @inheritDoc
     */
    public function all(): array
    {
        // Iterate through all the command providers to set any deferred commands
        foreach (self::$provided as $provided => $provider) {
            // Initialize the provided command
            $this->publishProvided($provided);
        }

        return self::$commands;
    }

    /**
     * @inheritDoc
     */
    public function set(Command ...$commands): void
    {
        self::$commands = $commands;
    }

    /**
     * @inheritDoc
     */
    public function getNamedCommands(): array
    {
        return self::$namedCommands;
    }

    /**
     * @inheritDoc
     */
    public function register(string $provider, bool $force = false): void
    {
        // Do the default registration of the service provider
        $this->traitRegister($provider, $force);

        /* @var Provider $provider */
        // Get the commands names provided
        $commands = $provider::commands();

        // Iterate through the provided commands
        foreach ($provider::provides() as $key => $provided) {
            // Parse the provided path
            $parsedPath = $this->pathParser->parse($provided);

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
