<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Session\Data;

use Valkyrja\Session\Data\Contract\SessionJwtConfigContract;
use Valkyrja\Session\Data\SessionJwtConfig;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class SessionJwtConfigTest extends TestCase
{
    public function testImplementsContract(): void
    {
        self::assertInstanceOf(SessionJwtConfigContract::class, new SessionJwtConfig());
    }

    public function testDefaults(): void
    {
        $config = new SessionJwtConfig();

        self::assertNull($config->jwtOptionName);
        self::assertNull($config->jwtHeaderName);
    }

    public function testCustomValuesAreStored(): void
    {
        $config = new SessionJwtConfig(jwtOptionName: 'test-option', jwtHeaderName: 'test-header');

        self::assertSame('test-option', $config->jwtOptionName);
        self::assertSame('test-header', $config->jwtHeaderName);
    }
}
