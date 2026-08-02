<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Middleware\Handler;

use Override;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Middleware\Contract\SendingResponseMiddlewareContract;
use Valkyrja\Http\Middleware\Handler\Abstract\Handler;
use Valkyrja\Http\Middleware\Handler\Contract\SendingResponseHandlerContract;

/**
 * @extends Handler<SendingResponseMiddlewareContract>
 */
class SendingResponseHandler extends Handler implements SendingResponseHandlerContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function sendingResponse(ServerRequestContract $request, ResponseContract $response): ResponseContract
    {
        $next = $this->next;

        return $next !== null
            ? $this->getMiddleware($next)->sendingResponse($request, $response, $this)
            : $response;
    }
}
