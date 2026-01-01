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
use Valkyrja\Cli\Interaction\Output\Output;
use Valkyrja\Cli\Middleware\Contract\CommandMatchedMiddlewareContract;
use Valkyrja\Cli\Middleware\Handler\Contract\CommandMatchedHandlerContract;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;
use Valkyrja\Tests\Classes\Cli\Middleware\Trait\MiddlewareCounterTrait;

/**
 * Class TestCommandMatchedMiddlewareChanged.
 */
class CommandMatchedMiddlewareChangedClass implements CommandMatchedMiddlewareContract
{
    use MiddlewareCounterTrait;

    public function commandMatched(InputContract $input, RouteContract $command, CommandMatchedHandlerContract $handler): RouteContract|OutputContract
    {
        $this->updateCounter();

        // Return an output instead of calling the handler to simulate early exit
        return new Output();
    }
}
