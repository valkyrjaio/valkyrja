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

namespace Valkyrja\Http\Middleware\Contract;

use Throwable;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Middleware\Handler\Contract\ThrowableCaughtHandlerContract;

/**
 * Interface ThrowableCaughtMiddlewareContract.
 *
 * @author Melech Mizrachi
 */
interface ThrowableCaughtMiddlewareContract
{
    /**
     * Middleware handler for after a throwable has been caught during dispatch.
     */
    public function throwableCaught(
        ServerRequestContract $request,
        ResponseContract $response,
        Throwable $exception,
        ThrowableCaughtHandlerContract $handler
    ): ResponseContract;
}
