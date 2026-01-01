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

namespace Valkyrja\Tests\Classes\Cli\Middleware;

use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Middleware\Contract\CommandDispatchedMiddlewareContract;
use Valkyrja\Cli\Middleware\Handler\Contract\CommandDispatchedHandlerContract;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;
use Valkyrja\Tests\Classes\Cli\Middleware\Trait\MiddlewareCounterTrait;

/**
 * Class TestCommandDispatchedMiddleware.
 *
 * @author Melech Mizrachi
 */
class CommandDispatchedMiddlewareClass implements CommandDispatchedMiddlewareContract
{
    use MiddlewareCounterTrait;

    public function commandDispatched(
        InputContract $input,
        OutputContract $output,
        RouteContract $command,
        CommandDispatchedHandlerContract $handler
    ): OutputContract {
        $this->updateCounter();

        return $handler->commandDispatched($input, $output, $command);
    }
}
