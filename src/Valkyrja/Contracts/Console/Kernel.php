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

use Valkyrja\Contracts\Console\Input\Input;
use Valkyrja\Contracts\Console\Output\Output;

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
     * @param \Valkyrja\Contracts\Console\Input\Input   $input  The input
     * @param \Valkyrja\Contracts\Console\Output\Output $output The output
     *
     * @throws \Valkyrja\Http\Exceptions\HttpException
     *
     * @return int
     */
    public function handle(Input $input, Output $output): int;

    /**
     * Terminate the kernel request.
     *
     * @param \Valkyrja\Contracts\Console\Input\Input $input    The input
     * @param int                                     $exitCode The response
     *
     * @return void
     */
    public function terminate(Input $input, int $exitCode): void;

    /**
     * Run the kernel.
     *
     * @param \Valkyrja\Contracts\Console\Input\Input   $input  The input
     * @param \Valkyrja\Contracts\Console\Output\Output $output The output
     *
     * @throws \Valkyrja\Http\Exceptions\HttpException
     *
     * @return void
     */
    public function run(Input $input = null, Output $output = null): void;
}
