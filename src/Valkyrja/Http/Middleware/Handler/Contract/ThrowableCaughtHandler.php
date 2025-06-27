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

namespace Valkyrja\Http\Middleware\Handler\Contract;

use Throwable;
use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\Response;
use Valkyrja\Http\Middleware\Contract\ThrowableCaughtMiddleware;

/**
 * Interface ThrowableCaughtHandler.
 *
 * @author Melech Mizrachi
 *
 * @extends Handler<ThrowableCaughtMiddleware>
 */
interface ThrowableCaughtHandler extends Handler
{
    /**
     * Middleware handler for after a throwable has been caught during dispatch.
     */
    public function throwableCaught(ServerRequest $request, Response $response, Throwable $exception): Response;
}
