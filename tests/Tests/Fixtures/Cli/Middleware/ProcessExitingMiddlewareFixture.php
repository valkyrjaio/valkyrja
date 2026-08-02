<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Cli\Middleware;

use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Middleware\Contract\ProcessExitingMiddlewareContract;
use Valkyrja\Cli\Middleware\Handler\Contract\ProcessExitingHandlerContract;
use Valkyrja\Tests\Fixtures\Cli\Middleware\Trait\MiddlewareCounterTrait;

/**
 * Class TestProcessExitingMiddleware.
 */
final class ProcessExitingMiddlewareFixture implements ProcessExitingMiddlewareContract
{
    use MiddlewareCounterTrait;

    public function processExiting(InputContract $input, OutputContract $output, ProcessExitingHandlerContract $handler): void
    {
        $this->updateCounter();

        $handler->processExiting($input, $output);
    }
}
