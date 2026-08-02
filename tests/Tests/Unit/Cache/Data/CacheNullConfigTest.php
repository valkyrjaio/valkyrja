<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Cache\Data;

use Valkyrja\Cache\Data\CacheNullConfig;
use Valkyrja\Cache\Data\Contract\CacheNullConfigContract;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class CacheNullConfigTest extends TestCase
{
    public function testImplementsContract(): void
    {
        self::assertInstanceOf(CacheNullConfigContract::class, new CacheNullConfig());
    }

    public function testDefaults(): void
    {
        self::assertSame('', new CacheNullConfig()->nullPrefix);
    }

    public function testCustomValuesAreStored(): void
    {
        self::assertSame('test:', new CacheNullConfig(nullPrefix: 'test:')->nullPrefix);
    }
}
