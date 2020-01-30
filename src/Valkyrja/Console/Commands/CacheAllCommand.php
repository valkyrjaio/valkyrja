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

namespace Valkyrja\Console\Commands;

use Valkyrja\Config\Commands\ConfigCacheCommand;
use Valkyrja\Config\Enums\ConfigKey;
use Valkyrja\Console\CommandHandler;
use Valkyrja\Console\Enums\ExitCode;
use Valkyrja\Console\Exceptions\CommandNotFound;
use Valkyrja\Console\Input\Option;
use Valkyrja\Console\Support\ProvidesCommand;
use Valkyrja\Container\Commands\ContainerCacheCommand;
use Valkyrja\Event\Commands\EventsCacheCommand;
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
     * @throws CommandNotFound
     *
     * @return int
     */
    public function run(string $sync = null): int
    {
        $containerCache = console()->matchCommand(ContainerCacheCommand::COMMAND);
        $consoleCache   = console()->matchCommand(ConsoleCacheCommand::COMMAND);
        $eventsCache    = console()->matchCommand(EventsCacheCommand::COMMAND);
        $routesCache    = console()->matchCommand(RoutesCacheCommand::COMMAND);

        console()->dispatchCommand($containerCache);
        console()->dispatchCommand($consoleCache);
        console()->dispatchCommand($eventsCache);
        console()->dispatchCommand($routesCache);

        $configCache = console()->matchCommand(ConfigCacheCommand::COMMAND);
        console()->dispatchCommand($configCache);

        if (null !== $sync && app()->debug()) {
            $files = [
                config(ConfigKey::CONSOLE_CACHE_FILE_PATH),
                config(ConfigKey::CONTAINER_CACHE_FILE_PATH),
                config(ConfigKey::EVENTS_CACHE_FILE_PATH),
                config(ConfigKey::ROUTING_CACHE_FILE_PATH),
                config(ConfigKey::CONFIG_CACHE_FILE_PATH),
            ];

            foreach ($files as $file) {
                copy($file, str_replace('site', 'sync', $file));
                output()->writeMessage('Copied: ' . $file, true);
            }
        }

        return ExitCode::SUCCESS;
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
