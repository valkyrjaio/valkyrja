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

namespace Valkyrja\Tests\Unit\Http\Message\Header;

use Valkyrja\Http\Message\Header\Security\HeaderSecurity;
use Valkyrja\Http\Message\Header\Throwable\Exception\InvalidNameException;
use Valkyrja\Http\Message\Header\Throwable\Exception\InvalidValueException;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class HeaderSecurityTest extends TestCase
{
    public function testFilter(): void
    {
        self::assertSame('test', HeaderSecurity::filter('test'));
        self::assertSame('test ', HeaderSecurity::filter('test '));
        self::assertSame('test foo', HeaderSecurity::filter('test foo'));
        self::assertSame('test foo', HeaderSecurity::filter("test\n foo"));
        self::assertSame("test\r\n foo", HeaderSecurity::filter("test\r\n foo"));
        self::assertSame("test\r\n   foo", HeaderSecurity::filter("test\r\n   foo"));
        self::assertSame('test foo', HeaderSecurity::filter("test foo\n"));
    }

    public function testInvalidHeaderValue(): void
    {
        $this->expectException(InvalidValueException::class);

        HeaderSecurity::assertValid("\x0a");
    }

    public function testIsValid(): void
    {
        self::assertTrue(HeaderSecurity::isValid('test'));
        self::assertTrue(HeaderSecurity::isValid('Test'));
        self::assertTrue(HeaderSecurity::isValid('Test-Header'));
        self::assertTrue(HeaderSecurity::isValid('Test_Header'));

        self::assertFalse(HeaderSecurity::isValid("\r"));
        self::assertFalse(HeaderSecurity::isValid("\n"));
        self::assertFalse(HeaderSecurity::isValid("\r\n"));
        self::assertFalse(HeaderSecurity::isValid("\n\r"));
        self::assertTrue(HeaderSecurity::isValid("\r\n "));
        self::assertTrue(HeaderSecurity::isValid("\r\n  "));

        self::assertTrue(HeaderSecurity::isValid("\x09"));
        self::assertFalse(HeaderSecurity::isValid("\x0a"));
        self::assertFalse(HeaderSecurity::isValid("\x0d"));
        self::assertTrue(HeaderSecurity::isValid("\x80"));
        self::assertTrue(HeaderSecurity::isValid("\xFE"));
        self::assertFalse(HeaderSecurity::isValid("\x7F"));
        self::assertTrue(HeaderSecurity::isValid("\x7E"));
    }

    public function testInvalidHeaderName(): void
    {
        $this->expectException(InvalidNameException::class);

        HeaderSecurity::assertValidName(' ');
    }

    public function testIsValidName(): void
    {
        self::assertTrue(HeaderSecurity::isValidName("a-zA-Z0-9'`#$%&*+.^_|~!-"));
        self::assertFalse(HeaderSecurity::isValidName("\x00"));
        self::assertFalse(HeaderSecurity::isValidName(':'));
        self::assertFalse(HeaderSecurity::isValidName("\r\n"));
        self::assertFalse(HeaderSecurity::isValidName("\n"));
        self::assertFalse(HeaderSecurity::isValidName("test\n"));
        self::assertFalse(HeaderSecurity::isValidName(' '));
    }
}
