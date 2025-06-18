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

namespace Valkyrja\Console;

use InvalidArgumentException;
use Valkyrja\Console\Contract\Console as Contract;
use Valkyrja\Console\Event\CommandDispatched;
use Valkyrja\Console\Event\CommandDispatching;
use Valkyrja\Console\Exception\CommandNotFound;
use Valkyrja\Console\Input\Contract\Input;
use Valkyrja\Console\Model\Contract\Command;
use Valkyrja\Console\Output\Contract\Output;
use Valkyrja\Console\Support\Provider;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Dispatcher\Contract\Dispatcher;
use Valkyrja\Dispatcher\Exception\InvalidClosureException;
use Valkyrja\Dispatcher\Exception\InvalidDispatchCapabilityException;
use Valkyrja\Dispatcher\Exception\InvalidFunctionException;
use Valkyrja\Dispatcher\Exception\InvalidMethodException;
use Valkyrja\Dispatcher\Exception\InvalidPropertyException;
use Valkyrja\Dispatcher\Validator\Contract\Validator;
use Valkyrja\Event\Contract\Dispatcher as Events;
use Valkyrja\Path\Parser\Contract\Parser;
use Valkyrja\Support\Provider\ProvidersAwareTrait;

use function preg_match;

/**
 * Class Console.
 *
 * @author Melech Mizrachi
 *
 * @psalm-import-type ParsedPath from Parser
 *
 * @phpstan-import-type ParsedPath from Parser
 */
class Console implements Contract
{
    use ProvidersAwareTrait {
        ProvidersAwareTrait::register as traitRegister;
    }

    /**
     * The run method to call within command handlers.
     *
     * @var non-empty-string
     */
    public const string RUN_METHOD = 'run';

    /**
     * The commands.
     *
     * @var array<string, Command>
     */
    protected array $commands = [];

    /**
     * The command paths.
     *
     * @var array<non-empty-string, string>
     */
    protected array $paths = [];

    /**
     * The commands by name.
     *
     * @var array<string, string>
     */
    protected array $namedCommands = [];

    /**
     * Console constructor.
     */
    public function __construct(
        protected Container $container,
        protected Dispatcher $dispatcher,
        protected Validator $validator,
        protected Events $events,
        protected Parser $pathParser,
        protected Config $config,
        protected bool $debug = false
    ) {
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

        $this->validator->dispatch($command);

        $this->addParsedCommand($command, $this->pathParser->parse((string) $command->getPath()));
    }

    /**
     * @inheritDoc
     */
    public function getCommand(string $name): Command|null
    {
        return $this->hasCommand($name)
            ? $this->commands[$this->namedCommands[$name]]
            : null;
    }

    /**
     * @inheritDoc
     */
    public function hasCommand(string $name): bool
    {
        return isset($this->namedCommands[$name]);
    }

    /**
     * @inheritDoc
     */
    public function removeCommand(string $name): void
    {
        if ($this->hasCommand($name)) {
            unset($this->commands[$this->namedCommands[$name]], $this->namedCommands[$name]);
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
        if (isset($this->commands[$path])) {
            return $this->commands[$path];
        }

        $command = null;

        // Otherwise iterate through the commands and attempt to match via regex
        foreach ($this->paths as $regex => $commandPath) {
            // If the preg match is successful, we've found our command!
            if (preg_match($regex, $path, $matches)) {
                // Check if this command is provided
                if ($this->isDeferred($commandPath)) {
                    // Initialize the provided command
                    $this->publishProvided($commandPath);
                }

                // Clone the command to avoid changing the one set in the master
                // array
                $command = clone $this->commands[$commandPath];
                // The first match is the path itself
                unset($matches[0]);

                // Set the matches
                $command->setMatches($matches);

                break;
            }
        }

        // If a command was not found
        if ($command === null) {
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
        $command = $this->inputCommand($input);

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
        $this->events->dispatchByIdIfHasListeners(CommandDispatching::class, [$command]);

        // Dispatch the command
        /** @var int $exitCode */
        $exitCode = $this->dispatcher->dispatch($command, $command->getMatches());

        // Trigger an event after dispatching
        $this->events->dispatchByIdIfHasListeners(CommandDispatched::class, [$command, $exitCode]);

        return $exitCode;
    }

    /**
     * @inheritDoc
     */
    public function all(): array
    {
        // Iterate through all the command providers to set any deferred commands
        foreach ($this->deferred as $provided => $provider) {
            // Initialize the provided command
            $this->publishProvided($provided);
        }

        return $this->commands;
    }

    /**
     * @inheritDoc
     */
    public function set(Command ...$commands): void
    {
        $this->commands = [];

        foreach ($commands as $command) {
            $path = $command->getPath();

            if ($path === null || $path === '') {
                throw new InvalidArgumentException('Path must be valid');
            }

            $this->commands[$path] = $command;
        }
    }

    /**
     * @inheritDoc
     */
    public function getNamedCommands(): array
    {
        return $this->namedCommands;
    }

    /**
     * @inheritDoc
     */
    public function register(string $provider, bool $force = false): void
    {
        // Do the default registration of the service provider
        $this->traitRegister($provider, $force);

        /** @var class-string<Provider> $provider */
        // Get the commands names provided
        $commands = $provider::commands();

        // Iterate through the provided commands
        foreach ($provider::provides() as $key => $provided) {
            // Parse the provided path
            $parsedPath = $this->pathParser->parse($provided);

            // Set the path and regex in the paths list
            $this->paths[$parsedPath['regex']] = $provided;
            // Set the path and command in the named commands list
            $this->namedCommands[$commands[$key]] = $provided;
        }
    }

    /**
     * Add a parsed command.
     *
     * @param Command    $command       The command
     * @param ParsedPath $parsedCommand The parsed command
     *
     * @return void
     */
    protected function addParsedCommand(Command $command, array $parsedCommand): void
    {
        // Set the properties
        $command->setRegex($parsedCommand['regex']);
        $command->setParams($parsedCommand['params']);
        $command->setSegments($parsedCommand['segments']);

        $path  = $command->getPath();
        $regex = $command->getRegex();

        if ($path === null || $path === '' || $regex === null || $regex === '') {
            throw new InvalidArgumentException('Invalid command provided.');
        }

        // Set the command in the commands list
        $this->commands[$path] = $command;
        // Set the command in the commands paths list
        $this->paths[$regex] = $path;

        $name = $command->getName();

        // If the command has a name
        if ($name !== null) {
            // Set in the named commands list to find it more easily later
            $this->namedCommands[$name] = $path;
        }
    }
}
