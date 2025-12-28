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

namespace Valkyrja\Cli\Server\Handler\Contract;

use Valkyrja\Cli\Interaction\Input\Contract\Input;
use Valkyrja\Cli\Interaction\Output\Contract\Output;

/**
 * Interface InputHandler.
 *
 * @author Melech Mizrachi
 */
interface InputHandler
{
    /**
     * Handle the input.
     *
     * @param Input $input The input
     *
     * @return Output
     */
    public function handle(Input $input): Output;

    /**
     * Handle exiting the handler.
     *
     * @param Input  $input  The input
     * @param Output $output The output
     *
     * @return void
     */
    public function exit(Input $input, Output $output): void;

    /**
     * Run the handler.
     *
     * @param Input $input
     *
     * @return void
     */
    public function run(Input $input): void;
}
