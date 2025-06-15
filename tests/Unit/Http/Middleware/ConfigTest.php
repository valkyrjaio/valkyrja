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

namespace Valkyrja\Tests\Unit\Http\Middleware;

use Valkyrja\Http\Middleware\Config;
use Valkyrja\Tests\Classes\Http\Middleware\Env\EmptyEnvClass;
use Valkyrja\Tests\Classes\Http\Middleware\Env\EnvClass;
use Valkyrja\Tests\Unit\TestCase;

class ConfigTest extends TestCase
{
    public function testDefaults(): void
    {
        $dataConfig = new Config();

        self::assertEmpty($dataConfig->requestReceived);
        self::assertEmpty($dataConfig->routeDispatched);
        self::assertEmpty($dataConfig->throwableCaught);
        self::assertEmpty($dataConfig->routeMatched);
        self::assertEmpty($dataConfig->routeNotMatched);
        self::assertEmpty($dataConfig->sendingResponse);
        self::assertEmpty($dataConfig->terminated);
    }

    public function testFromEnv(): void
    {
        $dataConfig = Config::fromEnv(EnvClass::class);

        self::assertNotEmpty($dataConfig->requestReceived);
        self::assertNotEmpty($dataConfig->routeDispatched);
        self::assertNotEmpty($dataConfig->throwableCaught);
        self::assertNotEmpty($dataConfig->routeMatched);
        self::assertNotEmpty($dataConfig->routeNotMatched);
        self::assertNotEmpty($dataConfig->sendingResponse);
        self::assertNotEmpty($dataConfig->terminated);

        self::assertSame(EnvClass::HTTP_MIDDLEWARE_REQUEST_RECEIVED, $dataConfig->requestReceived);
        self::assertSame(EnvClass::HTTP_MIDDLEWARE_ROUTE_DISPATCHED, $dataConfig->routeDispatched);
        self::assertSame(EnvClass::HTTP_MIDDLEWARE_THROWABLE_CAUGHT, $dataConfig->throwableCaught);
        self::assertSame(EnvClass::HTTP_MIDDLEWARE_ROUTE_MATCHED, $dataConfig->routeMatched);
        self::assertSame(EnvClass::HTTP_MIDDLEWARE_ROUTE_NOT_MATCHED, $dataConfig->routeNotMatched);
        self::assertSame(EnvClass::HTTP_MIDDLEWARE_SENDING_RESPONSE, $dataConfig->sendingResponse);
        self::assertSame(EnvClass::HTTP_MIDDLEWARE_TERMINATED, $dataConfig->terminated);
    }

    public function testFromEmptyEnv(): void
    {
        $dataConfig = Config::fromEnv(EmptyEnvClass::class);

        self::assertEmpty($dataConfig->requestReceived);
        self::assertEmpty($dataConfig->routeDispatched);
        self::assertEmpty($dataConfig->throwableCaught);
        self::assertEmpty($dataConfig->routeMatched);
        self::assertEmpty($dataConfig->routeNotMatched);
        self::assertEmpty($dataConfig->sendingResponse);
        self::assertEmpty($dataConfig->terminated);
    }
}
