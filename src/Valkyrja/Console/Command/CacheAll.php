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

namespace Valkyrja\Console\Command;

use Valkyrja\Config\Command\ConfigCache;
use Valkyrja\Config\Constant\ConfigKey;
use Valkyrja\Console\Commander\Commander;
use Valkyrja\Console\Enum\ExitCode;
use Valkyrja\Console\Input\Option;
use Valkyrja\Console\Support\Provides;
use Valkyrja\Container\Commands\ContainerCache;
use Valkyrja\Event\Commands\EventsCache;
use Valkyrja\Routing\Commands\RoutesCache;

use function copy;
use function str_replace;
use function Valkyrja\app;
use function Valkyrja\config;
use function Valkyrja\console;
use function Valkyrja\output;

/**
 * Class CacheAllCommand.
 *
 * @author Melech Mizrachi
 */
class CacheAll extends Commander
{
    use Provides;

    /**
     * The command.
     */
    public const COMMAND           = 'cache:all';
    public const PATH              = self::COMMAND . '[ {sync:-s|--sync}]';
    public const SHORT_DESCRIPTION = 'Generate all caches and sync';

    /**
     * @inheritDoc
     *
     * @param string|null $sync [optional] Whether to sync the cached files
     */
    public function run(string|null $sync = null): int
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

        if ($sync !== null && app()->debug()) {
            /** @var string[] $files */
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
     * @inheritDoc
     */
    protected function getOptions(): array
    {
        return [
            new Option('sync', 'Sync all files', 's'),
        ];
    }
}
