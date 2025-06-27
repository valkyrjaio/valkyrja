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
        $config = new Config();

        self::assertEmpty($config->requestReceived);
        self::assertEmpty($config->routeDispatched);
        self::assertEmpty($config->throwableCaught);
        self::assertEmpty($config->routeMatched);
        self::assertEmpty($config->routeNotMatched);
        self::assertEmpty($config->sendingResponse);
        self::assertEmpty($config->terminated);
    }

    public function testFromEnv(): void
    {
        $config = Config::fromEnv(EnvClass::class);

        self::assertNotEmpty($config->requestReceived);
        self::assertNotEmpty($config->routeDispatched);
        self::assertNotEmpty($config->throwableCaught);
        self::assertNotEmpty($config->routeMatched);
        self::assertNotEmpty($config->routeNotMatched);
        self::assertNotEmpty($config->sendingResponse);
        self::assertNotEmpty($config->terminated);

        self::assertSame(EnvClass::HTTP_MIDDLEWARE_REQUEST_RECEIVED, $config->requestReceived);
        self::assertSame(EnvClass::HTTP_MIDDLEWARE_ROUTE_DISPATCHED, $config->routeDispatched);
        self::assertSame(EnvClass::HTTP_MIDDLEWARE_THROWABLE_CAUGHT, $config->throwableCaught);
        self::assertSame(EnvClass::HTTP_MIDDLEWARE_ROUTE_MATCHED, $config->routeMatched);
        self::assertSame(EnvClass::HTTP_MIDDLEWARE_ROUTE_NOT_MATCHED, $config->routeNotMatched);
        self::assertSame(EnvClass::HTTP_MIDDLEWARE_SENDING_RESPONSE, $config->sendingResponse);
        self::assertSame(EnvClass::HTTP_MIDDLEWARE_TERMINATED, $config->terminated);
    }

    public function testFromEmptyEnv(): void
    {
        $config = Config::fromEnv(EmptyEnvClass::class);

        self::assertEmpty($config->requestReceived);
        self::assertEmpty($config->routeDispatched);
        self::assertEmpty($config->throwableCaught);
        self::assertEmpty($config->routeMatched);
        self::assertEmpty($config->routeNotMatched);
        self::assertEmpty($config->sendingResponse);
        self::assertEmpty($config->terminated);
    }
}
