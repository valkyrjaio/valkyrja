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
use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Middleware\Contract\ThrowableCaughtMiddlewareContract;
use Valkyrja\Cli\Middleware\Handler\Abstract\Handler;
use Valkyrja\Cli\Middleware\Handler\Contract\ThrowableCaughtHandlerContract as Contract;

/**
 * Class ExceptionHandler.
 *
 * @extends Handler<ThrowableCaughtMiddlewareContract>
 */
class ThrowableCaughtHandler extends Handler implements Contract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function throwableCaught(InputContract $input, OutputContract $output, Throwable $exception): OutputContract
    {
        $next = $this->next;

        return $next !== null
            ? $this->getMiddleware($next)->throwableCaught($input, $output, $exception, $this)
            : $output;
    }
}
