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

use Valkyrja\Contracts\Annotations\Annotation;
use Valkyrja\Contracts\Console\CommandHandler as CommandHandlerContract;
use Valkyrja\Dispatcher\Dispatch;

/**
 * Abstract Class CommandHandler
 *
 * @package Valkyrja\Console
 *
 * @author  Melech Mizrachi
 */
abstract class CommandHandler extends Dispatch implements Annotation, CommandHandlerContract
{
    /**
     * Run the command.
     *
     * @return mixed
     */
    abstract public function run();
}
