<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Console\Kernel\Contract;

use Valkyrja\Console\Input\Contract\Input;
use Valkyrja\Console\Output\Contract\Output;
use Valkyrja\Http\Exceptions\HttpException;

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
     * @throws HttpException
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
     * @param Input|null  $input  The input
     * @param Output|null $output The output
     *
     * @throws HttpException
     *
     * @return int
     */
    public function run(Input|null $input = null, Output|null $output = null): int;
}
