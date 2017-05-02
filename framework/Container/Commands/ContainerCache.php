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

/**
 * Class ContainerCache
 *
 * @package Valkyrja\Container\Commands
 *
 * @author  Melech Mizrachi
 */
class ContainerCache extends CommandHandler
{
    /**
     * The command.
     */
    public const COMMAND = 'container:cache';

    /**
     * Run the command.
     *
     * @return int
     */
    public function run(): int
    {
        // Get the results of the cache attempt
        $result = file_put_contents(
            config()->container->cacheFilePath,
            '<?php return ' . var_export(container()->getCacheable(), true) . ';'
        );

        if ($result === false) {
            output()->writeMessage('An error occurred while generating container cache.', true);

            return 0;
        }

        output()->writeMessage('Container cache generated successfully', true);

        return 1;
    }
}
