<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Http\Message\Header\Factory;

use Valkyrja\Http\Message\Header\Factory\CookieFactory;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class CookieFactoryTest extends TestCase
{
    protected const string SEPARATOR = '; ';

    public function testParseCookieHeader(): void
    {
        $single = [
            'test' => 'foo',
        ];
        $multi  = [
            'test'  => 'foo',
            'test2' => 'bar',
        ];

        $singleString = CookieFactory::convertCookieArrayToHeaderString($single);
        $multiString  = CookieFactory::convertCookieArrayToHeaderString($multi);

        self::assertSame($single, CookieFactory::parseCookieHeader($singleString));
        self::assertSame($multi, CookieFactory::parseCookieHeader($multiString));
    }

    public function testConvertCookieArrayToHeaderString(): void
    {
        $single = [
            'test' => 'foo',
        ];
        $multi  = [
            'test'  => 'foo',
            'test2' => 'bar',
        ];

        $singleString = CookieFactory::convertCookieArrayToHeaderString($single);
        $multiString  = CookieFactory::convertCookieArrayToHeaderString($multi);

        self::assertSame('test=foo', $singleString);
        self::assertSame('test=foo; test2=bar', $multiString);
    }
}
