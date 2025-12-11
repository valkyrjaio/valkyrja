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

use Valkyrja\Exception\Exception;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Request\ServerRequest;
use Valkyrja\Http\Message\Response\Response;
use Valkyrja\Http\Middleware\Handler\ThrowableCaughtHandler;
use Valkyrja\Http\Server\Middleware\ViewThrowableCaughtMiddleware;
use Valkyrja\Tests\Unit\TestCase;
use Valkyrja\View\Factory\ResponseFactory;

/**
 * Class ViewExceptionMiddlewareTest.
 *
 * @author Melech Mizrachi
 */
class ViewExceptionMiddlewareTest extends TestCase
{
    public function testException(): void
    {
        $statusCode = StatusCode::INTERNAL_SERVER_ERROR;
        $request    = new ServerRequest();
        $response   = new Response(statusCode: $statusCode);
        $handler    = new ThrowableCaughtHandler();
        $exception  = new Exception();

        $args = [
            'exception' => $exception,
            'request'   => $request,
            'response'  => $response,
        ];

        $templateText = 'Error: 500';

        $view = self::createStub(ResponseFactory::class);
        $view->method('createResponseFromView')
             ->with('errors/500', $args)
             ->willReturn(Response::create(content: $templateText, statusCode: $statusCode));

        $middleware = new ViewThrowableCaughtMiddleware(viewResponseFactory: $view);

        $response = $middleware->throwableCaught($request, $response, $exception, $handler);

        self::assertSame($templateText, (string) $response->getBody());
        self::assertSame($statusCode, $response->getStatusCode());
    }
}
