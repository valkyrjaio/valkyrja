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

namespace Valkyrja\Api\Middleware;

use Override;
use Throwable;
use Valkyrja\Api\Constant\Status;
use Valkyrja\Api\Manager\Contract\ApiContract;
use Valkyrja\Http\Message\Factory\Contract\ResponseFactoryContract;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Middleware\Contract\ThrowableCaughtMiddlewareContract;
use Valkyrja\Http\Middleware\Handler\Contract\ThrowableCaughtHandlerContract;
use Valkyrja\Throwable\Handler\WhoopsThrowableHandler;

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
        Throwable $exception,
        ThrowableCaughtHandlerContract $handler
    ): ResponseContract {
        $json = $this->api->jsonFromArray([
            'traceCode' => WhoopsThrowableHandler::getTraceCode($exception),
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
