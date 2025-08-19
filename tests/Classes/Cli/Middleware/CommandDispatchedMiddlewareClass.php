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

use Valkyrja\Cli\Interaction\Input\Contract\Input;
use Valkyrja\Cli\Interaction\Output\Contract\Output;
use Valkyrja\Cli\Middleware\Contract\CommandDispatchedMiddleware;
use Valkyrja\Cli\Middleware\Handler\Contract\CommandDispatchedHandler;
use Valkyrja\Cli\Routing\Data\Contract\Command;
use Valkyrja\Tests\Classes\Cli\Middleware\Trait\MiddlewareCounterTrait;

/**
 * Class TestCommandDispatchedMiddleware.
 *
 * @author Melech Mizrachi
 */
class CommandDispatchedMiddlewareClass implements CommandDispatchedMiddleware
{
    use MiddlewareCounterTrait;

    public function commandDispatched(Input $input, Output $output, Command $command, CommandDispatchedHandler $handler): Output
    {
        $this->updateCounter();

        return $handler->commandDispatched($input, $output, $command);
    }
}
