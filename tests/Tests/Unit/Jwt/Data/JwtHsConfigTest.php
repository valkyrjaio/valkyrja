<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Jwt\Data;

use Valkyrja\Jwt\Data\Contract\JwtHsConfigContract;
use Valkyrja\Jwt\Data\JwtHsConfig;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class JwtHsConfigTest extends TestCase
{
    public function testImplementsContract(): void
    {
        self::assertInstanceOf(JwtHsConfigContract::class, new JwtHsConfig());
    }

    public function testDefaults(): void
    {
        self::assertSame('key', new JwtHsConfig()->hsKey);
    }

    public function testCustomValuesAreStored(): void
    {
        self::assertSame('test-key', new JwtHsConfig(hsKey: 'test-key')->hsKey);
    }
}
