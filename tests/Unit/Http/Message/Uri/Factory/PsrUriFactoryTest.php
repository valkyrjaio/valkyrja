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

namespace Valkyrja\Tests\Unit\Http\Message\Uri\Factory;

use Valkyrja\Http\Message\Uri\Enum\Scheme;
use Valkyrja\Http\Message\Uri\Factory\PsrUriFactory;
use Valkyrja\Http\Message\Uri\Psr\Uri as PsrUri;
use Valkyrja\Http\Message\Uri\Uri;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class PsrUriFactoryTest extends TestCase
{
    protected const string URI           = 'www.example.com';
    protected const string URI_HTTPS     = 'https://' . self::URI;
    protected const string URI_HTTP      = 'http://' . self::URI;
    protected const string URI_EMPTY     = '//' . self::URI;
    protected const string URI_ALL_PARTS = 'https://username:password@example.com:9090/path?arg=value#anchor';

    public function testFromPsrArray(): void
    {
        $stream = new Uri(
            scheme: $scheme     = Scheme::HTTPS,
            username: $username = 'username',
            password: $password = 'password',
            host: $host         = 'host',
            port: $port         = 20,
            path: $path         = '/path',
            query: $query       = 'test=test',
            fragment: $fragment = 'fragment',
        );

        $psr = new PsrUri($stream);

        $fromPsr = PsrUriFactory::fromPsr($psr);

        self::assertSame($scheme, $fromPsr->getScheme());
        self::assertSame($username, $fromPsr->getUsername());
        self::assertSame($password, $fromPsr->getPassword());
        self::assertSame($host, $fromPsr->getHost());
        self::assertSame($port, $fromPsr->getPort());
        self::assertSame($path, $fromPsr->getPath());
        self::assertSame($query, $fromPsr->getQuery());
        self::assertSame($fragment, $fromPsr->getFragment());
    }

    public function testFromPsrWithoutPassword(): void
    {
        $uri = new Uri(
            scheme: Scheme::HTTPS,
            username: 'username',
            host: 'host',
            path: '/path',
        );

        $psr = new PsrUri($uri);

        $fromPsr = PsrUriFactory::fromPsr($psr);

        self::assertSame(Scheme::HTTPS, $fromPsr->getScheme());
        self::assertSame('username', $fromPsr->getUsername());
        self::assertSame('', $fromPsr->getPassword());
        self::assertSame('host', $fromPsr->getHost());
        self::assertSame('/path', $fromPsr->getPath());
    }

    public function testFromPsrWithEmptyUserInfo(): void
    {
        $uri = new Uri(
            scheme: Scheme::HTTPS,
            host: 'host',
            path: '/path',
        );

        $psr = new PsrUri($uri);

        $fromPsr = PsrUriFactory::fromPsr($psr);

        self::assertSame('', $fromPsr->getUsername());
        self::assertSame('', $fromPsr->getPassword());
    }
}
