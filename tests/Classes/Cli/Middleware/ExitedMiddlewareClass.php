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
use Valkyrja\Cli\Middleware\Contract\ExitedMiddleware;
use Valkyrja\Cli\Middleware\Handler\Contract\ExitedHandler;
use Valkyrja\Tests\Classes\Cli\Middleware\Trait\MiddlewareCounterTrait;

/**
 * Class TestExitedMiddleware.
 *
 * @author Melech Mizrachi
 */
class ExitedMiddlewareClass implements ExitedMiddleware
{
    use MiddlewareCounterTrait;

    public function exited(Input $input, Output $output, ExitedHandler $handler): void
    {
        $this->updateCounter();

        $handler->exited($input, $output);
    }
}
