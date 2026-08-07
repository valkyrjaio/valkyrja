<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Http\Middleware;

use Override;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Middleware\Contract\SendingResponseMiddlewareContract;
use Valkyrja\Http\Middleware\Handler\Contract\SendingResponseHandlerContract;
use Valkyrja\Tests\Fixtures\Http\Middleware\Trait\MiddlewareCounterTrait;

/**
 * Class TestSendingResponseMiddleware.
 */
final class SendingResponseMiddlewareFixture implements SendingResponseMiddlewareContract
{
    use MiddlewareCounterTrait;

    #[Override]
    public function sendingResponse(ServerRequestContract $request, ResponseContract $response, SendingResponseHandlerContract $handler): ResponseContract
    {
        $this->updateCounter();

        return $handler->sendingResponse($request, $response);
    }
}
