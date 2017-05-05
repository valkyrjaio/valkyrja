<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Console\Commands;

use Valkyrja\Console\CommandHandler;
use Valkyrja\Console\Input\Option;
use Valkyrja\Container\Commands\ContainerCache;
use Valkyrja\Events\Commands\EventsCache;
use Valkyrja\Routing\Commands\RoutingCache;

/**
 * Class CacheAllCommand
 *
 * @package Valkyrja\Console\Commands
 *
 * @author  Melech Mizrachi
 */
class CacheAllCommand extends CommandHandler
{
    /**
     * The command.
     */
    public const COMMAND           = 'cache:all';
    public const SHORT_DESCRIPTION = 'Generate all caches and sync';

    /**
     * Generate all cache files.
     *
     * @param string $sync [optional] Whether to sync the cached files
     *
     * @return int
     */
    public function run(string $sync = null): int
    {
        $consoleCache = console()->matchCommand(ConsoleCache::COMMAND);
        $containerCache = console()->matchCommand(ContainerCache::COMMAND);
        $eventsCache = console()->matchCommand(EventsCache::COMMAND);
        $routesCache = console()->matchCommand(RoutingCache::COMMAND);

        console()->dispatchCommand($consoleCache);
        console()->dispatchCommand($containerCache);
        console()->dispatchCommand($eventsCache);
        console()->dispatchCommand($routesCache);

        if (null !== $sync && config()->app->debug) {
            $files = [
                config()->console->cacheFilePath,
                config()->container->cacheFilePath,
                config()->events->cacheFilePath,
                config()->routing->cacheFilePath,
            ];

            foreach ($files as $file) {
                copy($file, str_replace('site', 'sync', $file));
                output()->writeMessage('Copied: ' . $file, true);
            }
        }

        return 1;
    }

    /**
     * Get the valid options.
     *
     * @return array
     */
    protected function getOptions(): array
    {
        return [
            new Option('sync', 'Sync all files', 's'),
        ];
    }
}
