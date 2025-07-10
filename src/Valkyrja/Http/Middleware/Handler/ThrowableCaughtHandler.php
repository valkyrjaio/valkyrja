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

namespace Valkyrja\Http\Middleware\Handler;

use Override;
use Throwable;
use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\Response;
use Valkyrja\Http\Middleware\Contract\ThrowableCaughtMiddleware;

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
    public function throwableCaught(ServerRequest $request, Response $response, Throwable $exception): Response
    {
        $next = $this->next;

        return $next !== null
            ? $this->getMiddleware($next)->throwableCaught($request, $response, $exception, $this)
            : $response;
    }
}
