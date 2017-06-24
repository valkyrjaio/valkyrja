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

use Valkyrja\Console\Input\Input;
use Valkyrja\Console\Output\Output;

/**
 * Interface ConsoleKernel.
 *
 * @author Melech Mizrachi
 */
interface Kernel
{
    /**
     * Handle a console input.
     *
     * @param Input  $input  The input
     * @param Output $output The output
     *
     * @throws \Valkyrja\Http\Exceptions\HttpException
     *
     * @return int
     */
    public function handle(Input $input, Output $output): int;

    /**
     * Terminate the kernel request.
     *
     * @param Input $input    The input
     * @param int   $exitCode The response
     *
     * @return void
     */
    public function terminate(Input $input, int $exitCode): void;

    /**
     * Run the kernel.
     *
     * @param Input  $input  The input
     * @param Output $output The output
     *
     * @throws \Valkyrja\Http\Exceptions\HttpException
     *
     * @return void
     */
    public function run(Input $input = null, Output $output = null): void;
}
