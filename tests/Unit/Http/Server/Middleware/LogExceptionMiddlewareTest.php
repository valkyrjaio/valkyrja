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

namespace Valkyrja\Tests\Unit\Http\Server\Middleware;

use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Request\ServerRequest;
use Valkyrja\Http\Message\Response\Response;
use Valkyrja\Http\Middleware\Handler\ThrowableCaughtHandler;
use Valkyrja\Http\Server\Middleware\LogThrowableCaughtMiddleware;
use Valkyrja\Log\Logger\Contract\LoggerContract;
use Valkyrja\Tests\Unit\TestCase;
use Valkyrja\Throwable\Exception\Exception;

/**
 * Class LogExceptionMiddlewareTest.
 */
class LogExceptionMiddlewareTest extends TestCase
{
    public function testException(): void
    {
        $statusCode = StatusCode::INTERNAL_SERVER_ERROR;
        $request    = new ServerRequest();
        $response   = new Response(statusCode: $statusCode);
        $exception  = new Exception();
        $url        = $request->getUri()->getPath();

        $logger = $this->createMock(LoggerContract::class);
        $logger->expects($this->once())
               ->method('throwable')
               ->with(
                   $this->equalTo($exception),
                   $this->equalTo("Http Server Error\nUrl: $url"),
               );

        $handler = $this->createMock(ThrowableCaughtHandler::class);
        $handler->expects($this->once())
                ->method('throwableCaught')
                ->with(
                    $this->equalTo($request),
                    $this->equalTo($response),
                    $this->equalTo($exception),
                )
                ->willReturn($response);

        $middleware = new LogThrowableCaughtMiddleware(logger: $logger);

        $response = $middleware->throwableCaught($request, $response, $exception, $handler);

        self::assertSame($statusCode, $response->getStatusCode());
    }
}
