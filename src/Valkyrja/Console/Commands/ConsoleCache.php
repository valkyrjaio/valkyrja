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

/**
 * Class ConsoleCache.
 *
 * @author Melech Mizrachi
 */
class ConsoleCache extends CommandHandler
{
    /**
     * The command.
     */
    public const COMMAND = 'console:cache';

    /**
     * Run the command.
     *
     * @return int
     */
    public function run(): int
    {
        $cache = base64_encode(serialize(console()->getCacheable()));

        // Get the results of the cache attempt
        $result = file_put_contents(
            config()->console->cacheFilePath,
            '<?php return ' . var_export($cache, true) . ';'
        );

        if ($result === false) {
            output()->writeMessage('An error occurred while generating console cache.', true);

            return 0;
        }

        output()->writeMessage('Console cache generated successfully', true);

        return 1;
    }
}
