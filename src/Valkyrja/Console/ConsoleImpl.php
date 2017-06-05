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

use Valkyrja\Application;
use Valkyrja\Console\Annotations\CommandAnnotations;
use Valkyrja\Console\Exceptions\CommandNotFound;
use Valkyrja\Console\Input\Input;
use Valkyrja\Console\Output\Output;
use Valkyrja\Support\Providers\ProvidersAwareTrait;
use Valkyrja\Support\Providers\Provides;

/**
 * Class Console.
 *
 * @author Melech Mizrachi
 */
class ConsoleImpl implements Console
{
    use ProvidersAwareTrait {
        register as traitRegister;
    }
    use Provides;

    /**
     * The run method to call within command handlers.
     */
    public const RUN_METHOD = 'run';

    /**
     * The application.
     *
     * @var \Valkyrja\Application
     */
    protected $app;

    /**
     * The commands.
     *
     * @var \Valkyrja\Console\Command[]
     */
    protected static $commands = [];

    /**
     * The command paths.
     *
     * @var string[]
     */
    protected static $paths = [];

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
     * @param \Valkyrja\Application $application The application
     */
    public function __construct(Application $application)
    {
        $this->app = $application;
    }

    /**
     * Add a new command.
     *
     * @param \Valkyrja\Console\Command $command The command
     *
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidClosureException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidDispatchCapabilityException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidFunctionException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidMethodException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidPropertyException
     *
     * @return void
     */
    public function addCommand(Command $command): void
    {
        $command->setMethod($command->getMethod() ?? static::RUN_METHOD);

        $this->app->dispatcher()->verifyClassMethod($command);
        $this->app->dispatcher()->verifyFunction($command);
        $this->app->dispatcher()->verifyClosure($command);

        $this->addParsedCommand($command, $this->app->pathParser()->parse($command->getPath()));
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

    /**
     * Get a command by name.
     *
     * @param string $name The command name
     *
     * @return \Valkyrja\Console\Command
     */
    public function command(string $name):? Command
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
            unset(
                self::$commands[self::$namedCommands[$name]],
                self::$namedCommands[$name]
            );
        }
    }

    /**
     * Get a command from an input.
     *
     * @param \Valkyrja\Console\Input\Input $input The input
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

                // Clone the command to avoid changing the one set in the master array
                $command = clone self::$commands[$commandPath];
                // The first match is the path itself
                unset($matches[0]);

                // Set the matches
                $command->setMatches($matches);

                return $command;
            }
        }

        return $command;
    }

    /**
     * Dispatch a command.
     *
     * @param \Valkyrja\Console\Input\Input   $input  The input
     * @param \Valkyrja\Console\Output\Output $output The output
     *
     * @throws \Valkyrja\Console\Exceptions\CommandNotFound
     *
     * @return mixed
     */
    public function dispatch(Input $input, Output $output)
    {
        // Verify the command exists
        if (null === $command = $this->inputCommand($input)) {
            throw new CommandNotFound();
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
     * @param \Valkyrja\Console\Command $command The command
     *
     * @return mixed
     */
    public function dispatchCommand(Command $command)
    {
        // Trigger an event before dispatching
        $this->app->events()->trigger('Command.dispatching', [$command]);

        // Dispatch the command
        $dispatch = $this->app->dispatcher()->dispatchCallable($command, $command->getMatches());

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
     * @param \Valkyrja\Console\Command[] $commands The commands
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
     * Setup the console.
     *
     * @param bool $force    [optional] Whether to force setup
     * @param bool $useCache [optional] Whether to use cache
     *
     * @throws \ReflectionException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidClosureException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidDispatchCapabilityException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidFunctionException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidMethodException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidPropertyException
     *
     * @return void
     */
    public function setup(bool $force = false, bool $useCache = true): void
    {
        // If the console was already setup no need to do it again
        if (self::$setup && ! $force) {
            return;
        }

        // The console is setting up
        self::$setup = true;

        // If the application should use the console cache files
        if ($useCache && $this->app->config()['console']['useCache']) {
            $this->setupFromCache();

            // Then return out of setup
            return;
        }

        self::$paths         = [];
        self::$commands      = [];
        self::$namedCommands = [];

        // Setup command providers
        $this->setupCommandProviders();

        // If annotations are enabled and the events should use annotations
        if ($this->app->config()['console']['useAnnotations'] && $this->app->config()['annotations']['enabled']) {
            // Setup annotated event listeners
            $this->setupAnnotations();

            // If only annotations should be used
            if ($this->app->config()['console']['useAnnotationsExclusively']) {
                // Return to avoid loading events file
                return;
            }
        }

        // Include the events file
        require $this->app->config()['console']['filePath'];
    }

    /**
     * Setup the console from cache.
     *
     * @return void
     */
    protected function setupFromCache(): void
    {
        // Set the application console with said file
        $cache = $this->app->config()['cache']['console']
            ?? require $this->app->config()['console']['cacheFilePath'];

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
     * Setup command providers.
     *
     * @return void
     */
    protected function setupCommandProviders(): void
    {
        // Iterate through all the providers
        foreach ($this->app->config()['console']['providers'] as $provider) {
            $this->register($provider);
        }

        // If this is not a dev environment
        if (! $this->app->debug()) {
            return;
        }

        // Iterate through all the providers
        foreach ($this->app->config()['console']['devProviders'] as $provider) {
            $this->register($provider);
        }
    }

    /**
     * Register a provider.
     *
     * @param string $provider The provider
     * @param bool   $force    [optional] Whether to force regardless of deferred status
     *
     * @return void
     */
    public function register(string $provider, bool $force = false): void
    {
        // Do the default registration of the service provider
        $this->traitRegister($provider, $force);

        /* @var \Valkyrja\Console\Support\CommandProvider $provider */
        // Get the commands names provided
        $commands = $provider::commands();

        // Iterate through the provided commands
        foreach ($provider::provides() as $key => $provided) {
            // Parse the provided path
            $parsedPath = $this->app->pathParser()->parse($provided);

            // Set the path and regex in the paths list
            self::$paths[$parsedPath['regex']] = $provided;
            // Set the path and command in the named commands list
            self::$namedCommands[$commands[$key]] = $provided;
        }
    }

    /**
     * Setup annotations.
     *
     * @throws \ReflectionException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidClosureException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidDispatchCapabilityException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidFunctionException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidMethodException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidPropertyException
     *
     * @return void
     */
    protected function setupAnnotations(): void
    {
        /** @var CommandAnnotations $containerAnnotations */
        $containerAnnotations = $this->app->container()->getSingleton(CommandAnnotations::class);

        // Get all the annotated commands from the list of handlers
        $commands = $containerAnnotations->getCommands(...$this->app->config()['console']['handlers']);

        // Iterate through the commands
        foreach ($commands as $command) {
            // Set the service
            $this->addCommand($command);
        }
    }

    /**
     * Get a cacheable representation of the commands.
     *
     * @throws \ReflectionException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidClosureException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidDispatchCapabilityException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidFunctionException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidMethodException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidPropertyException
     *
     * @return array
     */
    public function getCacheable(): array
    {
        $this->setup(true, false);

        return [
            'commands'      => base64_encode(serialize(self::$commands)),
            'paths'         => self::$paths,
            'namedCommands' => self::$namedCommands,
            'provided'      => self::$provided,
        ];
    }

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            Console::class,
        ];
    }

    /**
     * Publish the provider.
     *
     * @param \Valkyrja\Application $app The application
     *
     * @return void
     */
    public static function publish(Application $app): void
    {
        $app->container()->singleton(
            Console::class,
            new static($app)
        );

        $app->console()->setup();
    }

    /**
     * Get the application.
     *
     * @return \Valkyrja\Application
     */
    protected function getApplication(): Application
    {
        return $this->app;
    }
}
