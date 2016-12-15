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

use Valkyrja\Support\Directory;

/**
 * Class Routing
 *
 * @package Valkyrja\Console
 *
 * @author Melech Mizrachi
 */
class Routing extends Console
{
    /**
     *
     */
    public function run()
    {
        file_put_contents(
            Directory::basePath('bootstrap/cache/routes.php'),
            '<?php return ' . var_export(router()->getRoutes(), true) . ';'
        );
    }
}
