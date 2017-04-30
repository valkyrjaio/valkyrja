<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Container\Console;

use Valkyrja\Console\CommandHandler;

/**
 * Class GenerateCache
 *
 * @package Valkyrja\Container\Console
 *
 * @author  Melech Mizrachi
 */
class GenerateCache extends CommandHandler
{
    /**
     * Run the command.
     *
     * @return int
     */
    public function run(): int
    {
        file_put_contents(
            config()->container->cacheFilePath,
            '<?php return ' . var_export(container()->getCacheable(), true) . ';'
        );

        return 1;
    }
}
