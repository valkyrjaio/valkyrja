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

namespace Valkyrja\Tests\Unit\Http\Middleware\Cache;

use Valkyrja\Http\Message\Constant\HeaderName;
use Valkyrja\Http\Message\Request\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Message\Response\EmptyResponse;
use Valkyrja\Http\Middleware\Cache\NoCacheResponseMiddleware;
use Valkyrja\Http\Middleware\Handler\SendingResponseHandler;
use Valkyrja\Tests\Unit\TestCase;

class NoCacheMiddlewareTest extends TestCase
{
    public function testThroughHandler(): void
    {
        $handler = new SendingResponseHandler();
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
        self::assertTrue($response->hasHeader(HeaderName::EXPIRES));
        self::assertTrue($response->hasHeader(HeaderName::CACHE_CONTROL));
        self::assertTrue($response->hasHeader(HeaderName::PRAGMA));
        self::assertSame(
            $response->getHeader(HeaderName::EXPIRES),
            ['Sun, 01 Jan 2014 00:00:00 GMT']
        );
        self::assertSame(
            $response->getHeader(HeaderName::CACHE_CONTROL),
            ['no-store, no-cache, must-revalidate', 'post-check=0, pre-check=0']
        );
        self::assertSame(
            $response->getHeader(HeaderName::PRAGMA),
            ['no-cache']
        );
    }
}
