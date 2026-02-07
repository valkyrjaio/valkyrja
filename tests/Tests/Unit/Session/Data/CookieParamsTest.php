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
use Valkyrja\Session\Data\CookieParams;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class CookieParamsTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $cookieParams = new CookieParams();

        self::assertSame('/', $cookieParams->path);
        self::assertNull($cookieParams->domain);
        self::assertSame(0, $cookieParams->lifetime);
        self::assertFalse($cookieParams->secure);
        self::assertFalse($cookieParams->httpOnly);
        self::assertSame(SameSite::NONE, $cookieParams->sameSite);
    }

    public function testCustomValues(): void
    {
        $cookieParams = new CookieParams(
            path: '/custom',
            domain: 'example.com',
            lifetime: 3600,
            secure: true,
            httpOnly: true,
            sameSite: SameSite::STRICT,
        );

        self::assertSame('/custom', $cookieParams->path);
        self::assertSame('example.com', $cookieParams->domain);
        self::assertSame(3600, $cookieParams->lifetime);
        self::assertTrue($cookieParams->secure);
        self::assertTrue($cookieParams->httpOnly);
        self::assertSame(SameSite::STRICT, $cookieParams->sameSite);
    }

    public function testPartialCustomValues(): void
    {
        $cookieParams = new CookieParams(
            domain: 'test.com',
            secure: true,
        );

        self::assertSame('/', $cookieParams->path);
        self::assertSame('test.com', $cookieParams->domain);
        self::assertSame(0, $cookieParams->lifetime);
        self::assertTrue($cookieParams->secure);
        self::assertFalse($cookieParams->httpOnly);
        self::assertSame(SameSite::NONE, $cookieParams->sameSite);
    }

    public function testSameSiteLax(): void
    {
        $cookieParams = new CookieParams(
            sameSite: SameSite::LAX,
        );

        self::assertSame(SameSite::LAX, $cookieParams->sameSite);
    }
}
