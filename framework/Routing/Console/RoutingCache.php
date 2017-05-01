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
     * Run the command.
     *
     * @return int
     */
    public function run(): int
    {
        // The original use cache file value (may not be using cache to begin with)
        $originalUseCacheFile = config()->routing->useCacheFile;
        // Avoid using the cache file we already have
        config()->routing->useCacheFile = false;

        // Get the results of the cache attempt
        $result = file_put_contents(
            config()->routing->routesCacheFile,
            '<?php return ' . var_export(router()->getRoutes(), true) . ';'
        );

        // Reset the use cache file value
        config()->routing->useCacheFile = $originalUseCacheFile;

        if ($result === false) {
            $this->output->writeMessage('An error occurred while generating cache.', true);

            return 0;
        }

        $this->output->writeMessage('Cache generated successfully', true);

        return 1;
    }
}
