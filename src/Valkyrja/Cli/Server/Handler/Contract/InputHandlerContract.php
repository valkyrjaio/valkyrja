<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Cli\Server\Handler\Contract;

use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;

interface InputHandlerContract
{
    /**
     * Handle the input.
     *
     * @param InputContract $input The input
     */
    public function handle(InputContract $input): OutputContract;

    /**
     * Handle exiting the handler.
     *
     * @param InputContract  $input  The input
     * @param OutputContract $output The output
     */
    public function exit(InputContract $input, OutputContract $output): void;

    /**
     * Run the handler.
     */
    public function run(InputContract $input): void;
}
