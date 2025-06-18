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

namespace Valkyrja\Tests\Unit\Http\Message\Uri\Psr;

use Valkyrja\Http\Message\Uri\Enum\Scheme;
use Valkyrja\Http\Message\Uri\Psr\Uri as Psr;
use Valkyrja\Http\Message\Uri\Uri;
use Valkyrja\Tests\Unit\TestCase;

class UriTest extends TestCase
{
    protected const string URI_ALL_PARTS = 'https://username:password@example.com:9090/path?arg=value#anchor';

    public function testGetScheme(): void
    {
        $uri = Uri::fromString(self::URI_ALL_PARTS);

        $psr = new Psr($uri);

        self::assertSame($uri->getScheme()->value, $psr->getScheme());
    }

    public function testGetAuthority(): void
    {
        $uri = Uri::fromString(self::URI_ALL_PARTS);

        $psr = new Psr($uri);

        self::assertSame($uri->getAuthority(), $psr->getAuthority());
    }

    public function testGetUserInfo(): void
    {
        $uri = Uri::fromString(self::URI_ALL_PARTS);

        $psr = new Psr($uri);

        self::assertSame($uri->getUserInfo(), $psr->getUserInfo());
    }

    public function testGetHost(): void
    {
        $uri = Uri::fromString(self::URI_ALL_PARTS);

        $psr = new Psr($uri);

        self::assertSame($uri->getHost(), $psr->getHost());
    }

    public function testGetPort(): void
    {
        $uri = Uri::fromString(self::URI_ALL_PARTS);

        $psr = new Psr($uri);

        self::assertSame($uri->getPort(), $psr->getPort());
    }

    public function testGetPath(): void
    {
        $uri = Uri::fromString(self::URI_ALL_PARTS);

        $psr = new Psr($uri);

        self::assertSame($uri->getPath(), $psr->getPath());
    }

    public function testGetQuery(): void
    {
        $uri = Uri::fromString(self::URI_ALL_PARTS);

        $psr = new Psr($uri);

        self::assertSame($uri->getQuery(), $psr->getQuery());
    }

    public function testGetFragment(): void
    {
        $uri = Uri::fromString(self::URI_ALL_PARTS);

        $psr = new Psr($uri);

        self::assertSame($uri->getFragment(), $psr->getFragment());
    }

    public function testWithScheme(): void
    {
        $value = Scheme::HTTP->value;

        $uri = Uri::fromString(self::URI_ALL_PARTS);

        $psr = new Psr($uri);
        $psr = $psr->withScheme($value);

        self::assertSame($value, $psr->getScheme());
        self::assertNotSame($uri->getScheme(), $psr->getScheme());
    }

    public function testWithUserInfo(): void
    {
        $value = 'username2:password2';

        $uri = Uri::fromString(self::URI_ALL_PARTS);

        $psr = new Psr($uri);
        $psr = $psr->withUserInfo($value);

        self::assertSame($value, $psr->getUserInfo());
        self::assertNotSame($uri->getUserInfo(), $psr->getUserInfo());
    }

    public function testWithHost(): void
    {
        $value = 'test.com';

        $uri = Uri::fromString(self::URI_ALL_PARTS);

        $psr = new Psr($uri);
        $psr = $psr->withHost($value);

        self::assertSame($value, $psr->getHost());
        self::assertNotSame($uri->getHost(), $psr->getHost());
    }

    public function testWithPort(): void
    {
        $value = 8080;

        $uri = Uri::fromString(self::URI_ALL_PARTS);

        $psr = new Psr($uri);
        $psr = $psr->withPort($value);

        self::assertSame($value, $psr->getPort());
        self::assertNotSame($uri->getPort(), $psr->getPort());
    }

    public function testWithPath(): void
    {
        $value = '/example';

        $uri = Uri::fromString(self::URI_ALL_PARTS);

        $psr = new Psr($uri);
        $psr = $psr->withPath($value);

        self::assertSame($value, $psr->getPath());
        self::assertNotSame($uri->getPath(), $psr->getPath());
    }

    public function testWithQuery(): void
    {
        $value = 'query=value';

        $uri = Uri::fromString(self::URI_ALL_PARTS);

        $psr = new Psr($uri);
        $psr = $psr->withQuery($value);

        self::assertSame($value, $psr->getQuery());
        self::assertNotSame($uri->getQuery(), $psr->getQuery());
    }

    public function testWithFragment(): void
    {
        $value = 'fragment';

        $uri = Uri::fromString(self::URI_ALL_PARTS);

        $psr = new Psr($uri);
        $psr = $psr->withFragment($value);

        self::assertSame($value, $psr->getFragment());
        self::assertNotSame($uri->getFragment(), $psr->getFragment());
    }

    public function testToString(): void
    {
        $uri = Uri::fromString(self::URI_ALL_PARTS);

        $psr = new Psr($uri);

        self::assertSame($uri->__toString(), $psr->__toString());
    }
}
