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

use Valkyrja\Console\Console;

/**
 * Class GenerateCache
 *
 * @package Valkyrja\Container\Console
 *
 * @author  Melech Mizrachi
 */
class GenerateCache extends Console
{
    /**
     * Run the command.
     *
     * @return mixed
     */
    public function run()
    {
        return file_put_contents(
            config()->container->cacheFilePath,
            '<?php return ' . var_export(container()->getCacheable(), true) . ';'
        );
    }
}
