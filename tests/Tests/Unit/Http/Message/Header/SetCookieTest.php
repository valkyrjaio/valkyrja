<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Http\Message\Header;

use Valkyrja\Http\Message\Constant\HeaderName;
use Valkyrja\Http\Message\Header\Contract\HeaderContract;
use Valkyrja\Http\Message\Header\Header;
use Valkyrja\Http\Message\Header\SetCookie;
use Valkyrja\Http\Message\Header\Value\Cookie;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class SetCookieTest extends TestCase
{
    public function testImplementsHeaderContract(): void
    {
        self::assertInstanceOf(HeaderContract::class, new SetCookie(new Cookie('session')));
    }

    public function testExtendsHeader(): void
    {
        self::assertInstanceOf(Header::class, new SetCookie(new Cookie('session')));
    }

    public function testHeaderNameIsSetCookie(): void
    {
        $header = new SetCookie(new Cookie('session', 'abc'));

        self::assertSame(HeaderName::SET_COOKIE, $header->getName());
        self::assertSame('set-cookie', $header->getNormalizedName());
    }

    public function testWithSingleCookie(): void
    {
        $cookie = new Cookie('session', 'abc');
        $header = new SetCookie($cookie);

        self::assertCount(1, $header->getValues());
        self::assertSame((string) $cookie, $header->getHeaderLine());
        self::assertSame(HeaderName::SET_COOKIE . ': ' . (string) $cookie, $header->__toString());
    }

    public function testWithMultipleCookies(): void
    {
        $first  = new Cookie('a', '1');
        $second = new Cookie('b', '2');
        $header = new SetCookie($first, $second);

        self::assertCount(2, $header->getValues());
        self::assertSame((string) $first . ', ' . (string) $second, $header->getHeaderLine());
    }

    public function testEmpty(): void
    {
        $header = new SetCookie();

        self::assertCount(0, $header->getValues());
        self::assertSame('', $header->getHeaderLine());
    }
}
