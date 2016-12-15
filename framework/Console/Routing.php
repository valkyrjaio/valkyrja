<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Console;

/**
 * Class Routing
 *
 * @package Valkyrja\Console
 *
 * @author  Melech Mizrachi
 */
class Routing extends Console
{
    /**
     *
     */
    public function run()
    {
        file_put_contents(
            config()->routing->routesCacheFile,
            '<?php return ' . var_export(router()->getRoutes(), true) . ';'
        );
    }
}
