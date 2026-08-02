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

use Valkyrja\Jwt\Data\Contract\JwtRsConfigContract;
use Valkyrja\Jwt\Data\JwtRsConfig;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class JwtRsConfigTest extends TestCase
{
    public function testImplementsContract(): void
    {
        self::assertInstanceOf(JwtRsConfigContract::class, new JwtRsConfig());
    }

    public function testDefaults(): void
    {
        $config = new JwtRsConfig();

        self::assertSame('private-key', $config->rsPrivateKey);
        self::assertSame('public-key', $config->rsPublicKey);
    }

    public function testCustomValuesAreStored(): void
    {
        $config = new JwtRsConfig(rsPrivateKey: 'test-private', rsPublicKey: 'test-public');

        self::assertSame('test-private', $config->rsPrivateKey);
        self::assertSame('test-public', $config->rsPublicKey);
    }
}
