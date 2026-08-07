<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Http\Server\Middleware\SendingResponse;

use Valkyrja\Container\Manager\Container;
use Valkyrja\Http\Message\Constant\HeaderName;
use Valkyrja\Http\Message\Request\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Message\Response\EmptyResponse;
use Valkyrja\Http\Middleware\Handler\SendingResponseHandler;
use Valkyrja\Http\Server\Middleware\SendingResponse\NoCacheResponseMiddleware;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class NoCacheMiddlewareTest extends TestCase
{
    public function testThroughHandler(): void
    {
        $container = new Container();
        $container->bindSingleton(
            NoCacheResponseMiddleware::class,
            static fn (): NoCacheResponseMiddleware => new NoCacheResponseMiddleware()
        );

        $handler = new SendingResponseHandler($container);
        $handler->add(NoCacheResponseMiddleware::class);

        $request  = new ServerRequest();
        $response = new EmptyResponse();

        $responseAfterMiddleware = $handler->sendingResponse($request, $response);

        // Ensure immutability
        self::assertNotSame($responseAfterMiddleware, $response);

        $this->assertions($responseAfterMiddleware);
    }

    public function testDirectly(): void
    {
        $middleware = new NoCacheResponseMiddleware();
        $handler    = new SendingResponseHandler();

        $request  = new ServerRequest();
        $response = new EmptyResponse();

        $responseAfterMiddleware = $middleware->sendingResponse($request, $response, $handler);

        // Ensure immutability
        self::assertNotSame($responseAfterMiddleware, $response);

        $this->assertions($responseAfterMiddleware);
    }

    protected function assertions(ResponseContract $response): void
    {
        $headers = $response->getHeaders();

        self::assertTrue($headers->has(HeaderName::EXPIRES));
        self::assertTrue($headers->has(HeaderName::CACHE_CONTROL));
        self::assertTrue($headers->has(HeaderName::PRAGMA));
        self::assertSame(
            'Sun, 01 Jan 2014 00:00:00 GMT',
            $headers->get(HeaderName::EXPIRES)->getHeaderLine(),
        );
        self::assertSame(
            'no-store, no-cache, must-revalidate, post-check=0, pre-check=0',
            $headers->get(HeaderName::CACHE_CONTROL)->getHeaderLine(),
        );
        self::assertSame(
            'no-cache',
            $headers->get(HeaderName::PRAGMA)->getHeaderLine(),
        );
    }
}
