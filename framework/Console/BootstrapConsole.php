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

use Valkyrja\Console\Handlers\ConsoleCache;
use Valkyrja\Console\Handlers\ConsoleCommands;
use Valkyrja\Console\Handlers\ConsoleCommandsForBash;
use Valkyrja\Container\Console\ContainerCache;
use Valkyrja\Container\Enums\CoreComponent;
use Valkyrja\Container\Service;
use Valkyrja\Contracts\Application;
use Valkyrja\Contracts\Console\Console;
use Valkyrja\Events\Console\EventsCache;
use Valkyrja\Routing\Console\RoutingCache;

/**
 * Class BootstrapConsole
 *
 * @package Valkyrja\Console
 *
 * @author  Melech Mizrachi
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
        $this->app = $application;
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
        $this->bootstrapConsoleCache();
        $this->bootstrapContainerCache();
        $this->bootstrapEventsCache();
        $this->bootstrapRoutingCache();
    }

    /**
     * Bootstrap the console commands command.
     *
     * @return void
     */
    protected function bootstrapConsoleCommands(): void
    {
        $this->app->container()->bind(
            (new Service())
                ->setId(ConsoleCommands::class)
                ->setClass(ConsoleCommands::class)
                ->setDependencies([CoreComponent::INPUT, CoreComponent::OUTPUT])
        );

        $this->console->addCommand(
            (new Command())
                ->setPath('console:commands[ -{h}][ --{help}]')
                ->setName('console:commands')
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
        $this->app->container()->bind(
            (new Service())
                ->setId(ConsoleCommandsForBash::class)
                ->setClass(ConsoleCommandsForBash::class)
                ->setDependencies([CoreComponent::INPUT, CoreComponent::OUTPUT])
        );

        $this->console->addCommand(
            (new Command())
                ->setPath('console:commandsForBash valkyrja[ {commandTyped:alpha}]')
                ->setName('console:commandsForBash')
                ->setDescription('List all the commands for bash auto complete')
                ->setClass(ConsoleCommandsForBash::class)
        );
    }

    /**
     * Bootstrap the console cache command.
     *
     * @return void
     */
    protected function bootstrapConsoleCache(): void
    {
        $this->app->container()->bind(
            (new Service())
                ->setId(ConsoleCache::class)
                ->setClass(ConsoleCache::class)
                ->setDependencies([CoreComponent::INPUT, CoreComponent::OUTPUT])
        );

        $this->console->addCommand(
            (new Command())
                ->setPath('console:cache[ -{h}][ --{help}]')
                ->setName('console:cache')
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
        $this->app->container()->bind(
            (new Service())
                ->setId(ContainerCache::class)
                ->setClass(ContainerCache::class)
                ->setDependencies([CoreComponent::INPUT, CoreComponent::OUTPUT])
        );

        $this->console->addCommand(
            (new Command())
                ->setPath('container:cache[ -{h}][ --{help}]')
                ->setName('container:cache')
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
        $this->app->container()->bind(
            (new Service())
                ->setId(EventsCache::class)
                ->setClass(EventsCache::class)
                ->setDependencies([CoreComponent::INPUT, CoreComponent::OUTPUT])
        );

        $this->console->addCommand(
            (new Command())
                ->setPath('events:cache[ -{h}][ --{help}]')
                ->setName('events:cache')
                ->setDescription('Generate the events cache')
                ->setClass(EventsCache::class)
        );
    }

    /**
     * Bootstrap the routing cache command.
     *
     * @return void
     */
    protected function bootstrapRoutingCache(): void
    {
        $this->app->container()->bind(
            (new Service())
                ->setId(RoutingCache::class)
                ->setClass(RoutingCache::class)
                ->setDependencies([CoreComponent::INPUT, CoreComponent::OUTPUT])
        );

        $this->console->addCommand(
            (new Command())
                ->setPath('routes:cache[ -{h}][ --{help}]')
                ->setName('routes:cache')
                ->setDescription('Generate the routes cache')
                ->setClass(RoutingCache::class)
        );
    }
}
