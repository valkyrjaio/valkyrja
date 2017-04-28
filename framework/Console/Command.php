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

use Valkyrja\Contracts\Console\Command as CommandContract;

/**
 * Abstract Class Command
 *
 * @package Valkyrja\Console
 *
 * @author  Melech Mizrachi
 */
abstract class Command implements CommandContract
{
    /**
     * Run the command.
     *
     * @return mixed
     */
    abstract public function run();
}
