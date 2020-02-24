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

namespace Valkyrja\Console\Facades;

use Valkyrja\Application\Applications\Valkyrja;
use Valkyrja\Console\Input;
use Valkyrja\Console\Output;
use Valkyrja\Facade\Facades\Facade;

/**
 * Class Kernel.
 *
 * @author Melech Mizrachi
 *
 * @method static int handle(Input $input, Output $output)
 * @method static void terminate(Input $input, int $exitCode)
 * @method static int run(Input $input = null, Output $output = null)
 */
class Kernel extends Facade
{
    /**
     * The facade instance.
     *
     * @return string|object
     */
    public static function instance()
    {
        return Valkyrja::app()->consoleKernel();
    }
}
