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
use Valkyrja\Cli\Middleware\Contract\CommandNotMatchedMiddleware;
use Valkyrja\Cli\Middleware\Handler\Contract\CommandNotMatchedHandler;
use Valkyrja\Tests\Classes\Cli\Middleware\Trait\MiddlewareCounterTrait;

/**
 * Class TestCommandNotMatchedMiddleware.
 *
 * @author Melech Mizrachi
 */
class CommandNotMatchedMiddlewareClass implements CommandNotMatchedMiddleware
{
    use MiddlewareCounterTrait;

    public function commandNotMatched(Input $input, Output $output, CommandNotMatchedHandler $handler): Output
    {
        $this->updateCounter();

        return $handler->commandNotMatched($input, $output);
    }
}
