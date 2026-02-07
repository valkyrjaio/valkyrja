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

namespace Valkyrja\Tests\Unit\Http\Server\Handler;

use Exception;
use PHPUnit\Framework\MockObject\Exception as MockObjectException;
use Throwable;
use Valkyrja\Container\Manager\Container;
use Valkyrja\Http\Message\Request\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Message\Response\Response;
use Valkyrja\Http\Message\Throwable\Exception\HttpException;
use Valkyrja\Http\Middleware\Handler\Contract\TerminatedHandlerContract;
use Valkyrja\Http\Middleware\Handler\RequestReceivedHandler;
use Valkyrja\Http\Middleware\Handler\ThrowableCaughtHandler;
use Valkyrja\Http\Routing\Dispatcher\Router;
use Valkyrja\Http\Server\Handler\RequestHandler;
use Valkyrja\Tests\Classes\Http\Server\CloseOutputBuffersRequestHandlerClass;
use Valkyrja\Tests\Classes\Http\Server\CloseOutputBuffersWithCleanRequestHandlerClass;
use Valkyrja\Tests\Classes\Http\Server\FastCgiRequestHandlerClass;
use Valkyrja\Tests\Classes\Http\Server\LitespeedRequestHandlerClass;
use Valkyrja\Tests\Classes\Http\Server\SessionCloseRequestHandlerClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;

use function ob_start;

/**
 * Class RequestHandlerTest.
 */
class RequestHandlerTest extends TestCase
{
    /**
     * @throws MockObjectException
     * @throws Throwable
     */
    public function testHandle(): void
    {
        $response = new Response();
        $request  = new ServerRequest();

        $router = $this->createMock(Router::class);
        $router
            ->expects($this->once())
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
     * @throws MockObjectException
     * @throws Throwable
     */
    public function testHandleWithBeforeMiddleware(): void
    {
        $response  = new Response();
        $response2 = new Response();
        $request   = new ServerRequest();

        $router = $this->createMock(Router::class);
        $router
            // Router shouldn't be called since the middleware returns a response
            ->expects($this->never())
            ->method('dispatch')
            ->with($request)
            ->willReturn($response);

        $beforeHandler = $this->createMock(RequestReceivedHandler::class);
        $beforeHandler
            ->expects($this->once())
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
     * @throws MockObjectException
     * @throws Throwable
     */
    public function testHandleWithBeforeMiddlewareReturningRequest(): void
    {
        $response = new Response();
        $request  = new ServerRequest();
        $request2 = new ServerRequest();

        $router = $this->createMock(Router::class);
        $router
            ->expects($this->once())
            ->method('dispatch')
            ->with($request2)
            ->willReturn($response);

        $beforeHandler = $this->createMock(RequestReceivedHandler::class);
        $beforeHandler
            ->expects($this->once())
            ->method('requestReceived')
            ->with($request)
            ->willReturn($request2);

        $container = new Container();

        $requestHandler = new RequestHandler(
            container: $container,
            router: $router,
            requestReceivedHandler: $beforeHandler,
        );

        $handledResponse = $requestHandler->handle($request);

        self::assertSame($response, $handledResponse);
        self::assertSame($response, $container->get(ResponseContract::class));
    }

    /**
     * @throws MockObjectException
     * @throws Throwable
     */
    public function testHandleException(): void
    {
        $response  = new Response();
        $request   = new ServerRequest();
        $exception = new HttpException(response: $response);

        $router = $this->createMock(Router::class);
        $router
            ->expects($this->once())
            ->method('dispatch')
            ->with($request)
            ->willThrowException($exception);

        $throwableCaughtHandler = $this->createMock(ThrowableCaughtHandler::class);
        $throwableCaughtHandler
            ->expects($this->once())
            ->method('throwableCaught')
            ->with($request, $response, $exception)
            ->willReturn($response);

        $container = new Container();

        $requestHandler = new RequestHandler(
            container: $container,
            router: $router,
            throwableCaughtHandler: $throwableCaughtHandler,
        );

        $handledResponse = $requestHandler->handle($request);

        self::assertSame($response, $handledResponse);
        self::assertSame($response, $container->get(ResponseContract::class));
    }

    /**
     * @throws MockObjectException
     * @throws Throwable
     */
    public function testHandleExceptionWithThrowableCaughtMiddleware(): void
    {
        $response  = new Response();
        $response2 = new Response();
        $request   = new ServerRequest();
        $exception = new HttpException(response: $response);

        $router = $this->createMock(Router::class);
        $router
            ->expects($this->once())
            ->method('dispatch')
            ->with($request)
            ->willThrowException($exception);

        $throwableCaughtHandler = $this->createMock(ThrowableCaughtHandler::class);
        $throwableCaughtHandler
            ->expects($this->once())
            ->method('throwableCaught')
            ->with($request, $response, $exception)
            ->willReturn($response2);

        $container = new Container();

        $requestHandler = new RequestHandler(
            container: $container,
            router: $router,
            throwableCaughtHandler: $throwableCaughtHandler
        );

        $handledResponse = $requestHandler->handle($request);

        self::assertSame($response2, $handledResponse);
        self::assertSame($response2, $container->get(ResponseContract::class));
    }

    /**
     * @throws MockObjectException
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
            ->expects($this->once())
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
     * @throws MockObjectException
     * @throws Throwable
     */
    public function testHandleExceptionWithHttpExceptionAndNoResponse(): void
    {
        $request = new ServerRequest();

        $exception = new HttpException(message: 'test');

        $router = $this->createMock(Router::class);
        $router
            ->expects($this->once())
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
     * @throws MockObjectException
     * @throws Throwable
     */
    public function testHandleExceptionWithRandomException(): void
    {
        $request = new ServerRequest();

        $exception = new Exception(message: 'test');

        $router = $this->createMock(Router::class);
        $router
            ->expects($this->once())
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
     * @throws MockObjectException
     * @throws Throwable
     */
    public function testRun(): void
    {
        $response = new Response();
        $request  = new ServerRequest();

        $router = $this->createMock(Router::class);
        $router
            ->expects($this->once())
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
     * @throws MockObjectException
     * @throws Throwable
     */
    public function testClosingSession(): void
    {
        $response = new Response();
        $request  = new ServerRequest();

        $router = $this->createMock(Router::class);
        $router
            ->expects($this->once())
            ->method('dispatch')
            ->with($request)
            ->willReturn($response);

        $container = new Container();

        $requestHandler = new SessionCloseRequestHandlerClass(
            container: $container,
            router: $router,
        );

        self::assertFalse($requestHandler->hasSessionBeenClosed());

        $requestHandler->run($request);

        self::assertSame($response, $container->get(ResponseContract::class));
        self::assertTrue($requestHandler->hasSessionBeenClosed());
    }

    /**
     * @throws MockObjectException
     * @throws Throwable
     */
    public function testFinishRequestWithFastCgi(): void
    {
        $response = new Response();
        $request  = new ServerRequest();

        $router = $this->createMock(Router::class);
        $router
            ->expects($this->once())
            ->method('dispatch')
            ->with($request)
            ->willReturn($response);

        $container = new Container();

        $requestHandler = new FastCgiRequestHandlerClass(
            container: $container,
            router: $router,
        );

        self::assertFalse($requestHandler->hasRequestBeenFinishedWithFastCgi());

        $requestHandler->run($request);

        self::assertSame($response, $container->get(ResponseContract::class));
        self::assertTrue($requestHandler->hasRequestBeenFinishedWithFastCgi());
    }

    /**
     * @throws MockObjectException
     * @throws Throwable
     */
    public function testFinishRequestWithLitespeed(): void
    {
        $response = new Response();
        $request  = new ServerRequest();

        $router = $this->createMock(Router::class);
        $router
            ->expects($this->once())
            ->method('dispatch')
            ->with($request)
            ->willReturn($response);

        $container = new Container();

        $requestHandler = new LitespeedRequestHandlerClass(
            container: $container,
            router: $router,
        );

        self::assertFalse($requestHandler->hasRequestBeenFinishedWithLitespeed());

        $requestHandler->run($request);

        self::assertSame($response, $container->get(ResponseContract::class));
        self::assertTrue($requestHandler->hasRequestBeenFinishedWithLitespeed());
    }

    /**
     * @throws MockObjectException
     * @throws Throwable
     */
    public function testFinishRequestByClosingOutputBuffers(): void
    {
        $response = new Response();
        $request  = new ServerRequest();

        $router = $this->createMock(Router::class);
        $router
            ->expects($this->once())
            ->method('dispatch')
            ->with($request)
            ->willReturn($response);

        $container = new Container();

        $requestHandler = new CloseOutputBuffersRequestHandlerClass(
            container: $container,
            router: $router,
        );

        self::assertFalse($requestHandler->hasRequestBeenFinishedByClosingOutputBuffers());

        $requestHandler->run($request);

        self::assertSame($response, $container->get(ResponseContract::class));
        self::assertTrue($requestHandler->hasRequestBeenFinishedByClosingOutputBuffers());
    }

    /**
     * @throws MockObjectException
     * @throws Throwable
     */
    public function testFinishRequestByClosingOutputBuffersWithClean(): void
    {
        $response = new Response();
        $request  = new ServerRequest();

        $router = $this->createMock(Router::class);
        $router
            ->expects($this->once())
            ->method('dispatch')
            ->with($request)
            ->willReturn($response);

        $container = new Container();

        $requestHandler = new CloseOutputBuffersWithCleanRequestHandlerClass(
            container: $container,
            router: $router,
        );

        self::assertFalse($requestHandler->hasRequestBeenFinishedByClosingOutputBuffers());

        $requestHandler->run($request);

        self::assertSame($response, $container->get(ResponseContract::class));
        self::assertTrue($requestHandler->hasRequestBeenFinishedByClosingOutputBuffers());
    }

    /**
     * @throws MockObjectException
     * @throws Throwable
     */
    public function testHandleTerminateHandler(): void
    {
        $response = new Response();
        $request  = new ServerRequest();

        $router = $this->createMock(Router::class);
        $router
            ->expects($this->once())
            ->method('dispatch')
            ->with($request)
            ->willReturn($response);

        $terminatedHandler = $this->createMock(TerminatedHandlerContract::class);
        $terminatedHandler
            ->expects($this->once())
            ->method('terminated')
            ->with($request, $response);

        $container = new Container();

        $requestHandler = new RequestHandler(
            container: $container,
            router: $router,
            terminatedHandler: $terminatedHandler,
        );

        ob_start();
        $requestHandler->run($request);
        ob_get_clean();
    }
}
