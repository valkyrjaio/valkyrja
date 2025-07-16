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
use Valkyrja\Api\Contract\Api;
use Valkyrja\Exception\ExceptionHandler;
use Valkyrja\Http\Message\Factory\Contract\ResponseFactory;
use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\Response;
use Valkyrja\Http\Middleware\Contract\ThrowableCaughtMiddleware;
use Valkyrja\Http\Middleware\Handler\Contract\ThrowableCaughtHandler;

/**
 * Class ApiExceptionMiddleware.
 *
 * @author Melech Mizrachi
 */
class ApiThrowableCaughtMiddleware implements ThrowableCaughtMiddleware
{
    public function __construct(
        protected Api $api,
        protected ResponseFactory $responseFactory,
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function throwableCaught(ServerRequest $request, Response $response, Throwable $exception, ThrowableCaughtHandler $handler): Response
    {
        $json = $this->api->jsonFromArray([
            'traceCode' => ExceptionHandler::getTraceCode($exception),
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
