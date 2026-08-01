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

use Valkyrja\Http\Message\Enum\SameSite;
use Valkyrja\Session\Data\Contract\SessionCookieConfigContract;
use Valkyrja\Session\Data\SessionCookieConfig;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class SessionCookieConfigTest extends TestCase
{
    public function testImplementsContract(): void
    {
        self::assertInstanceOf(SessionCookieConfigContract::class, new SessionCookieConfig());
    }

    public function testDefaults(): void
    {
        $config = new SessionCookieConfig();

        self::assertSame('/', $config->cookiePath);
        self::assertNull($config->cookieDomain);
        self::assertSame(0, $config->cookieLifetime);
        self::assertFalse($config->cookieSecure);
        self::assertFalse($config->cookieHttpOnly);
        self::assertSame(SameSite::NONE, $config->cookieSameSite);
    }

    public function testCustomValuesAreStored(): void
    {
        $config = new SessionCookieConfig(
            cookiePath: '/test',
            cookieDomain: 'test.dev',
            cookieLifetime: 3600,
            cookieSecure: true,
            cookieHttpOnly: true,
            cookieSameSite: SameSite::STRICT,
        );

        self::assertSame('/test', $config->cookiePath);
        self::assertSame('test.dev', $config->cookieDomain);
        self::assertSame(3600, $config->cookieLifetime);
        self::assertTrue($config->cookieSecure);
        self::assertTrue($config->cookieHttpOnly);
        self::assertSame(SameSite::STRICT, $config->cookieSameSite);
    }
}
