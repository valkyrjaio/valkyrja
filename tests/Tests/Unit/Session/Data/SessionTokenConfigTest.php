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

use Valkyrja\Session\Data\Contract\SessionTokenConfigContract;
use Valkyrja\Session\Data\SessionTokenConfig;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class SessionTokenConfigTest extends TestCase
{
    public function testImplementsContract(): void
    {
        self::assertInstanceOf(SessionTokenConfigContract::class, new SessionTokenConfig());
    }

    public function testDefaults(): void
    {
        $config = new SessionTokenConfig();

        self::assertNull($config->tokenOptionName);
        self::assertNull($config->tokenHeaderName);
    }

    public function testCustomValuesAreStored(): void
    {
        $config = new SessionTokenConfig(tokenOptionName: 'test-option', tokenHeaderName: 'test-header');

        self::assertSame('test-option', $config->tokenOptionName);
        self::assertSame('test-header', $config->tokenHeaderName);
    }
}
