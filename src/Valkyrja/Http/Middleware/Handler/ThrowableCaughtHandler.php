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
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Middleware\Contract\ThrowableCaughtMiddlewareContract;
use Valkyrja\Http\Middleware\Handler\Abstract\Handler;
use Valkyrja\Http\Middleware\Handler\Contract\ThrowableCaughtHandlerContract as Contract;

/**
 * @extends Handler<ThrowableCaughtMiddlewareContract>
 */
class ThrowableCaughtHandler extends Handler implements Contract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function throwableCaught(ServerRequestContract $request, ResponseContract $response, Throwable $throwable): ResponseContract
    {
        $next = $this->next;

        return $next !== null
            ? $this->getMiddleware($next)->throwableCaught($request, $response, $throwable, $this)
            : $response;
    }
}
