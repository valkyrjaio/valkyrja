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

use Valkyrja\Jwt\Data\Contract\JwtEdDsaConfigContract;
use Valkyrja\Jwt\Data\JwtEdDsaConfig;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class JwtEdDsaConfigTest extends TestCase
{
    public function testImplementsContract(): void
    {
        self::assertInstanceOf(JwtEdDsaConfigContract::class, new JwtEdDsaConfig());
    }

    public function testDefaults(): void
    {
        $config = new JwtEdDsaConfig();

        self::assertSame('private-key', $config->edDsaPrivateKey);
        self::assertSame('public-key', $config->edDsaPublicKey);
    }

    public function testCustomValuesAreStored(): void
    {
        $config = new JwtEdDsaConfig(edDsaPrivateKey: 'test-private', edDsaPublicKey: 'test-public');

        self::assertSame('test-private', $config->edDsaPrivateKey);
        self::assertSame('test-public', $config->edDsaPublicKey);
    }
}
