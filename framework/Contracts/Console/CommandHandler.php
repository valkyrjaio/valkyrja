<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Contracts\Console;

/**
 * Interface Command
 *
 * @package Valkyrja\Contracts\Console
 *
 * @author  Melech Mizrachi
 */
interface CommandHandler
{
    /**
     * CommandHandler constructor.
     *
     * @param \Valkyrja\Contracts\Console\Input  $input  The input
     * @param \Valkyrja\Contracts\Console\Output $output The output
     */
    public function __construct(Input $input, Output $output);

    /**
     * Run the command.
     *
     * @return mixed
     */
    public function run();
}
