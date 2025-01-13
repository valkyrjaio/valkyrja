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

namespace Unit\Http\Message\Header\Value;

use Valkyrja\Http\Message\Enum\SameSite;
use Valkyrja\Http\Message\Header\Value\Cookie;
use Valkyrja\Support\Time\Time;
use Valkyrja\Tests\Unit\TestCase;

class CookieTest extends TestCase
{
    protected const COOKIE_NAME = 'test';

    public function testDefaults(): void
    {
        Time::freeze(1734553175);

        $cookie = new Cookie(name: self::COOKIE_NAME);

        self::assertSame(self::COOKIE_NAME, $cookie->getName());
        self::assertNull($cookie->getValue());
        self::assertSame(0, $cookie->getExpire());
        self::assertSame('/', $cookie->getPath());
        self::assertNull($cookie->getDomain());
        self::assertFalse($cookie->isSecure());
        self::assertTrue($cookie->isHttpOnly());
        self::assertFalse($cookie->isRaw());
        self::assertNull($cookie->getSameSite());

        self::assertSame(
            'test=delete; expires=Tuesday, 19-Dec-2023 20:19:34 GMT; max-age=-31536001; path=/; httponly',
            $cookie->__toString()
        );

        Time::unfreeze();
    }

    public function testDelete(): void
    {
        Time::freeze(1734553175);

        $cookie  = new Cookie(name: self::COOKIE_NAME, value: 'foo');
        $cookie2 = $cookie->delete();

        self::assertNotSame($cookie, $cookie2);

        self::assertSame('test=foo; path=/; httponly', $cookie->__toString());

        self::assertSame(
            'test=delete; expires=Tuesday, 19-Dec-2023 20:19:34 GMT; max-age=-31536001; path=/; httponly',
            $cookie2->__toString()
        );

        Time::unfreeze();
    }

    public function testName(): void
    {
        $cookie  = new Cookie(name: self::COOKIE_NAME, value: 'foo');
        $cookie2 = $cookie->withName($name2 = 'test2');

        self::assertNotSame($cookie, $cookie2);

        self::assertSame(self::COOKIE_NAME, $cookie->getName());
        self::assertSame('test=foo; path=/; httponly', $cookie->__toString());

        self::assertSame($name2, $cookie2->getName());
        self::assertSame('test2=foo; path=/; httponly', $cookie2->__toString());
    }

    public function testValue(): void
    {
        $cookie  = new Cookie(name: self::COOKIE_NAME, value: $value = 'foo');
        $cookie2 = $cookie->withValue($value2 = 'foo2');

        self::assertNotSame($cookie, $cookie2);

        self::assertSame($value, $cookie->getValue());
        self::assertSame('test=foo; path=/; httponly', $cookie->__toString());

        self::assertSame($value2, $cookie2->getValue());
        self::assertSame('test=foo2; path=/; httponly', $cookie2->__toString());
    }

    public function testExpire(): void
    {
        Time::freeze(1734553175);

        $cookie  = new Cookie(name: self::COOKIE_NAME, value: 'test', expire: $expire = 10);
        $cookie2 = $cookie->withExpire($expire2 = 20);

        self::assertNotSame($cookie, $cookie2);

        self::assertSame($expire, $cookie->getExpire());
        self::assertSame(
            'test=test; expires=Thursday, 01-Jan-1970 00:00:10 GMT; max-age=-1734553165; path=/; httponly',
            $cookie->__toString()
        );

        self::assertSame($expire2, $cookie2->getExpire());
        self::assertSame(
            'test=test; expires=Thursday, 01-Jan-1970 00:00:20 GMT; max-age=-1734553155; path=/; httponly',
            $cookie2->__toString()
        );

        Time::unfreeze();
    }

    public function testPath(): void
    {
        $cookie  = new Cookie(name: self::COOKIE_NAME, value: 'test', path: $path = '/path');
        $cookie2 = $cookie->withPath($path2 = '/path2');

        self::assertNotSame($cookie, $cookie2);

        self::assertSame($path, $cookie->getPath());
        self::assertSame('test=test; path=/path; httponly', $cookie->__toString());

        self::assertSame($path2, $cookie2->getPath());
        self::assertSame('test=test; path=/path2; httponly', $cookie2->__toString());
    }

    public function testDomain(): void
    {
        $cookie  = new Cookie(name: self::COOKIE_NAME, value: 'test', domain: $domain = 'www.example.com');
        $cookie2 = $cookie->withDomain($domain2 = 'www.example2.com');

        self::assertNotSame($cookie, $cookie2);

        self::assertSame($domain, $cookie->getDomain());
        self::assertSame('test=test; path=/; domain=www.example.com; httponly', $cookie->__toString());

        self::assertSame($domain2, $cookie2->getDomain());
        self::assertSame('test=test; path=/; domain=www.example2.com; httponly', $cookie2->__toString());
    }

    public function testSecure(): void
    {
        $cookie  = new Cookie(name: self::COOKIE_NAME, value: 'test', secure: true);
        $cookie2 = $cookie->withSecure(false);

        self::assertNotSame($cookie, $cookie2);

        self::assertTrue($cookie->isSecure());
        self::assertSame('test=test; path=/; secure; httponly', $cookie->__toString());

        self::assertFalse($cookie2->isSecure());
        self::assertSame('test=test; path=/; httponly', $cookie2->__toString());
    }

    public function testHttpOnly(): void
    {
        $cookie  = new Cookie(name: self::COOKIE_NAME, value: 'test', httpOnly: false);
        $cookie2 = $cookie->withHttpOnly(true);

        self::assertNotSame($cookie, $cookie2);

        self::assertFalse($cookie->isHttpOnly());
        self::assertSame('test=test; path=/', $cookie->__toString());

        self::assertTrue($cookie2->isHttpOnly());
        self::assertSame('test=test; path=/; httponly', $cookie2->__toString());
    }

    public function testRaw(): void
    {
        $cookie  = new Cookie(name: self::COOKIE_NAME, value: 'test', raw: true);
        $cookie2 = $cookie->withRaw(false);

        self::assertNotSame($cookie, $cookie2);

        self::assertTrue($cookie->isRaw());
        self::assertSame('test=test; path=/; httponly', $cookie->__toString());

        self::assertFalse($cookie2->isRaw());
        self::assertSame('test=test; path=/; httponly', $cookie2->__toString());
    }

    public function testSameSite(): void
    {
        $cookie  = new Cookie(name: self::COOKIE_NAME, value: 'test', sameSite: SameSite::STRICT);
        $cookie2 = $cookie->withSameSite(SameSite::LAX);

        self::assertNotSame($cookie, $cookie2);

        self::assertSame(SameSite::STRICT, $cookie->getSameSite());
        self::assertSame('test=test; path=/; httponly; samesite=strict', $cookie->__toString());

        self::assertSame(SameSite::LAX, $cookie2->getSameSite());
        self::assertSame('test=test; path=/; httponly; samesite=lax', $cookie2->__toString());
    }
}
