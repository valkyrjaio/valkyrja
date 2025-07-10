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

namespace Valkyrja\Cli\Middleware\Handler;

use Override;
use Throwable;
use Valkyrja\Cli\Interaction\Input\Contract\Input;
use Valkyrja\Cli\Interaction\Output\Contract\Output;
use Valkyrja\Cli\Middleware\Contract\ThrowableCaughtMiddleware;

/**
 * Class ExceptionHandler.
 *
 * @author Melech Mizrachi
 *
 * @extends Handler<ThrowableCaughtMiddleware>
 */
class ThrowableCaughtHandler extends Handler implements Contract\ThrowableCaughtHandler
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function throwableCaught(Input $input, Output $output, Throwable $exception): Output
    {
        $next = $this->next;

        return $next !== null
            ? $this->getMiddleware($next)->throwableCaught($input, $output, $exception, $this)
            : $output;
    }
}
