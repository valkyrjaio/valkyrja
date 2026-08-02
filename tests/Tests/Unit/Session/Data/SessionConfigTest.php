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

use Valkyrja\Session\Data\Contract\SessionConfigContract;
use Valkyrja\Session\Data\SessionConfig;
use Valkyrja\Session\Manager\NullSession;
use Valkyrja\Session\Manager\PhpSession;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class SessionConfigTest extends TestCase
{
    public function testImplementsContract(): void
    {
        self::assertInstanceOf(SessionConfigContract::class, new SessionConfig());
    }

    public function testDefaults(): void
    {
        $config = new SessionConfig();

        self::assertSame(PhpSession::class, $config->defaultSession);
        self::assertNull($config->sessionId);
        self::assertNull($config->sessionName);
    }

    public function testCustomValuesAreStored(): void
    {
        $config = new SessionConfig(
            defaultSession: NullSession::class,
            sessionId: 'test-id',
            sessionName: 'test-name',
        );

        self::assertSame(NullSession::class, $config->defaultSession);
        self::assertSame('test-id', $config->sessionId);
        self::assertSame('test-name', $config->sessionName);
    }
}
