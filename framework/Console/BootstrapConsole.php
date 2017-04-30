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
use Valkyrja\Container\Console\ContainerCache;
use Valkyrja\Container\Enums\CoreComponent;
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
    protected $app;

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
        $this->bootstrapConsoleCache();
        $this->bootstrapContainerCache();
        $this->bootstrapEventsCache();
        $this->bootstrapRoutingCache();
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
                ->setPath('console:cache[ -{h}][ --{help}]')
                ->setName('console:cache')
                ->setClass(ConsoleCache::class)
                ->setDependencies([CoreComponent::INPUT, CoreComponent::OUTPUT])
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
                ->setPath('container:cache[ -{h}][ --{help}]')
                ->setName('container:cache')
                ->setClass(ContainerCache::class)
                ->setDependencies([CoreComponent::INPUT, CoreComponent::OUTPUT])
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
                ->setPath('events:cache[ -{h}][ --{help}]')
                ->setName('events:cache')
                ->setClass(EventsCache::class)
                ->setDependencies([CoreComponent::INPUT, CoreComponent::OUTPUT])
        );
    }

    /**
     * Bootstrap the routing cache command.
     *
     * @return void
     */
    protected function bootstrapRoutingCache(): void
    {
        $this->console->addCommand(
            (new Command())
                ->setPath('routing:cache[ -{h}][ --{help}]')
                ->setName('routing:cache')
                ->setClass(RoutingCache::class)
                ->setDependencies([CoreComponent::INPUT, CoreComponent::OUTPUT])
        );
    }
}
