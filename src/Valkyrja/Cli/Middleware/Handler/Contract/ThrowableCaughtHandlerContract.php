<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Cli\Middleware\Handler\Contract;

use Throwable;
use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Middleware\Contract\ThrowableCaughtMiddlewareContract;

/**
 * @extends HandlerContract<ThrowableCaughtMiddlewareContract>
 */
interface ThrowableCaughtHandlerContract extends HandlerContract
{
    /**
     * Middleware handler for after a throwable has been caught during dispatch.
     */
    public function throwableCaught(InputContract $input, OutputContract $output, Throwable $throwable): OutputContract;
}
