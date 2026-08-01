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
