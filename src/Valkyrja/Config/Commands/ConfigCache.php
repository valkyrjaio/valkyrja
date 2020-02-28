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

namespace Valkyrja\Config\Commands;

use Valkyrja\Config\Enums\ConfigKey;
use Valkyrja\Console\Commanders\Commander;
use Valkyrja\Console\Support\ProvidesCommand;

use const LOCK_EX;

/**
 * Class ConfigCache.
 *
 * @author Melech Mizrachi
 */
class ConfigCache extends Commander
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

        $config             = config();
        $config->app->debug = false;
        $config->app->env   = 'production';

        app()->setup($config, true);

        // Get the results of the cache attempt
        $result = file_put_contents(
            config(ConfigKey::CONFIG_CACHE_FILE_PATH),
            serialize(config()),
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
