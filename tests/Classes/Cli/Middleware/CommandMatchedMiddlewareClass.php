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
use Valkyrja\Cli\Middleware\Contract\CommandMatchedMiddleware;
use Valkyrja\Cli\Middleware\Handler\Contract\CommandMatchedHandler;
use Valkyrja\Cli\Routing\Data\Contract\Command;
use Valkyrja\Tests\Classes\Cli\Middleware\Trait\MiddlewareCounterTrait;

/**
 * Class TestCommandMatchedMiddleware.
 *
 * @author Melech Mizrachi
 */
class CommandMatchedMiddlewareClass implements CommandMatchedMiddleware
{
    use MiddlewareCounterTrait;

    public function commandMatched(Input $input, Command $command, CommandMatchedHandler $handler): Command|Output
    {
        $this->updateCounter();

        return $handler->commandMatched($input, $command);
    }
}
