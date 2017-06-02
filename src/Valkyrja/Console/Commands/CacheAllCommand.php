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

use Valkyrja\Config\Commands\ConfigCache;
use Valkyrja\Console\CommandHandler;
use Valkyrja\Console\Input\Option;
use Valkyrja\Console\Support\ProvidesCommand;
use Valkyrja\Container\Commands\ContainerCache;
use Valkyrja\Events\Commands\EventsCache;
use Valkyrja\Routing\Commands\RoutesCacheCommand;

/**
 * Class CacheAllCommand.
 *
 * @author Melech Mizrachi
 */
class CacheAllCommand extends CommandHandler
{
    use ProvidesCommand;

    /**
     * The command.
     */
    public const COMMAND           = 'cache:all';
    public const PATH              = self::COMMAND . '[ {sync:-s|--sync}]';
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
        $containerCache = console()->matchCommand(ContainerCache::COMMAND);
        $consoleCache   = console()->matchCommand(ConsoleCache::COMMAND);
        $eventsCache    = console()->matchCommand(EventsCache::COMMAND);
        $routesCache    = console()->matchCommand(RoutesCacheCommand::COMMAND);

        console()->dispatchCommand($containerCache);
        console()->dispatchCommand($consoleCache);
        console()->dispatchCommand($eventsCache);
        console()->dispatchCommand($routesCache);

        $configCache = console()->matchCommand(ConfigCache::COMMAND);
        console()->dispatchCommand($configCache);

        if (null !== $sync && config()['app']['debug']) {
            $files = [
                config()['console']['cacheFilePath'],
                config()['container']['cacheFilePath'],
                config()['events']['cacheFilePath'],
                config()['routing']['cacheFilePath'],
                config()['cacheFilePath'],
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
