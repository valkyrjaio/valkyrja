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

namespace Valkyrja\Cli\Middleware\Handler\Contract;

use Closure;
use Valkyrja\Cli\Middleware\Contract\CommandDispatchedMiddleware;
use Valkyrja\Cli\Middleware\Contract\CommandMatchedMiddleware;
use Valkyrja\Cli\Middleware\Contract\CommandNotMatchedMiddleware;
use Valkyrja\Cli\Middleware\Contract\ExitedMiddleware;
use Valkyrja\Cli\Middleware\Contract\InputReceivedMiddleware;
use Valkyrja\Cli\Middleware\Contract\ThrowableCaughtMiddleware;
use Valkyrja\Container\Contract\Container;

/**
 * Interface Handler.
 *
 * @author Melech Mizrachi
 *
 * @template Middleware of InputReceivedMiddleware|CommandMatchedMiddleware|CommandNotMatchedMiddleware|CommandDispatchedMiddleware|ThrowableCaughtMiddleware|ExitedMiddleware
 */
interface Handler
{
    /**
     * @param class-string<Middleware>|Closure(Container): Middleware ...$middleware The middleware to add
     */
    public function add(Closure|string ...$middleware): void;
}
