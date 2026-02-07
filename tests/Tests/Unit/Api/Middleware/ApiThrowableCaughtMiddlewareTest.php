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

namespace Valkyrja\Tests\Unit\Api\Middleware;

use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use Valkyrja\Api\Constant\Status;
use Valkyrja\Api\Manager\Contract\ApiContract;
use Valkyrja\Api\Middleware\ApiThrowableCaughtMiddleware;
use Valkyrja\Api\Model\Json;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\Contract\JsonResponseContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Message\Response\Factory\Contract\ResponseFactoryContract;
use Valkyrja\Http\Middleware\Contract\ThrowableCaughtMiddlewareContract;
use Valkyrja\Http\Middleware\Handler\Contract\ThrowableCaughtHandlerContract;
use Valkyrja\Tests\Unit\Abstract\TestCase;

use function is_string;

/**
 * Test the ApiThrowableCaughtMiddleware.
 */
class ApiThrowableCaughtMiddlewareTest extends TestCase
{
    protected ApiContract&MockObject $api;
    protected ResponseFactoryContract&MockObject $responseFactory;
    protected ServerRequestContract&MockObject $request;
    protected ResponseContract&MockObject $response;
    protected ThrowableCaughtHandlerContract&MockObject $handler;
    protected JsonResponseContract&MockObject $jsonResponse;
    protected ApiThrowableCaughtMiddleware $middleware;

    protected function setUp(): void
    {
        $this->api             = $this->createMock(ApiContract::class);
        $this->responseFactory = $this->createMock(ResponseFactoryContract::class);
        $this->request         = $this->createMock(ServerRequestContract::class);
        $this->response        = $this->createMock(ResponseContract::class);
        $this->handler         = $this->createMock(ThrowableCaughtHandlerContract::class);
        $this->jsonResponse    = $this->createMock(JsonResponseContract::class);

        $this->middleware = new ApiThrowableCaughtMiddleware(
            $this->api,
            $this->responseFactory
        );
    }

    public function testImplementsContract(): void
    {
        $this->api->expects($this->never())->method(self::anything());
        $this->responseFactory->expects($this->never())->method(self::anything());
        $this->request->expects($this->never())->method(self::anything());
        $this->response->expects($this->never())->method(self::anything());
        $this->handler->expects($this->never())->method(self::anything());
        $this->jsonResponse->expects($this->never())->method(self::anything());

        self::assertInstanceOf(ThrowableCaughtMiddlewareContract::class, $this->middleware);
    }

    public function testThrowableCaughtCreatesJsonResponse(): void
    {
        $exception  = new Exception('Test error');
        $statusCode = StatusCode::INTERNAL_SERVER_ERROR;
        $headers    = ['X-Custom' => ['value']];

        $json = new Json();

        $this->request->expects($this->never())->method(self::anything());
        $this->response->expects($this->once())
            ->method('getStatusCode')
            ->willReturn($statusCode);
        $this->response->expects($this->once())
            ->method('getHeaders')
            ->willReturn($headers);

        $this->api->expects($this->once())
            ->method('jsonFromArray')
            ->with(self::callback(static fn (array $data): bool => isset($data['traceCode']) && is_string($data['traceCode'])))
            ->willReturn($json);

        $this->responseFactory->expects($this->once())
            ->method('createJsonResponse')
            ->with(
                self::callback(static fn (array $data): bool => isset($data['status']) && $data['status'] === Status::ERROR),
                $statusCode,
                $headers
            )
            ->willReturn($this->jsonResponse);

        $this->handler->expects($this->never())->method(self::anything());
        $this->jsonResponse->expects($this->never())->method(self::anything());

        $result = $this->middleware->throwableCaught(
            $this->request,
            $this->response,
            $exception,
            $this->handler
        );

        self::assertSame($this->jsonResponse, $result);
    }

    public function testThrowableCaughtPreservesResponseStatusCode(): void
    {
        $exception  = new Exception('Not found');
        $statusCode = StatusCode::NOT_FOUND;

        $json = new Json();

        $this->request->expects($this->never())->method(self::anything());
        $this->response->expects($this->once())
            ->method('getStatusCode')
            ->willReturn($statusCode);
        $this->response->expects($this->once())
            ->method('getHeaders')
            ->willReturn([]);

        $this->api->expects($this->once())
            ->method('jsonFromArray')
            ->willReturn($json);

        $this->responseFactory->expects($this->once())
            ->method('createJsonResponse')
            ->with(
                self::anything(),
                $statusCode,
                []
            )
            ->willReturn($this->jsonResponse);

        $this->handler->expects($this->never())->method(self::anything());
        $this->jsonResponse->expects($this->never())->method(self::anything());

        $result = $this->middleware->throwableCaught(
            $this->request,
            $this->response,
            $exception,
            $this->handler
        );

        self::assertInstanceOf(ResponseContract::class, $result);
    }

    public function testThrowableCaughtSetsErrorStatus(): void
    {
        $exception = new Exception('Error');
        $json      = new Json();

        $this->request->expects($this->never())->method(self::anything());
        $this->response->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(StatusCode::INTERNAL_SERVER_ERROR);
        $this->response->expects($this->once())
            ->method('getHeaders')
            ->willReturn([]);

        $this->api->expects($this->once())
            ->method('jsonFromArray')
            ->willReturn($json);

        $this->responseFactory->expects($this->once())
            ->method('createJsonResponse')
            ->with(
                self::callback(static fn (array $data): bool => $data['status'] === Status::ERROR),
                self::anything(),
                self::anything()
            )
            ->willReturn($this->jsonResponse);

        $this->handler->expects($this->never())->method(self::anything());
        $this->jsonResponse->expects($this->never())->method(self::anything());

        $this->middleware->throwableCaught(
            $this->request,
            $this->response,
            $exception,
            $this->handler
        );
    }

    public function testThrowableCaughtPreservesResponseHeaders(): void
    {
        $exception = new Exception('Error');
        $headers   = [
            'X-Request-Id' => ['abc123'],
            'X-Trace-Id'   => ['xyz789'],
        ];
        $json = new Json();

        $this->request->expects($this->never())->method(self::anything());
        $this->response->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(StatusCode::INTERNAL_SERVER_ERROR);
        $this->response->expects($this->once())
            ->method('getHeaders')
            ->willReturn($headers);

        $this->api->expects($this->once())
            ->method('jsonFromArray')
            ->willReturn($json);

        $this->responseFactory->expects($this->once())
            ->method('createJsonResponse')
            ->with(
                self::anything(),
                self::anything(),
                $headers
            )
            ->willReturn($this->jsonResponse);

        $this->handler->expects($this->never())->method(self::anything());
        $this->jsonResponse->expects($this->never())->method(self::anything());

        $this->middleware->throwableCaught(
            $this->request,
            $this->response,
            $exception,
            $this->handler
        );
    }
}
