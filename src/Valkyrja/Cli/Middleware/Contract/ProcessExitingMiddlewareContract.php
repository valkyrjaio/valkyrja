<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Cli\Middleware\Contract;

use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Middleware\Handler\Contract\ProcessExitingHandlerContract;

interface ProcessExitingMiddlewareContract
{
    /**
     * Middleware handler ran when the process is exiting.
     */
    public function processExiting(InputContract $input, OutputContract $output, ProcessExitingHandlerContract $handler): void;
}
