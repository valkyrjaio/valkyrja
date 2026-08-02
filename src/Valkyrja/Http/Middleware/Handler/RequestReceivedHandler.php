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
use Valkyrja\Http\Middleware\Contract\RequestReceivedMiddlewareContract;
use Valkyrja\Http\Middleware\Handler\Abstract\Handler;
use Valkyrja\Http\Middleware\Handler\Contract\RequestReceivedHandlerContract;

/**
 * @extends Handler<RequestReceivedMiddlewareContract>
 */
class RequestReceivedHandler extends Handler implements RequestReceivedHandlerContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function requestReceived(ServerRequestContract $request): ResponseContract|ServerRequestContract
    {
        $next = $this->next;

        return $next !== null
            ? $this->getMiddleware($next)->requestReceived($request, $this)
            : $request;
    }
}
