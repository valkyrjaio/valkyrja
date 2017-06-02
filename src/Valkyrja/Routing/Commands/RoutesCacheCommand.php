<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Routing\Commands;

use Valkyrja\Console\CommandHandler;
use Valkyrja\Console\Support\ProvidesCommand;

/**
 * Class RoutingCache.
 *
 * @author Melech Mizrachi
 */
class RoutesCacheCommand extends CommandHandler
{
    use ProvidesCommand;

    /**
     * The command.
     */
    public const COMMAND           = 'routes:cache';
    public const PATH              = self::COMMAND;
    public const SHORT_DESCRIPTION = 'Generate the routes cache';
    public const DESCRIPTION       = '';

    /**
     * Run the command.
     *
     * @return int
     */
    public function run(): int
    {
        $originalDebug = config()['app']['debug'];
        $originalEnv   = config()['app']['env'];

        config()['app']['debug'] = false;
        config()['app']['env']   = 'production';

        $cache = router()->getCacheable();

        config()['app']['debug'] = $originalDebug;
        config()['app']['env']   = $originalEnv;

        // Get the results of the cache attempt
        $result = file_put_contents(
            config()['routing']['cacheFilePath'],
            '<?php return ' . var_export($cache, true) . ';'
        );

        if ($result === false) {
            output()->writeMessage('An error occurred while generating routes cache.', true);

            return 0;
        }

        output()->writeMessage('Routes cache generated successfully', true);

        return 1;
    }
}
