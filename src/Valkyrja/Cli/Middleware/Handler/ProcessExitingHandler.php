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
use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Middleware\Contract\ProcessExitingMiddlewareContract;
use Valkyrja\Cli\Middleware\Handler\Abstract\Handler;
use Valkyrja\Cli\Middleware\Handler\Contract\ProcessExitingHandlerContract;

/**
 * @extends Handler<ProcessExitingMiddlewareContract>
 */
class ProcessExitingHandler extends Handler implements ProcessExitingHandlerContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function processExiting(InputContract $input, OutputContract $output): void
    {
        $next = $this->next;

        if ($next !== null) {
            $this->getMiddleware($next)->processExiting($input, $output, $this);
        }
    }
}
