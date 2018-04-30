<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Container\Commands;

use Valkyrja\Console\CommandHandler;
use Valkyrja\Console\Support\ProvidesCommand;

/**
 * Class ContainerCache.
 *
 * @author Melech Mizrachi
 */
class ContainerCacheCommand extends CommandHandler
{
    use ProvidesCommand;

    /**
     * The command.
     */
    public const COMMAND           = 'container:cache';
    public const PATH              = self::COMMAND;
    public const SHORT_DESCRIPTION = 'Generate the container cache';

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

        $cache = container()->getCacheable();

        config()['app']['debug'] = $originalDebug;
        config()['app']['env']   = $originalEnv;

        // Get the results of the cache attempt
        $result = file_put_contents(
            config()['container']['cacheFilePath'],
            '<?php return ' . var_export($cache, true) . ';',
            LOCK_EX
        );

        if ($result === false) {
            output()->writeMessage(
                'An error occurred while generating container cache.',
                true
            );

            return 1;
        }

        output()->writeMessage('Container cache generated successfully', true);

        return 0;
    }
}
