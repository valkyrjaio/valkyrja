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

use Throwable;
use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Interaction\Output\Output;
use Valkyrja\Cli\Middleware\Contract\ThrowableCaughtMiddlewareContract;
use Valkyrja\Cli\Middleware\Handler\Contract\ThrowableCaughtHandlerContract;
use Valkyrja\Tests\Fixtures\Cli\Middleware\Trait\MiddlewareCounterTrait;

/**
 * Class TestThrowableCaughtMiddlewareChanged.
 */
final class ThrowableCaughtMiddlewareChangedFixture implements ThrowableCaughtMiddlewareContract
{
    use MiddlewareCounterTrait;

    public function throwableCaught(InputContract $input, OutputContract $output, Throwable $throwable, ThrowableCaughtHandlerContract $handler): OutputContract
    {
        $this->updateCounter();

        // Return a different output instead of calling the handler to simulate early exit
        return new Output();
    }
}
