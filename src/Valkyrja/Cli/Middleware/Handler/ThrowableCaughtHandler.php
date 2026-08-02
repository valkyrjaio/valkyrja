<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Cli\Middleware\Handler;

use Override;
use Throwable;
use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Middleware\Contract\ThrowableCaughtMiddlewareContract;
use Valkyrja\Cli\Middleware\Handler\Abstract\Handler;
use Valkyrja\Cli\Middleware\Handler\Contract\ThrowableCaughtHandlerContract;

/**
 * @extends Handler<ThrowableCaughtMiddlewareContract>
 */
class ThrowableCaughtHandler extends Handler implements ThrowableCaughtHandlerContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function throwableCaught(InputContract $input, OutputContract $output, Throwable $throwable): OutputContract
    {
        $next = $this->next;

        return $next !== null
            ? $this->getMiddleware($next)->throwableCaught($input, $output, $throwable, $this)
            : $output;
    }
}
