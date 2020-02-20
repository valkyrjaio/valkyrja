<?php

declare(strict_types = 1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Config\Commands;

use Valkyrja\Config\Enums\ConfigKey;
use Valkyrja\Console\Handlers\Handler;
use Valkyrja\Console\Support\ProvidesCommand;

/**
 * Class ConfigCache.
 *
 * @author Melech Mizrachi
 */
class ConfigCache extends Handler
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
        if (file_exists(config(ConfigKey::CONFIG_CACHE_FILE_PATH))) {
            unlink(config(ConfigKey::CONFIG_CACHE_FILE_PATH));
        }

        // If the config file exists
        if (file_exists(config(ConfigKey::CONFIG_FILE_PATH))) {
            // Re-setup the application with it
            app()->setup(require config(ConfigKey::CONFIG_FILE_PATH), true);
        } else {
            // Otherwise just re-setup the application with default configs
            app()->setup(null, true);
        }

        $cache = config();

        // Get the results of the cache attempt
        $result = file_put_contents(
            config(ConfigKey::CONFIG_CACHE_FILE_PATH),
            '<?php

declare(strict_types=1); return ' . var_export($cache, true) . ';',
            LOCK_EX
        );

        if ($result === false) {
            output()->writeMessage('An error occurred while generating config cache.', true);

            return 1;
        }

        output()->writeMessage('Config cache generated successfully', true);

        return 0;
    }
}
