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

use Valkyrja\Console\Commands\CacheAllCommand;
use Valkyrja\Console\Commands\ConsoleCache;
use Valkyrja\Console\Commands\ConsoleCommands;
use Valkyrja\Console\Commands\ConsoleCommandsForBash;
use Valkyrja\Container\Commands\ContainerCache;
use Valkyrja\Contracts\Application;
use Valkyrja\Contracts\Console\Console;
use Valkyrja\Events\Commands\EventsCache;
use Valkyrja\Routing\Commands\RoutesCacheCommand;
use Valkyrja\Routing\Commands\RoutesListCommand;

/**
 * Class BootstrapConsole.
 *
 * @author Melech Mizrachi
 */
class BootstrapConsole
{
    /**
     * The application.
     *
     * @var \Valkyrja\Contracts\Application
     */
    protected $app;

    /**
     * The console.
     *
     * @var \Valkyrja\Contracts\Console\Console
     */
    protected $console;

    /**
     * BootstrapConsole constructor.
     *
     * @param \Valkyrja\Contracts\Application     $application The application
     * @param \Valkyrja\Contracts\Console\Console $console     The console
     */
    public function __construct(Application $application, Console $console)
    {
        $this->app     = $application;
        $this->console = $console;

        $this->bootstrap();
    }

    /**
     * Bootstrap the console commands.
     *
     * @return void
     */
    protected function bootstrap(): void
    {
        $this->bootstrapConsoleCommands();
        $this->bootstrapConsoleCommandsForBash();
        $this->bootstrapCacheAll();
        $this->bootstrapConsoleCache();
        $this->bootstrapContainerCache();
        $this->bootstrapEventsCache();
        $this->bootstrapRoutingCache();
        $this->bootstrapRoutingRoutes();
    }

    /**
     * Bootstrap the console commands command.
     *
     * @return void
     */
    protected function bootstrapConsoleCommands(): void
    {
        $this->console->addCommand(
            (new Command())
                ->setPath('[' . ConsoleCommands::COMMAND . '][ {namespace:[a-zA-Z0-9]+}]')
                ->setName(ConsoleCommands::COMMAND)
                ->setDescription('List all the commands')
                ->setClass(ConsoleCommands::class)
        );
    }

    /**
     * Bootstrap the console commands for bash command.
     *
     * @return void
     */
    protected function bootstrapConsoleCommandsForBash(): void
    {
        $this->console->addCommand(
            (new Command())
                ->setPath(ConsoleCommandsForBash::COMMAND . ' valkyrja[ {commandTyped:[a-zA-Z0-9\:]+}]')
                ->setName(ConsoleCommandsForBash::COMMAND)
                ->setDescription('List all the commands for bash auto complete')
                ->setClass(ConsoleCommandsForBash::class)
        );
    }

    /**
     * Bootstrap the cache:all command.
     *
     * @return void
     */
    protected function bootstrapCacheAll(): void
    {
        $this->console->addCommand(
            (new Command())
                ->setPath(CacheAllCommand::COMMAND . '[ {sync:-s|--sync}]')
                ->setName(CacheAllCommand::COMMAND)
                ->setDescription(CacheAllCommand::SHORT_DESCRIPTION)
                ->setClass(CacheAllCommand::class)
        );
    }

    /**
     * Bootstrap the console cache command.
     *
     * @return void
     */
    protected function bootstrapConsoleCache(): void
    {
        $this->console->addCommand(
            (new Command())
                ->setPath(ConsoleCache::COMMAND)
                ->setName(ConsoleCache::COMMAND)
                ->setDescription('Generate the console cache')
                ->setClass(ConsoleCache::class)
        );
    }

    /**
     * Bootstrap the container cache command.
     *
     * @return void
     */
    protected function bootstrapContainerCache(): void
    {
        $this->console->addCommand(
            (new Command())
                ->setPath(ContainerCache::COMMAND)
                ->setName(ContainerCache::COMMAND)
                ->setDescription('Generate the container cache')
                ->setClass(ContainerCache::class)
        );
    }

    /**
     * Bootstrap the events cache command.
     *
     * @return void
     */
    protected function bootstrapEventsCache(): void
    {
        $this->console->addCommand(
            (new Command())
                ->setPath(EventsCache::COMMAND)
                ->setName(EventsCache::COMMAND)
                ->setDescription('Generate the events cache')
                ->setClass(EventsCache::class)
        );
    }

    /**
     * Bootstrap the routes cache command.
     *
     * @return void
     */
    protected function bootstrapRoutingCache(): void
    {
        $this->console->addCommand(
            (new Command())
                ->setPath(RoutesCacheCommand::COMMAND)
                ->setName(RoutesCacheCommand::COMMAND)
                ->setDescription(RoutesCacheCommand::SHORT_DESCRIPTION)
                ->setClass(RoutesCacheCommand::class)
        );
    }

    /**
     * Bootstrap the routes list command.
     *
     * @return void
     */
    protected function bootstrapRoutingRoutes(): void
    {
        $this->console->addCommand(
            (new Command())
                ->setPath(RoutesListCommand::COMMAND)
                ->setName(RoutesListCommand::COMMAND)
                ->setDescription(RoutesListCommand::SHORT_DESCRIPTION)
                ->setClass(RoutesListCommand::class)
        );
    }
}
