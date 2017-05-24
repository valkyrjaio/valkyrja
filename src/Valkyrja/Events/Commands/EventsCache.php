<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Events\Commands;

use Valkyrja\Console\CommandHandler;

/**
 * Class EventsCache.
 *
 * @author Melech Mizrachi
 */
class EventsCache extends CommandHandler
{
    /**
     * The command.
     */
    public const COMMAND = 'events:cache';

    /**
     * Run the command.
     *
     * @return int
     */
    public function run(): int
    {
        $cache = base64_encode(serialize(events()->getCacheable()));

        // Get the results of the cache attempt
        $result = file_put_contents(
            config()['events']['cacheFilePath'],
            '<?php return ' . var_export($cache, true) . ';'
        );

        if ($result === false) {
            output()->writeMessage('An error occurred while generating events cache.', true);

            return 0;
        }

        output()->writeMessage('Events cache generated successfully', true);

        return 1;
    }
}
