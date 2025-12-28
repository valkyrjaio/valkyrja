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
use Valkyrja\Http\Server\Middleware\LogThrowableCaughtMiddleware;
use Valkyrja\Log\Logger\Contract\Logger;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Class LogExceptionMiddlewareTest.
 *
 * @author Melech Mizrachi
 */
class LogExceptionMiddlewareTest extends TestCase
{
    public function testException(): void
    {
        $statusCode = StatusCode::INTERNAL_SERVER_ERROR;
        $request    = new ServerRequest();
        $response   = new Response(statusCode: $statusCode);
        $handler    = new ThrowableCaughtHandler();
        $exception  = new Exception();

        $logger = self::createStub(Logger::class);

        $middleware = new LogThrowableCaughtMiddleware(logger: $logger);

        $response = $middleware->throwableCaught($request, $response, $exception, $handler);

        self::assertSame($statusCode, $response->getStatusCode());
    }
}
