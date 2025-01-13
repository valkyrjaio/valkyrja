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

namespace Valkyrja\Tests\Unit\Http\Server;

use Exception;
use Throwable;
use Valkyrja\Container\Container;
use Valkyrja\Http\Message\Exception\HttpException;
use Valkyrja\Http\Message\Request\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\Response as ResponseContract;
use Valkyrja\Http\Message\Response\Response;
use Valkyrja\Http\Middleware\Handler\RequestReceivedHandler;
use Valkyrja\Http\Middleware\Handler\ThrowableCaughtHandler;
use Valkyrja\Http\Routing\Router;
use Valkyrja\Http\Server\RequestHandler;
use Valkyrja\Tests\Classes\Http\Server\CloseOutputBuffersRequestHandler;
use Valkyrja\Tests\Classes\Http\Server\CloseOutputBuffersWithCleanRequestHandler;
use Valkyrja\Tests\Classes\Http\Server\FastCgiRequestHandler;
use Valkyrja\Tests\Classes\Http\Server\LitespeedRequestHandler;
use Valkyrja\Tests\Classes\Http\Server\SessionCloseRequestHandler;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Class RequestHandlerTest.
 *
 * @author Melech Mizrachi
 */
class RequestHandlerTest extends TestCase
{
    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     * @throws Throwable
     */
    public function testHandle(): void
    {
        $response = new Response();
        $request  = new ServerRequest();

        $router = $this->createMock(Router::class);
        $router
            ->method('dispatch')
            ->with($request)
            ->willReturn($response);

        $container = new Container();

        $requestHandler = new RequestHandler(
            container: $container,
            router: $router,
        );

        $handledResponse = $requestHandler->handle($request);

        self::assertSame($response, $handledResponse);
        self::assertSame($response, $container->get(ResponseContract::class));
    }

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     * @throws Throwable
     */
    public function testHandleWithBeforeMiddleware(): void
    {
        $response  = new Response();
        $response2 = new Response();
        $request   = new ServerRequest();

        $router = $this->createMock(Router::class);
        $router
            ->method('dispatch')
            ->with($request)
            ->willReturn($response);

        $beforeHandler = $this->createMock(RequestReceivedHandler::class);
        $beforeHandler
            ->method('requestReceived')
            ->with($request)
            ->willReturn($response2);

        $container = new Container();

        $requestHandler = new RequestHandler(
            container: $container,
            router: $router,
            requestReceivedHandler: $beforeHandler,
        );

        $handledResponse = $requestHandler->handle($request);

        self::assertSame($response2, $handledResponse);
        self::assertSame($response2, $container->get(ResponseContract::class));
    }

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     * @throws Throwable
     */
    public function testHandleException(): void
    {
        $response  = new Response();
        $request   = new ServerRequest();
        $exception = new HttpException(response: $response);

        $router = $this->createMock(Router::class);
        $router
            ->method('dispatch')
            ->with($request)
            ->willThrowException($exception);

        $container = new Container();

        $requestHandler = new RequestHandler(
            container: $container,
            router: $router,
        );

        $handledResponse = $requestHandler->handle($request);

        self::assertSame($exception->getResponse(), $handledResponse);
        self::assertSame($exception->getResponse(), $container->get(ResponseContract::class));
    }

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     * @throws Throwable
     */
    public function testHandleExceptionWithExceptionMiddleware(): void
    {
        $response  = new Response();
        $response2 = new Response();
        $request   = new ServerRequest();
        $exception = new HttpException(response: $response);

        $router = $this->createMock(Router::class);
        $router
            ->method('dispatch')
            ->with($request)
            ->willThrowException($exception);

        $exceptionHandler = $this->createMock(ThrowableCaughtHandler::class);
        $exceptionHandler
            ->method('throwableCaught')
            ->with($request, $response)
            ->willReturn($response2);

        $container = new Container();

        $requestHandler = new RequestHandler(
            container: $container,
            router: $router,
            exceptionHandler: $exceptionHandler
        );

        $handledResponse = $requestHandler->handle($request);

        self::assertSame($response2, $handledResponse);
        self::assertSame($response2, $container->get(ResponseContract::class));
    }

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     * @throws Throwable
     */
    public function testHandleExceptionWithDebugOn(): void
    {
        $response  = new Response();
        $exception = new HttpException(response: $response);

        $this->expectException($exception::class);

        $request = new ServerRequest();

        $router = $this->createMock(Router::class);
        $router
            ->method('dispatch')
            ->with($request)
            ->willThrowException($exception);

        $container = new Container();

        $requestHandler = new RequestHandler(
            container: $container,
            router: $router,
            debug: true
        );

        $requestHandler->handle($request);
    }

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     * @throws Throwable
     */
    public function testHandleExceptionWithHttpExceptionAndNoResponse(): void
    {
        $request = new ServerRequest();

        $exception = new HttpException(message: 'test');

        $router = $this->createMock(Router::class);
        $router
            ->method('dispatch')
            ->with($request)
            ->willThrowException($exception);

        $container = new Container();

        $requestHandler = new RequestHandler(
            container: $container,
            router: $router
        );

        $handledResponse = $requestHandler->handle($request);

        self::assertSame($handledResponse, $container->get(ResponseContract::class));
    }

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     * @throws Throwable
     */
    public function testHandleExceptionWithRandomException(): void
    {
        $request = new ServerRequest();

        $exception = new Exception(message: 'test');

        $router = $this->createMock(Router::class);
        $router
            ->method('dispatch')
            ->with($request)
            ->willThrowException($exception);

        $container = new Container();

        $requestHandler = new RequestHandler(
            container: $container,
            router: $router
        );

        $handledResponse = $requestHandler->handle($request);

        self::assertSame($handledResponse, $container->get(ResponseContract::class));
    }

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     * @throws Throwable
     */
    public function testRun(): void
    {
        $response = new Response();
        $request  = new ServerRequest();

        $router = $this->createMock(Router::class);
        $router
            ->method('dispatch')
            ->with($request)
            ->willReturn($response);

        $container = new Container();

        $requestHandler = new RequestHandler(
            container: $container,
            router: $router,
        );

        $requestHandler->run($request);

        self::assertSame($response, $container->get(ResponseContract::class));
    }

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     * @throws Throwable
     */
    public function testClosingSession(): void
    {
        $response = new Response();
        $request  = new ServerRequest();

        $router = $this->createMock(Router::class);
        $router
            ->method('dispatch')
            ->with($request)
            ->willReturn($response);

        $container = new Container();

        $requestHandler = new SessionCloseRequestHandler(
            container: $container,
            router: $router,
        );

        self::assertFalse($requestHandler->hasSessionBeenClosed());

        $requestHandler->run($request);

        self::assertSame($response, $container->get(ResponseContract::class));
        self::assertTrue($requestHandler->hasSessionBeenClosed());
    }

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     * @throws Throwable
     */
    public function testFinishRequestWithFastCgi(): void
    {
        $response = new Response();
        $request  = new ServerRequest();

        $router = $this->createMock(Router::class);
        $router
            ->method('dispatch')
            ->with($request)
            ->willReturn($response);

        $container = new Container();

        $requestHandler = new FastCgiRequestHandler(
            container: $container,
            router: $router,
        );

        self::assertFalse($requestHandler->hasRequestBeenFinishedWithFastCgi());

        $requestHandler->run($request);

        self::assertSame($response, $container->get(ResponseContract::class));
        self::assertTrue($requestHandler->hasRequestBeenFinishedWithFastCgi());
    }

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     * @throws Throwable
     */
    public function testFinishRequestWithLitespeed(): void
    {
        $response = new Response();
        $request  = new ServerRequest();

        $router = $this->createMock(Router::class);
        $router
            ->method('dispatch')
            ->with($request)
            ->willReturn($response);

        $container = new Container();

        $requestHandler = new LitespeedRequestHandler(
            container: $container,
            router: $router,
        );

        self::assertFalse($requestHandler->hasRequestBeenFinishedWithLitespeed());

        $requestHandler->run($request);

        self::assertSame($response, $container->get(ResponseContract::class));
        self::assertTrue($requestHandler->hasRequestBeenFinishedWithLitespeed());
    }

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     * @throws Throwable
     */
    public function testFinishRequestByClosingOutputBuffers(): void
    {
        $response = new Response();
        $request  = new ServerRequest();

        $router = $this->createMock(Router::class);
        $router
            ->method('dispatch')
            ->with($request)
            ->willReturn($response);

        $container = new Container();

        $requestHandler = new CloseOutputBuffersRequestHandler(
            container: $container,
            router: $router,
        );

        self::assertFalse($requestHandler->hasRequestBeenFinishedByClosingOutputBuffers());

        $requestHandler->run($request);

        self::assertSame($response, $container->get(ResponseContract::class));
        self::assertTrue($requestHandler->hasRequestBeenFinishedByClosingOutputBuffers());
    }

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     * @throws Throwable
     */
    public function testFinishRequestByClosingOutputBuffersWithClean(): void
    {
        $response = new Response();
        $request  = new ServerRequest();

        $router = $this->createMock(Router::class);
        $router
            ->method('dispatch')
            ->with($request)
            ->willReturn($response);

        $container = new Container();

        $requestHandler = new CloseOutputBuffersWithCleanRequestHandler(
            container: $container,
            router: $router,
        );

        self::assertFalse($requestHandler->hasRequestBeenFinishedByClosingOutputBuffers());

        $requestHandler->run($request);

        self::assertSame($response, $container->get(ResponseContract::class));
        self::assertTrue($requestHandler->hasRequestBeenFinishedByClosingOutputBuffers());
    }
}
