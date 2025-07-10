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
use Valkyrja\Cli\Interaction\Input\Contract\Input;
use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Interaction\Message\NewLine;
use Valkyrja\Cli\Interaction\Output\Contract\Output;
use Valkyrja\Cli\Middleware\Contract\ThrowableCaughtMiddleware;
use Valkyrja\Cli\Middleware\Handler\Contract\ThrowableCaughtHandler;

/**
 * Class OutputThrowableCaughtMiddleware.
 *
 * @author Melech Mizrachi
 */
class OutputThrowableCaughtMiddleware implements ThrowableCaughtMiddleware
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function throwableCaught(Input $input, Output $output, Throwable $exception, ThrowableCaughtHandler $handler): Output
    {
        $commandName = $input->getCommandName();

        $output->withAddedMessages(
            new Message('Cli Server Error:'),
            new NewLine(),
            new NewLine("Url: $commandName"),
            new NewLine(),
            new NewLine('Message: ' . $exception->getMessage()),
            new NewLine(),
            new NewLine('Line: ' . ((string) $exception->getLine())),
            new NewLine(),
            new NewLine('Trace: ' . $exception->getTraceAsString()),
        );

        return $output;
    }
}
