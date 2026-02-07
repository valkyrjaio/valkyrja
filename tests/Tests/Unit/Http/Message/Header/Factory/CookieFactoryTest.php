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
