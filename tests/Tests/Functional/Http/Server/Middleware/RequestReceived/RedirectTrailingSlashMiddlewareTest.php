<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Functional\Http\Server\Middleware\RequestReceived;

use Valkyrja\Application\Data\HttpConfig;
use Valkyrja\Application\Directory\Directory;
use Valkyrja\Application\Entry\Http;
use Valkyrja\Http\Message\Request\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\RedirectResponseContract;
use Valkyrja\Http\Message\Uri\Uri;
use Valkyrja\Http\Middleware\Handler\Contract\RequestReceivedHandlerContract;
use Valkyrja\Http\Server\Middleware\RequestReceived\RedirectTrailingSlashMiddleware;
use Valkyrja\Tests\Functional\Abstract\TestCase;

/**
 * Resolution of a configured RedirectTrailingSlashMiddleware at request time.
 *
 * The stage handler resolves each configured class-string through the
 * container, so the middleware only runs when the framework publishes it.
 */
final class RedirectTrailingSlashMiddlewareTest extends TestCase
{
    public function testConfiguredMiddlewareRedirects(): void
    {
        Http::directory(Directory::$basePath);

        $application = Http::app(
            new HttpConfig(
                dir: Directory::$basePath,
                debugMode: true,
                requestReceivedMiddleware: [RedirectTrailingSlashMiddleware::class],
            )
        );

        $handler = $application->getContainer()->getSingleton(RequestReceivedHandlerContract::class);

        self::assertInstanceOf(RequestReceivedHandlerContract::class, $handler);

        $response = $handler->requestReceived(new ServerRequest(uri: new Uri(path: '/users/')));

        self::assertInstanceOf(RedirectResponseContract::class, $response);
        self::assertSame('/users', $response->getUri()->__toString());
    }
}
