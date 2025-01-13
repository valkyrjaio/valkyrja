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

use Valkyrja\Http\Middleware\DataConfig;
use Valkyrja\Tests\Classes\Http\Middleware\Env\EmptyEnv;
use Valkyrja\Tests\Classes\Http\Middleware\Env\Env;
use Valkyrja\Tests\Unit\TestCase;

class DataConfigTest extends TestCase
{
    public function testDefaults(): void
    {
        $dataConfig = new DataConfig();

        self::assertEmpty($dataConfig->before);
        self::assertEmpty($dataConfig->dispatched);
        self::assertEmpty($dataConfig->exception);
        self::assertEmpty($dataConfig->routeMatched);
        self::assertEmpty($dataConfig->routeNotMatched);
        self::assertEmpty($dataConfig->sending);
        self::assertEmpty($dataConfig->terminated);
    }

    public function testFromEnv(): void
    {
        $dataConfig = DataConfig::fromEnv(Env::class);

        self::assertNotEmpty($dataConfig->before);
        self::assertNotEmpty($dataConfig->dispatched);
        self::assertNotEmpty($dataConfig->exception);
        self::assertNotEmpty($dataConfig->routeMatched);
        self::assertNotEmpty($dataConfig->routeNotMatched);
        self::assertNotEmpty($dataConfig->sending);
        self::assertNotEmpty($dataConfig->terminated);

        self::assertSame(Env::HTTP_MIDDLEWARE_BEFORE, $dataConfig->before);
        self::assertSame(Env::HTTP_MIDDLEWARE_DISPATCHED, $dataConfig->dispatched);
        self::assertSame(Env::HTTP_MIDDLEWARE_EXCEPTION, $dataConfig->exception);
        self::assertSame(Env::HTTP_MIDDLEWARE_ROUTE_MATCHED, $dataConfig->routeMatched);
        self::assertSame(Env::HTTP_MIDDLEWARE_ROUTE_NOT_MATCHED, $dataConfig->routeNotMatched);
        self::assertSame(Env::HTTP_MIDDLEWARE_SENDING, $dataConfig->sending);
        self::assertSame(Env::HTTP_MIDDLEWARE_TERMINATED, $dataConfig->terminated);
    }

    public function testFromEmptyEnv(): void
    {
        $dataConfig = DataConfig::fromEnv(EmptyEnv::class);

        self::assertEmpty($dataConfig->before);
        self::assertEmpty($dataConfig->dispatched);
        self::assertEmpty($dataConfig->exception);
        self::assertEmpty($dataConfig->routeMatched);
        self::assertEmpty($dataConfig->routeNotMatched);
        self::assertEmpty($dataConfig->sending);
        self::assertEmpty($dataConfig->terminated);
    }
}
