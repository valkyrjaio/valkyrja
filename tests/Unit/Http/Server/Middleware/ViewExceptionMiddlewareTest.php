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
use Valkyrja\Http\Server\Middleware\ViewThrowableCaughtMiddleware;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Throwable\Exception\Exception;
use Valkyrja\View\Factory\ResponseFactory;

/**
 * Class ViewExceptionMiddlewareTest.
 */
class ViewExceptionMiddlewareTest extends TestCase
{
    public function testException(): void
    {
        $statusCode = StatusCode::INTERNAL_SERVER_ERROR;
        $request    = new ServerRequest();
        $response   = new Response(statusCode: $statusCode);
        $exception  = new Exception();

        $args = [
            'exception' => $exception,
            'request'   => $request,
            'response'  => $response,
        ];

        $templateText = 'Error: 500';

        $viewResponse = Response::create(content: $templateText, statusCode: $statusCode);

        $view = $this->createMock(ResponseFactory::class);
        $view->expects($this->once())
            ->method('createResponseFromView')
            ->with(
                self::equalTo('errors/500'),
                self::equalTo($args)
            )
            ->willReturn($viewResponse);

        $handler = $this->createMock(ThrowableCaughtHandler::class);
        $handler->expects($this->once())
            ->method('throwableCaught')
            ->with(
                self::equalTo($request),
                self::equalTo($viewResponse),
                self::equalTo($exception),
            )
            ->willReturn($viewResponse);

        $middleware = new ViewThrowableCaughtMiddleware(viewResponseFactory: $view);

        $response = $middleware->throwableCaught($request, $response, $exception, $handler);

        self::assertSame($templateText, (string) $response->getBody());
        self::assertSame($statusCode, $response->getStatusCode());
    }
}
