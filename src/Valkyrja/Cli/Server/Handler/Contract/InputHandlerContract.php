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

use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;

/**
 * Interface InputHandlerContract.
 *
 * @author Melech Mizrachi
 */
interface InputHandlerContract
{
    /**
     * Handle the input.
     *
     * @param InputContract $input The input
     *
     * @return OutputContract
     */
    public function handle(InputContract $input): OutputContract;

    /**
     * Handle exiting the handler.
     *
     * @param InputContract  $input  The input
     * @param OutputContract $output The output
     *
     * @return void
     */
    public function exit(InputContract $input, OutputContract $output): void;

    /**
     * Run the handler.
     *
     * @param InputContract $input
     *
     * @return void
     */
    public function run(InputContract $input): void;
}
