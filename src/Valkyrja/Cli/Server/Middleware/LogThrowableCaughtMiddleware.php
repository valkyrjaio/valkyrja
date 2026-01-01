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
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Middleware\Contract\ThrowableCaughtMiddlewareContract;
use Valkyrja\Cli\Middleware\Handler\Contract\ThrowableCaughtHandlerContract;
use Valkyrja\Log\Logger\Contract\LoggerContract;

/**
 * Class LogExceptionMiddleware.
 */
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
    public function throwableCaught(InputContract $input, OutputContract $output, Throwable $exception, ThrowableCaughtHandlerContract $handler): OutputContract
    {
        $commandName = $input->getCommandName();
        $logMessage  = "Cli Server Error\nUrl: $commandName";

        $this->logger->exception($exception, $logMessage);

        return $output;
    }
}
