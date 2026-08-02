<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Server\Middleware\ThrowableCaught;

use Override;
use Throwable;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Middleware\Contract\ThrowableCaughtMiddlewareContract;
use Valkyrja\Http\Middleware\Handler\Contract\ThrowableCaughtHandlerContract;
use Valkyrja\Log\Logger\Contract\LoggerContract;

class LogThrowableCaughtMiddleware implements ThrowableCaughtMiddlewareContract
{
    public function __construct(
        protected LoggerContract $logger,
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function throwableCaught(
        ServerRequestContract $request,
        ResponseContract $response,
        Throwable $throwable,
        ThrowableCaughtHandlerContract $handler
    ): ResponseContract {
        $url        = $request->getUri()->getPath();
        $logMessage = "Http Server Error\nUrl: $url";

        $this->logger->throwable($throwable, $logMessage);

        return $handler->throwableCaught($request, $response, $throwable);
    }
}
