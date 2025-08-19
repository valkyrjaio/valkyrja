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

use Throwable;
use Valkyrja\Cli\Interaction\Input\Contract\Input;
use Valkyrja\Cli\Interaction\Output\Contract\Output;
use Valkyrja\Cli\Middleware\Contract\ThrowableCaughtMiddleware;
use Valkyrja\Cli\Middleware\Handler\Contract\ThrowableCaughtHandler;
use Valkyrja\Tests\Classes\Cli\Middleware\Trait\MiddlewareCounterTrait;

/**
 * Class TestThrowableCaughtMiddlewareChanged.
 *
 * @author Melech Mizrachi
 */
class ThrowableCaughtMiddlewareChangedClass implements ThrowableCaughtMiddleware
{
    use MiddlewareCounterTrait;

    public function throwableCaught(Input $input, Output $output, Throwable $exception, ThrowableCaughtHandler $handler): Output
    {
        $this->updateCounter();

        // Return a different output instead of calling the handler to simulate early exit
        return new \Valkyrja\Cli\Interaction\Output\Output();
    }
}
