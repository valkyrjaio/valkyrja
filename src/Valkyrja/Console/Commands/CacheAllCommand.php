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
        $originalDebug = config()['app']['debug'];
        $originalEnv   = config()['app']['env'];

        $configCache = console()->matchCommand(ConfigCache::COMMAND);
        console()->dispatchCommand($configCache);

        config()['app']['debug'] = false;
        config()['app']['env']   = 'production';

        $containerCache = console()->matchCommand(ContainerCache::COMMAND);
        $consoleCache   = console()->matchCommand(ConsoleCache::COMMAND);
        $eventsCache    = console()->matchCommand(EventsCache::COMMAND);
        $routesCache    = console()->matchCommand(RoutesCacheCommand::COMMAND);

        console()->dispatchCommand($containerCache);
        console()->dispatchCommand($consoleCache);
        console()->dispatchCommand($eventsCache);
        console()->dispatchCommand($routesCache);

        config()['app']['debug'] = $originalDebug;
        config()['app']['env']   = $originalEnv;

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
