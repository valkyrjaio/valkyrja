<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Http\Server\Middleware\RequestReceived;

use Valkyrja\Http\Message\Request\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\RedirectResponseContract;
use Valkyrja\Http\Message\Uri\Uri;
use Valkyrja\Http\Middleware\Handler\RequestReceivedHandler;
use Valkyrja\Http\Server\Middleware\RequestReceived\RedirectTrailingSlashMiddleware;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class RedirectTrailingSlashMiddlewareTest extends TestCase
{
    public function testBeforeHandling(): void
    {
        $request    = new ServerRequest();
        $handler    = new RequestReceivedHandler();
        $middleware = new RedirectTrailingSlashMiddleware();

        $result = $middleware->requestReceived($request, $handler);

        self::assertSame($request, $result);
    }

    public function testRedirectResponse(): void
    {
        $request    = new ServerRequest(uri: new Uri(path: '/test/'));
        $request2   = new ServerRequest(uri: new Uri(path: '/test/', query: 'test=foo'));
        $request3   = new ServerRequest(uri: new Uri(path: '/test/', fragment: 'test'));
        $request4   = new ServerRequest(uri: new Uri(path: '/test/', query: 'test=foo', fragment: 'test'));
        $handler    = new RequestReceivedHandler();
        $middleware = new RedirectTrailingSlashMiddleware();

        $result  = $middleware->requestReceived($request, $handler);
        $result2 = $middleware->requestReceived($request2, $handler);
        $result3 = $middleware->requestReceived($request3, $handler);
        $result4 = $middleware->requestReceived($request4, $handler);

        self::assertInstanceOf(RedirectResponseContract::class, $result);
        self::assertInstanceOf(RedirectResponseContract::class, $result2);
        self::assertInstanceOf(RedirectResponseContract::class, $result3);
        self::assertInstanceOf(RedirectResponseContract::class, $result4);
        self::assertSame('/test', $result->getUri()->__toString());
        self::assertSame('/test?test=foo', $result2->getUri()->__toString());
        self::assertSame('/test#test', $result3->getUri()->__toString());
        self::assertSame('/test?test=foo#test', $result4->getUri()->__toString());
    }
}
