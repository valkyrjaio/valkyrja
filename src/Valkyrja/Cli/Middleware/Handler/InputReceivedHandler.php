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
use Valkyrja\Cli\Middleware\Contract\InputReceivedMiddlewareContract;
use Valkyrja\Cli\Middleware\Handler\Abstract\Handler;
use Valkyrja\Cli\Middleware\Handler\Contract\InputReceivedHandlerContract;

/**
 * @extends Handler<InputReceivedMiddlewareContract>
 */
class InputReceivedHandler extends Handler implements InputReceivedHandlerContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function inputReceived(InputContract $input): InputContract|OutputContract
    {
        $next = $this->next;

        return $next !== null
            ? $this->getMiddleware($next)->inputReceived($input, $this)
            : $input;
    }
}
