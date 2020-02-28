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

use Valkyrja\Config\Enums\ConfigKey;
use Valkyrja\Console\Commanders\Commander;
use Valkyrja\Console\Enums\ExitCode;
use Valkyrja\Console\Support\ProvidesCommand;

use const LOCK_EX;

/**
 * Class Optimize.
 *
 * @author Melech Mizrachi
 */
class Optimize extends Commander
{
    use ProvidesCommand;

    /**
     * The command.
     */
    public const COMMAND           = 'optimize';
    public const PATH              = self::COMMAND;
    public const SHORT_DESCRIPTION = 'Optimize the application';

    /**
     * Optimize the application.
     *
     * @return int
     */
    public function run(): int
    {
        // If the cache file already exists, delete it
        if (file_exists(config(ConfigKey::CONFIG_CACHE_FILE_PATH))) {
            unlink(config(ConfigKey::CONFIG_CACHE_FILE_PATH));
        }

        $configCache = config();

        $configCache->app->debug = false;
        $configCache->app->env   = 'production';

        $containerCache = container()->getCacheable();
        $consoleCache   = console()->getCacheable();
        $eventsCache    = events()->getCacheable();
        $routesCache    = router()->getCacheable();

        $configCache->container->cache = $containerCache;
        $configCache->console->cache   = $consoleCache;
        $configCache->event->cache     = $eventsCache;
        $configCache->routing->cache   = $routesCache;

        $configCache->container->useCache = true;
        $configCache->console->useCache   = true;
        $configCache->event->useCache     = true;
        $configCache->routing->useCache   = true;

        // Get the results of the cache attempt
        $result = file_put_contents(
            config(ConfigKey::CONFIG_CACHE_FILE_PATH),
            serialize($configCache),
            LOCK_EX
        );

        if ($result === false) {
            output()->writeMessage('An error occurred while optimizing the application.', true);

            return ExitCode::FAILURE;
        }

        output()->writeMessage('Application optimized successfully', true);

        return ExitCode::SUCCESS;
    }
}
