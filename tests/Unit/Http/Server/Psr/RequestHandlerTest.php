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

namespace Valkyrja\Tests\Unit\Http\Server\Psr;

use PHPUnit\Framework\MockObject\Exception;
use Throwable;
use Valkyrja\Container\Container;
use Valkyrja\Http\Message\Request\Psr\ServerRequest as PsrServerRequest;
use Valkyrja\Http\Message\Request\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\Response as ResponseContract;
use Valkyrja\Http\Message\Response\Response;
use Valkyrja\Http\Message\Stream\Stream;
use Valkyrja\Http\Middleware\Handler\RouteDispatchedHandler;
use Valkyrja\Http\Routing\Router;
use Valkyrja\Http\Server\Psr\RequestHandler as PsrRequestHandler;
use Valkyrja\Http\Server\RequestHandler;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Class RequestHandlerTest.
 *
 * @author Melech Mizrachi
 */
class RequestHandlerTest extends TestCase
{
    /**
     * @throws Throwable
     * @throws Exception
     */
    public function testHandle(): void
    {
        $value = 'test';

        $body = new Stream();
        $body->write($value);
        $body->rewind();

        $response   = new Response(body: $body);
        $request    = new ServerRequest();
        $psrRequest = new PsrServerRequest(request: $request);

        $router = self::createStub(Router::class);
        $router
            ->method('dispatch')
            ->withAnyParameters()
            ->willReturn($response);

        $container = new Container();

        $dispatchedHandler = new RouteDispatchedHandler();

        $requestHandler    = new RequestHandler(
            container: $container,
            router: $router,
        );
        $psrRequestHandler = new PsrRequestHandler(
            requestHandler: $requestHandler
        );

        $handledResponse = $psrRequestHandler->handle($psrRequest);

        self::assertSame($value, (string) $handledResponse->getBody());
        self::assertSame($response, $container->get(ResponseContract::class));
    }
}
