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
use Valkyrja\Console\Support\ProvidesCommand;

/**
 * Class Optimize.
 *
 * @author Melech Mizrachi
 */
class OptimizeCommand extends CommandHandler
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
        if (file_exists(config()['cacheFilePath'])) {
            unlink(config()['cacheFilePath']);
        }

        app()->setup(
            [
                'app' => [
                    'debug' => false,
                    'env'   => 'production',
                ],
            ],
            true
        );

        $containerCache = container()->getCacheable();
        $consoleCache   = console()->getCacheable();
        $eventsCache    = events()->getCacheable();
        $routesCache    = router()->getCacheable();
        $configCache    = config();

        $configCache['cache']['container'] = $containerCache;
        $configCache['cache']['console']   = $consoleCache;
        $configCache['cache']['events']    = $eventsCache;
        $configCache['cache']['routing']   = $routesCache;

        $configCache['container']['useCache'] = true;
        $configCache['console']['useCache']   = true;
        $configCache['events']['useCache']    = true;
        $configCache['routing']['useCache']   = true;

        // Get the results of the cache attempt
        $result = file_put_contents(
            config()['cacheFilePath'],
            '<?php return ' . var_export($configCache, true) . ';'
        );

        if ($result === false) {
            output()->writeMessage(
                'An error occurred while optimizing the application.',
                true
            );

            return 0;
        }

        output()->writeMessage('Application optimized successfully', true);

        return 1;
    }
}
