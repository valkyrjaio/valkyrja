<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Http\Server\Data;

use Valkyrja\Http\Server\Data\Contract\HttpServerConfigContract;
use Valkyrja\Http\Server\Data\HttpServerConfig;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class HttpServerConfigTest extends TestCase
{
    public function testImplementsContract(): void
    {
        self::assertInstanceOf(HttpServerConfigContract::class, new HttpServerConfig());
    }

    public function testDefaults(): void
    {
        self::assertNull(new HttpServerConfig()->responseCacheFilePath);
    }

    public function testCustomValuesAreStored(): void
    {
        $config = new HttpServerConfig(responseCacheFilePath: '/tmp/response-cache');

        self::assertSame('/tmp/response-cache', $config->responseCacheFilePath);
    }
}
