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
        $cacheFilePath = config()->cacheFilePath;

        // If the cache file already exists, delete it
        if (file_exists($cacheFilePath)) {
            unlink($cacheFilePath);
        }

        $config             = config();
        $config->app->debug = false;
        $config->app->env   = 'production';

        $serialized = serialize($config);
        $serialized = preg_replace('/O:\d+:"[^"]++"/', 'O:8:"stdClass"', $serialized);

        // Get the results of the cache attempt
        $result = file_put_contents($cacheFilePath, $serialized, LOCK_EX);

        if ($result === false) {
            output()->writeMessage('An error occurred while generating config cache.', true);

            return 1;
        }

        output()->writeMessage('Config cache generated successfully', true);

        return 0;
    }
}
