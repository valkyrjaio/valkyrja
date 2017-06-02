<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Config\Commands;

use Valkyrja\Console\CommandHandler;
use Valkyrja\Console\Support\ProvidesCommand;

/**
 * Class ConfigCache.
 *
 * @author Melech Mizrachi
 */
class ConfigCache extends CommandHandler
{
    use ProvidesCommand;

    /**
     * The command.
     */
    public const COMMAND           = 'config:cache';
    public const PATH              = self::COMMAND;
    public const SHORT_DESCRIPTION = 'Generate the config cache';

    /**
     * Run the command.
     *
     * @return int
     */
    public function run(): int
    {
        // If the cache file already exists, delete it
        if (file_exists(config()['cacheFilePath'])) {
            unlink(config()['cacheFilePath']);
        }

        // If the config file exists
        if (file_exists(config()['filePath'])) {
            // Resetup the application with it
            app()->setup(require config()['filePath'], true);
        } else {
            // Otherwise just resetup the application with default configs
            app()->setup(null, true);
        }

        $cache = config();

        // Get the results of the cache attempt
        $result = file_put_contents(
            config()['cacheFilePath'],
            '<?php return ' . var_export($cache, true) . ';'
        );

        if ($result === false) {
            output()->writeMessage('An error occurred while generating config cache.', true);

            return 0;
        }

        output()->writeMessage('Config cache generated successfully', true);

        return 1;
    }
}
