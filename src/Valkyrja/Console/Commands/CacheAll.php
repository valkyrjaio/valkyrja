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

use Valkyrja\Config\Commands\ConfigCache;
use Valkyrja\Config\Enums\ConfigKey;
use Valkyrja\Console\Commanders\Commander;
use Valkyrja\Console\Enums\ExitCode;
use Valkyrja\Console\Exceptions\CommandNotFound;
use Valkyrja\Console\Inputs\Option;
use Valkyrja\Console\Support\ProvidesCommand;
use Valkyrja\Container\Commands\ContainerCache;
use Valkyrja\Event\Commands\EventsCache;
use Valkyrja\Routing\Commands\RoutesCache;

use function copy;
use function str_replace;
use function Valkyrja\config;
use function Valkyrja\console;
use function Valkyrja\output;
use function Valkyrja\app;

/**
 * Class CacheAllCommand.
 *
 * @author Melech Mizrachi
 */
class CacheAll extends Commander
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
        $containerCache = console()->matchCommand(ContainerCache::COMMAND);
        $consoleCache   = console()->matchCommand(ConsoleCache::COMMAND);
        $eventsCache    = console()->matchCommand(EventsCache::COMMAND);
        $routesCache    = console()->matchCommand(RoutesCache::COMMAND);

        console()->dispatchCommand($containerCache);
        console()->dispatchCommand($consoleCache);
        console()->dispatchCommand($eventsCache);
        console()->dispatchCommand($routesCache);

        $configCache = console()->matchCommand(ConfigCache::COMMAND);
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
