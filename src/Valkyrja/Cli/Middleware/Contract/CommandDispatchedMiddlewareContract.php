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

namespace Valkyrja\Cli\Middleware\Contract;

use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Middleware\Handler\Contract\CommandDispatchedHandlerContract;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;

/**
 * Interface CommandDispatchedMiddlewareContract.
 */
interface CommandDispatchedMiddlewareContract
{
    /**
     * Middleware handler for after a command is dispatched.
     */
    public function commandDispatched(
        InputContract $input,
        OutputContract $output,
        RouteContract $command,
        CommandDispatchedHandlerContract $handler
    ): OutputContract;
}
