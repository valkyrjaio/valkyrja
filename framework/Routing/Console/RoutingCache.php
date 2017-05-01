<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Routing\Console;

use Valkyrja\Console\CommandHandler;

/**
 * Class GenerateCache
 *
 * @package Valkyrja\Routing\Console
 *
 * @author  Melech Mizrachi
 */
class RoutingCache extends CommandHandler
{
    /**
     * The command.
     */
    public const COMMAND = 'routes:cache';

    /**
     * Run the command.
     *
     * @return int
     */
    public function run(): int
    {
        // Get the results of the cache attempt
        $result = file_put_contents(
            config()->routing->routesCacheFile,
            '<?php return ' . var_export(router()->getCacheable(), true) . ';'
        );

        if ($result === false) {
            output()->writeMessage('An error occurred while generating routes cache.', true);

            return 0;
        }

        output()->writeMessage('Routes cache generated successfully', true);

        return 1;
    }
}
