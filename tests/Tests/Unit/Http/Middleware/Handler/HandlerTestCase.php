<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Http\Middleware\Handler;

use Valkyrja\Container\Manager\Container;
use Valkyrja\Http\Message\Request\ServerRequest;
use Valkyrja\Http\Message\Response\Response;
use Valkyrja\Http\Routing\Data\Route;
use Valkyrja\Tests\Fixtures\Http\Middleware\AllMiddlewareFixture;
use Valkyrja\Tests\Fixtures\Http\Middleware\RequestReceivedMiddlewareChangedFixture;
use Valkyrja\Tests\Fixtures\Http\Middleware\RequestReceivedMiddlewareFixture;
use Valkyrja\Tests\Fixtures\Http\Middleware\ResponseSentMiddlewareChangedFixture;
use Valkyrja\Tests\Fixtures\Http\Middleware\ResponseSentMiddlewareFixture;
use Valkyrja\Tests\Fixtures\Http\Middleware\RouteDispatchedMiddlewareChangedFixture;
use Valkyrja\Tests\Fixtures\Http\Middleware\RouteDispatchedMiddlewareFixture;
use Valkyrja\Tests\Fixtures\Http\Middleware\RouteMatchedMiddlewareChangedFixture;
use Valkyrja\Tests\Fixtures\Http\Middleware\RouteMatchedMiddlewareFixture;
use Valkyrja\Tests\Fixtures\Http\Middleware\RouteNotMatchedMiddlewareChangedFixture;
use Valkyrja\Tests\Fixtures\Http\Middleware\RouteNotMatchedMiddlewareFixture;
use Valkyrja\Tests\Fixtures\Http\Middleware\SendingResponseMiddlewareChangedFixture;
use Valkyrja\Tests\Fixtures\Http\Middleware\SendingResponseMiddlewareFixture;
use Valkyrja\Tests\Fixtures\Http\Middleware\ThrowableCaughtMiddlewareChangedFixture;
use Valkyrja\Tests\Fixtures\Http\Middleware\ThrowableCaughtMiddlewareFixture;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * The Handler test case.
 */
abstract class HandlerTestCase extends TestCase
{
    protected Container $container;

    protected ServerRequest $request;

    protected Response $response;

    protected Route $route;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->container = new Container();

        // Every middleware the handler tests schedule is bound, the same way an application binds its own.
        $this->container->bindSingleton(AllMiddlewareFixture::class, static fn (): AllMiddlewareFixture => new AllMiddlewareFixture());
        $this->container->bindSingleton(RequestReceivedMiddlewareChangedFixture::class, static fn (): RequestReceivedMiddlewareChangedFixture => new RequestReceivedMiddlewareChangedFixture());
        $this->container->bindSingleton(RequestReceivedMiddlewareFixture::class, static fn (): RequestReceivedMiddlewareFixture => new RequestReceivedMiddlewareFixture());
        $this->container->bindSingleton(ResponseSentMiddlewareChangedFixture::class, static fn (): ResponseSentMiddlewareChangedFixture => new ResponseSentMiddlewareChangedFixture());
        $this->container->bindSingleton(ResponseSentMiddlewareFixture::class, static fn (): ResponseSentMiddlewareFixture => new ResponseSentMiddlewareFixture());
        $this->container->bindSingleton(RouteDispatchedMiddlewareChangedFixture::class, static fn (): RouteDispatchedMiddlewareChangedFixture => new RouteDispatchedMiddlewareChangedFixture());
        $this->container->bindSingleton(RouteDispatchedMiddlewareFixture::class, static fn (): RouteDispatchedMiddlewareFixture => new RouteDispatchedMiddlewareFixture());
        $this->container->bindSingleton(RouteMatchedMiddlewareChangedFixture::class, static fn (): RouteMatchedMiddlewareChangedFixture => new RouteMatchedMiddlewareChangedFixture());
        $this->container->bindSingleton(RouteMatchedMiddlewareFixture::class, static fn (): RouteMatchedMiddlewareFixture => new RouteMatchedMiddlewareFixture());
        $this->container->bindSingleton(RouteNotMatchedMiddlewareChangedFixture::class, static fn (): RouteNotMatchedMiddlewareChangedFixture => new RouteNotMatchedMiddlewareChangedFixture());
        $this->container->bindSingleton(RouteNotMatchedMiddlewareFixture::class, static fn (): RouteNotMatchedMiddlewareFixture => new RouteNotMatchedMiddlewareFixture());
        $this->container->bindSingleton(SendingResponseMiddlewareChangedFixture::class, static fn (): SendingResponseMiddlewareChangedFixture => new SendingResponseMiddlewareChangedFixture());
        $this->container->bindSingleton(SendingResponseMiddlewareFixture::class, static fn (): SendingResponseMiddlewareFixture => new SendingResponseMiddlewareFixture());
        $this->container->bindSingleton(ThrowableCaughtMiddlewareChangedFixture::class, static fn (): ThrowableCaughtMiddlewareChangedFixture => new ThrowableCaughtMiddlewareChangedFixture());
        $this->container->bindSingleton(ThrowableCaughtMiddlewareFixture::class, static fn (): ThrowableCaughtMiddlewareFixture => new ThrowableCaughtMiddlewareFixture());

        $this->request  = new ServerRequest();
        $this->response = new Response();
        $this->route    = new Route(
            '/',
            'name',
            handler: static fn (): null => null,
        );
    }
}
