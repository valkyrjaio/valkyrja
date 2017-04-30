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
class ContainerCache extends CommandHandler
{
    /**
     * Run the command.
     *
     * @return int
     */
    public function run(): int
    {
        $result = file_put_contents(
            config()->container->cacheFilePath,
            '<?php return ' . var_export(container()->getCacheable(), true) . ';'
        );

        if ($result === false) {
            $this->output->writeMessage('An error occurred while generating cache.', true);

            return 0;
        }

        $this->output->writeMessage('Cache generated successfully', true);

        return 1;
    }
}
