<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Console\Handlers;

use Valkyrja\Console\CommandHandler;

/**
 * Class GenerateCache
 *
 * @package Valkyrja\Console\Handlers
 *
 * @author  Melech Mizrachi
 */
class ConsoleCache extends CommandHandler
{
    /**
     * Run the command.
     *
     * @return int
     */
    public function run(): int
    {
        // The original use cache file value (may not be using cache to begin with)
        $originalUseCacheFile = config()->console->useCacheFile;
        // Avoid using the cache file we already have
        config()->console->useCacheFile = false;

        // Get the results of the cache attempt
        $result = file_put_contents(
            config()->console->cacheFilePath,
            '<?php return ' . var_export(console()->getCacheable(), true) . ';'
        );

        // Reset the use cache file value
        config()->console->useCacheFile = $originalUseCacheFile;

        if ($result === false) {
            output()->writeMessage('An error occurred while generating cache.', true);

            return 0;
        }

        output()->writeMessage('Cache generated successfully', true);

        return 1;
    }
}
