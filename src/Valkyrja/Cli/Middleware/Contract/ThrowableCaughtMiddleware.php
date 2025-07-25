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

use Throwable;
use Valkyrja\Cli\Interaction\Input\Contract\Input;
use Valkyrja\Cli\Interaction\Output\Contract\Output;
use Valkyrja\Cli\Middleware\Handler\Contract\ThrowableCaughtHandler;

/**
 * Interface ThrowableCaughtMiddleware.
 *
 * @author Melech Mizrachi
 */
interface ThrowableCaughtMiddleware
{
    /**
     * Middleware handler for after a throwable has been caught during dispatch.
     */
    public function throwableCaught(Input $input, Output $output, Throwable $exception, ThrowableCaughtHandler $handler): Output;
}
