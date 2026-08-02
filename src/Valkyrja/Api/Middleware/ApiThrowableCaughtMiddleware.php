<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Api\Middleware;

use Override;
use Throwable;
use Valkyrja\Api\Constant\Status;
use Valkyrja\Api\Manager\Contract\ApiContract;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Message\Response\Factory\Contract\ResponseFactoryContract;
use Valkyrja\Http\Middleware\Contract\ThrowableCaughtMiddlewareContract;
use Valkyrja\Http\Middleware\Handler\Contract\ThrowableCaughtHandlerContract;
use Valkyrja\Throwable\Factory\ThrowableFactory;

class ApiThrowableCaughtMiddleware implements ThrowableCaughtMiddlewareContract
{
    public function __construct(
        protected ApiContract $api,
        protected ResponseFactoryContract $responseFactory,
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
        $json = $this->api->jsonFromArray([
            'traceCode' => ThrowableFactory::getTraceCode($throwable),
        ]);

        $json->setStatus(Status::ERROR);
        $json->setStatusCode($statusCode = $response->getStatusCode());

        return $this->responseFactory->createJsonResponse(
            data: $json->asArray(),
            statusCode: $statusCode,
            headers: $response->getHeaders()
        );
    }
}
