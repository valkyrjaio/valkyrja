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

use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Middleware\Contract\ResponseSentMiddlewareContract;
use Valkyrja\Http\Middleware\Handler\Contract\ResponseSentHandlerContract;
use Valkyrja\Tests\Fixtures\Http\Middleware\Trait\MiddlewareCounterTrait;

/**
 * Class TestResponseSentMiddleware.
 */
final class ResponseSentMiddlewareFixture implements ResponseSentMiddlewareContract
{
    use MiddlewareCounterTrait;

    public function responseSent(ServerRequestContract $request, ResponseContract $response, ResponseSentHandlerContract $handler): void
    {
        $this->updateCounter();

        $handler->responseSent($request, $response);
    }
}
