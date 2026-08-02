<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Cli\Server\Middleware\ThrowableCaught;

use Override;
use Throwable;
use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Middleware\Contract\ThrowableCaughtMiddlewareContract;
use Valkyrja\Cli\Middleware\Handler\Contract\ThrowableCaughtHandlerContract;
use Valkyrja\Log\Logger\Contract\LoggerContract;

class LogThrowableCaughtMiddleware implements ThrowableCaughtMiddlewareContract
{
    public function __construct(
        protected LoggerContract $logger,
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function throwableCaught(InputContract $input, OutputContract $output, Throwable $throwable, ThrowableCaughtHandlerContract $handler): OutputContract
    {
        $commandName = $input->getCommandName();
        $logMessage  = "Cli Server Error\nUrl: $commandName";

        $this->logger->throwable($throwable, $logMessage);

        return $handler->throwableCaught($input, $output, $throwable);
    }
}
