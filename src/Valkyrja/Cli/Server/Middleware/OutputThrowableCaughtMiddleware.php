<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Cli\Server\Middleware;

use Override;
use Throwable;
use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Interaction\Message\NewLine;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Middleware\Contract\ThrowableCaughtMiddlewareContract;
use Valkyrja\Cli\Middleware\Handler\Contract\ThrowableCaughtHandlerContract;

/**
 * Class OutputThrowableCaughtMiddleware.
 */
class OutputThrowableCaughtMiddleware implements ThrowableCaughtMiddlewareContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function throwableCaught(InputContract $input, OutputContract $output, Throwable $exception, ThrowableCaughtHandlerContract $handler): OutputContract
    {
        $commandName = $input->getCommandName();

        $output->withAddedMessages(
            new Message('Cli Server Error:'),
            new NewLine(),
            new Message("Url: $commandName"),
            new NewLine(),
            new Message('Message: ' . $exception->getMessage()),
            new NewLine(),
            new Message('Line: ' . ((string) $exception->getLine())),
            new NewLine(),
            new Message('Trace: ' . $exception->getTraceAsString()),
        );

        return $output;
    }
}
