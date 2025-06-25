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

use Throwable;
use Valkyrja\Cli\Interaction\Input\Contract\Input;
use Valkyrja\Cli\Interaction\Output\Contract\Output;
use Valkyrja\Cli\Middleware\Contract\ThrowableCaughtMiddleware;
use Valkyrja\Cli\Middleware\Handler\Contract\ThrowableCaughtHandler;
use Valkyrja\Log\Contract\Logger;

/**
 * Class LogExceptionMiddleware.
 *
 * @author Melech Mizrachi
 */
class LogThrowableCaughtMiddleware implements ThrowableCaughtMiddleware
{
    public function __construct(
        protected Logger $logger = new \Valkyrja\Log\Logger(),
    ) {
    }

    /**
     * @inheritDoc
     */
    public function throwableCaught(Input $input, Output $output, Throwable $exception, ThrowableCaughtHandler $handler): Output
    {
        $commandName = $input->getCommandName();
        $logMessage  = "Cli Server Error\nUrl: $commandName";

        $this->logger->exception($exception, $logMessage);

        return $output;
    }
}
