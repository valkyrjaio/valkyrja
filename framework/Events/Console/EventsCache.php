<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Events\Console;

use Valkyrja\Console\CommandHandler;

/**
 * Class GenerateCache
 *
 * @package Valkyrja\Events\Console
 *
 * @author  Melech Mizrachi
 */
class EventsCache extends CommandHandler
{
    /**
     * Run the command.
     *
     * @return int
     */
    public function run(): int
    {
        file_put_contents(
            config()->events->cacheFilePath,
            '<?php return ' . var_export(events()->getCacheable(), true) . ';'
        );

        return 1;
    }
}
