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
use Valkyrja\Cli\Interaction\Enum\ExitCode;
use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Cli\Interaction\Message\Banner;
use Valkyrja\Cli\Interaction\Message\ErrorMessage;
use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Interaction\Message\NewLine;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Middleware\Contract\ThrowableCaughtMiddlewareContract;
use Valkyrja\Cli\Middleware\Handler\Contract\ThrowableCaughtHandlerContract;

class OutputThrowableCaughtMiddleware implements ThrowableCaughtMiddlewareContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function throwableCaught(InputContract $input, OutputContract $output, Throwable $throwable, ThrowableCaughtHandlerContract $handler): OutputContract
    {
        $commandName = $input->getCommandName();

        $output = $output
            ->withExitCode(exitCode: ExitCode::ERROR)
            ->withMessages(
                new Banner(new ErrorMessage('Cli Server Error:')),
                new NewLine(),
                new ErrorMessage('Command:'),
                new Message(" $commandName"),
                new NewLine(),
                new NewLine(),
                new ErrorMessage('Message:'),
                new Message(' ' . $throwable->getMessage()),
                new NewLine(),
                new NewLine(),
                new ErrorMessage('Line:'),
                new Message(' ' . ((string) $throwable->getLine())),
                new NewLine(),
                new NewLine(),
                new ErrorMessage('Trace:'),
                new NewLine(),
                new Message($throwable->getTraceAsString() . "\n")
            );

        return $handler->throwableCaught($input, $output, $throwable);
    }
}
