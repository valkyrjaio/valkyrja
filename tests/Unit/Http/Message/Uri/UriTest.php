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

namespace Valkyrja\Tests\Unit\Http\Message\Uri;

use Valkyrja\Http\Message\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Http\Message\Uri\Enum\Scheme;
use Valkyrja\Http\Message\Uri\Throwable\Exception\InvalidPathException;
use Valkyrja\Http\Message\Uri\Throwable\Exception\InvalidPortException;
use Valkyrja\Http\Message\Uri\Throwable\Exception\InvalidQueryException;
use Valkyrja\Http\Message\Uri\Uri;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class UriTest extends TestCase
{
    protected const string URI           = 'www.example.com';
    protected const string URI_HTTPS     = 'https://' . self::URI;
    protected const string URI_HTTP      = 'http://' . self::URI;
    protected const string URI_EMPTY     = '//' . self::URI;
    protected const string URI_ALL_PARTS = 'https://username:password@example.com:9090/path?arg=value#anchor';

    public function testFromString(): void
    {
        $uri              = Uri::fromString(self::URI);
        $uriWithAllParts  = Uri::fromString(self::URI_ALL_PARTS);
        $uriSecure        = Uri::fromString(self::URI_HTTPS);
        $uriNotSecure     = Uri::fromString(self::URI_HTTP);
        $uriUnknownSecure = Uri::fromString(self::URI_EMPTY);
        $uriWithJustPath  = Uri::fromString('/');
        $uriWithEmptyPath = Uri::fromString('');

        self::assertFalse($uri->isSecure());
        self::assertTrue($uriWithAllParts->isSecure());
        self::assertTrue($uriSecure->isSecure());
        self::assertFalse($uriNotSecure->isSecure());
        self::assertFalse($uriUnknownSecure->isSecure());

        self::assertSame(Scheme::HTTPS, $uriWithAllParts->getScheme());
        self::assertSame('username:password@example.com:9090', $uriWithAllParts->getAuthority());
        self::assertSame('username:password', $uriWithAllParts->getUserInfo());
        self::assertSame('example.com', $uriWithAllParts->getHost());
        self::assertSame(9090, $uriWithAllParts->getPort());
        self::assertSame('example.com:9090', $uriWithAllParts->getHostPort());
        self::assertSame('https://example.com:9090', $uriWithAllParts->getSchemeHostPort());
        self::assertSame('/path', $uriWithAllParts->getPath());
        self::assertSame('arg=value', $uriWithAllParts->getQuery());
        self::assertSame('anchor', $uriWithAllParts->getFragment());

        self::assertEmpty($uriWithJustPath->getHost());
        self::assertSame('/', $uriWithJustPath->getPath());

        self::assertEmpty($uriWithEmptyPath->getHost());
        self::assertSame('', $uriWithEmptyPath->getPath());
    }

    public function testFromStringWithJustPath(): void
    {
        $uri = Uri::fromString('/');

        self::assertSame('', $uri->getHost());
        self::assertSame('/', $uri->getPath());
    }

    public function testFromStringException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Uri::fromString('//');
    }

    public function testIsSecure(): void
    {
        $uri              = Uri::fromString(self::URI);
        $uriSecure        = Uri::fromString(self::URI_HTTPS);
        $uriNotSecure     = Uri::fromString(self::URI_HTTP);
        $uriUnknownSecure = Uri::fromString(self::URI_EMPTY);

        self::assertFalse($uri->isSecure());
        self::assertTrue($uriSecure->isSecure());
        self::assertFalse($uriNotSecure->isSecure());
        self::assertFalse($uriUnknownSecure->isSecure());
    }

    public function testGetScheme(): void
    {
        $uri      = Uri::fromString(self::URI);
        $uriHttps = Uri::fromString(self::URI_HTTPS);
        $uriHttp  = Uri::fromString(self::URI_HTTP);
        $uriEmpty = Uri::fromString(self::URI_EMPTY);

        self::assertSame(Scheme::EMPTY, $uri->getScheme());
        self::assertSame(Scheme::HTTPS, $uriHttps->getScheme());
        self::assertSame(Scheme::HTTP, $uriHttp->getScheme());
        self::assertSame(Scheme::EMPTY, $uriEmpty->getScheme());
    }

    public function testSetScheme(): void
    {
        $uri = Uri::fromString(self::URI);

        $uriNowWithHttps = $uri->withScheme(Scheme::HTTPS);
        $uriNowWithHttp  = $uri->withScheme(Scheme::HTTP);
        $uriNowWithEmpty = $uri->withScheme(Scheme::EMPTY);

        self::assertNotSame($uri, $uriNowWithHttps);
        self::assertNotSame($uri, $uriNowWithHttp);
        self::assertNotSame($uri, $uriNowWithEmpty);

        self::assertSame(Scheme::EMPTY, $uri->getScheme());
        self::assertSame(Scheme::HTTPS, $uriNowWithHttps->getScheme());
        self::assertSame(Scheme::HTTP, $uriNowWithHttp->getScheme());
        self::assertSame(Scheme::EMPTY, $uriNowWithEmpty->getScheme());
    }

    public function testGetAuthority(): void
    {
        $uri         = Uri::fromString(self::URI);
        $uriHttps    = Uri::fromString(self::URI_HTTPS);
        $uriHttp     = Uri::fromString(self::URI_HTTP);
        $uriEmpty    = Uri::fromString(self::URI_EMPTY);
        $uriJustPath = Uri::fromString('/path');

        self::assertSame(self::URI, $uri->getAuthority());
        self::assertSame(self::URI, $uriHttps->getAuthority());
        self::assertSame(self::URI, $uriHttp->getAuthority());
        self::assertSame(self::URI, $uriEmpty->getAuthority());
        self::assertSame('', $uriJustPath->getAuthority());
    }

    public function testGetUsername(): void
    {
        $uri         = Uri::fromString(self::URI);
        $uriHttps    = Uri::fromString(self::URI_HTTPS);
        $uriHttp     = Uri::fromString(self::URI_HTTP);
        $uriEmpty    = Uri::fromString(self::URI_EMPTY);
        $uriAllParts = Uri::fromString(self::URI_ALL_PARTS);

        $uriWIthJustUsername = Uri::fromString('https://username@example.com');

        self::assertEmpty($uri->getUsername());
        self::assertEmpty($uriHttps->getUsername());
        self::assertEmpty($uriHttp->getUsername());
        self::assertEmpty($uriEmpty->getUsername());
        self::assertSame('username', $uriAllParts->getUsername());
        self::assertSame('username', $uriWIthJustUsername->getUsername());
    }

    public function testGetPassword(): void
    {
        $uri         = Uri::fromString(self::URI);
        $uriHttps    = Uri::fromString(self::URI_HTTPS);
        $uriHttp     = Uri::fromString(self::URI_HTTP);
        $uriEmpty    = Uri::fromString(self::URI_EMPTY);
        $uriAllParts = Uri::fromString(self::URI_ALL_PARTS);

        $uriWIthJustUsername = Uri::fromString('https://username@example.com');

        self::assertEmpty($uri->getPassword());
        self::assertEmpty($uriHttps->getPassword());
        self::assertEmpty($uriHttp->getPassword());
        self::assertEmpty($uriEmpty->getPassword());
        self::assertSame('password', $uriAllParts->getPassword());
        self::assertSame('', $uriWIthJustUsername->getPassword());
    }

    public function testGetUserInfo(): void
    {
        $uri         = Uri::fromString(self::URI);
        $uriHttps    = Uri::fromString(self::URI_HTTPS);
        $uriHttp     = Uri::fromString(self::URI_HTTP);
        $uriEmpty    = Uri::fromString(self::URI_EMPTY);
        $uriAllParts = Uri::fromString(self::URI_ALL_PARTS);

        $uriWIthJustUsername = Uri::fromString('https://username@example.com');

        self::assertEmpty($uri->getUserInfo());
        self::assertEmpty($uriHttps->getUserInfo());
        self::assertEmpty($uriHttp->getUserInfo());
        self::assertEmpty($uriEmpty->getUserInfo());
        self::assertSame('username:password', $uriAllParts->getUserInfo());
        self::assertSame('username', $uriWIthJustUsername->getUserInfo());
    }

    public function testGetHost(): void
    {
        $uri         = Uri::fromString(self::URI);
        $uriHttps    = Uri::fromString(self::URI_HTTPS);
        $uriHttp     = Uri::fromString(self::URI_HTTP);
        $uriEmpty    = Uri::fromString(self::URI_EMPTY);
        $uriAllParts = Uri::fromString(self::URI_ALL_PARTS);

        self::assertSame(self::URI, $uri->getHost());
        self::assertSame(self::URI, $uriHttps->getHost());
        self::assertSame(self::URI, $uriHttp->getHost());
        self::assertSame(self::URI, $uriEmpty->getHost());
        self::assertSame('example.com', $uriAllParts->getHost());
    }

    public function testGetPort(): void
    {
        $uri         = Uri::fromString(self::URI);
        $uriHttps    = Uri::fromString(self::URI_HTTPS);
        $uriHttp     = Uri::fromString(self::URI_HTTP);
        $uriEmpty    = Uri::fromString(self::URI_EMPTY);
        $uriAllParts = Uri::fromString(self::URI_ALL_PARTS);

        self::assertNull($uri->getPort());
        self::assertNull($uriHttps->getPort());
        self::assertNull($uriHttp->getPort());
        self::assertNull($uriEmpty->getPort());
        self::assertSame(9090, $uriAllParts->getPort());
    }

    public function testGetHostPort(): void
    {
        $uri         = Uri::fromString(self::URI);
        $uriHttps    = Uri::fromString(self::URI_HTTPS);
        $uriHttp     = Uri::fromString(self::URI_HTTP);
        $uriEmpty    = Uri::fromString(self::URI_EMPTY);
        $uriAllParts = Uri::fromString(self::URI_ALL_PARTS);

        self::assertSame(self::URI, $uri->getHostPort());
        self::assertSame(self::URI, $uriHttps->getHostPort());
        self::assertSame(self::URI, $uriHttp->getHostPort());
        self::assertSame(self::URI, $uriEmpty->getHostPort());
        self::assertSame('example.com:9090', $uriAllParts->getHostPort());
    }

    public function testGetSchemeHostPort(): void
    {
        $uri         = Uri::fromString(self::URI);
        $uriHttps    = Uri::fromString(self::URI_HTTPS);
        $uriHttp     = Uri::fromString(self::URI_HTTP);
        $uriEmpty    = Uri::fromString(self::URI_EMPTY);
        $uriAllParts = Uri::fromString(self::URI_ALL_PARTS);

        self::assertSame(self::URI, $uri->getSchemeHostPort());
        self::assertSame(self::URI_HTTPS, $uriHttps->getSchemeHostPort());
        self::assertSame(self::URI_HTTP, $uriHttp->getSchemeHostPort());
        self::assertSame(self::URI, $uriEmpty->getSchemeHostPort());
        self::assertSame('https://example.com:9090', $uriAllParts->getSchemeHostPort());
    }

    public function testGetPath(): void
    {
        $uri         = Uri::fromString(self::URI);
        $uriHttps    = Uri::fromString(self::URI_HTTPS);
        $uriHttp     = Uri::fromString(self::URI_HTTP);
        $uriEmpty    = Uri::fromString(self::URI_EMPTY);
        $uriAllParts = Uri::fromString(self::URI_ALL_PARTS);

        self::assertSame('', $uri->getPath());
        self::assertSame('', $uriHttps->getPath());
        self::assertSame('', $uriHttp->getPath());
        self::assertSame('', $uriEmpty->getPath());
        self::assertSame('/path', $uriAllParts->getPath());
    }

    public function testGetQuery(): void
    {
        $uri         = Uri::fromString(self::URI);
        $uriHttps    = Uri::fromString(self::URI_HTTPS);
        $uriHttp     = Uri::fromString(self::URI_HTTP);
        $uriEmpty    = Uri::fromString(self::URI_EMPTY);
        $uriAllParts = Uri::fromString(self::URI_ALL_PARTS);

        self::assertEmpty($uri->getQuery());
        self::assertEmpty($uriHttps->getQuery());
        self::assertEmpty($uriHttp->getQuery());
        self::assertEmpty($uriEmpty->getQuery());
        self::assertSame('arg=value', $uriAllParts->getQuery());
    }

    public function testGetFragment(): void
    {
        $uri         = Uri::fromString(self::URI);
        $uriHttps    = Uri::fromString(self::URI_HTTPS);
        $uriHttp     = Uri::fromString(self::URI_HTTP);
        $uriEmpty    = Uri::fromString(self::URI_EMPTY);
        $uriAllParts = Uri::fromString(self::URI_ALL_PARTS);

        self::assertEmpty($uri->getFragment());
        self::assertEmpty($uriHttps->getFragment());
        self::assertEmpty($uriHttp->getFragment());
        self::assertEmpty($uriEmpty->getFragment());
        self::assertSame('anchor', $uriAllParts->getFragment());
    }

    public function testWithScheme(): void
    {
        $uri      = Uri::fromString(self::URI);
        $uriHttps = $uri->withScheme(Scheme::HTTPS);
        $uriHttp  = $uri->withScheme(Scheme::HTTP);
        $uriEmpty = $uri->withScheme(Scheme::EMPTY);

        self::assertNotSame($uri, $uriHttps);
        self::assertNotSame($uri, $uriHttp);
        self::assertNotSame($uri, $uriEmpty);

        self::assertSame(Scheme::EMPTY, $uri->getScheme());
        self::assertSame(Scheme::HTTPS, $uriHttps->getScheme());
        self::assertSame(Scheme::HTTP, $uriHttp->getScheme());
        self::assertSame(Scheme::EMPTY, $uriEmpty->getScheme());
    }

    public function testWithUsername(): void
    {
        $uri  = Uri::fromString(self::URI);
        $uri2 = $uri->withUsername('username');
        $uri3 = $uri->withUsername('username');
        $uri4 = $uri->withUsername('username2');

        self::assertNotSame($uri, $uri2);
        self::assertNotSame($uri, $uri3);
        // Ensure same username still doesn't create the same object
        self::assertNotSame($uri2, $uri3);
        self::assertNotSame($uri, $uri4);

        self::assertSame('', $uri->getUsername());
        self::assertSame('username', $uri2->getUsername());
        self::assertSame('username', $uri3->getUsername());
        self::assertSame('username2', $uri4->getUsername());
    }

    public function testWithPassword(): void
    {
        $uri  = new Uri(username: 'test', host: self::URI);
        $uri2 = $uri->withPassword('password');
        $uri3 = $uri->withPassword('password');
        $uri4 = $uri->withPassword('password2');
        $uri5 = $uri4->withUsername('');

        self::assertNotSame($uri, $uri2);
        self::assertNotSame($uri, $uri3);
        // Ensure same username still doesn't create the same object
        self::assertNotSame($uri2, $uri3);
        self::assertNotSame($uri, $uri4);
        self::assertNotSame($uri4, $uri5);

        self::assertSame('', $uri->getPassword());
        self::assertSame('password', $uri2->getPassword());
        self::assertSame('password', $uri3->getPassword());
        self::assertSame('password2', $uri4->getPassword());
        // If no username is present in the URI then the password should also be blank
        self::assertSame('', $uri5->getPassword());
    }

    public function testWithUserInfo(): void
    {
        $uri  = Uri::fromString(self::URI);
        $uri2 = $uri->withUserInfo('username', 'password');
        $uri3 = $uri->withUserInfo('username', 'password');
        $uri4 = $uri->withUserInfo('username2', 'password2');
        $uri5 = $uri4->withUserInfo('', 'password3');

        self::assertNotSame($uri, $uri2);
        self::assertNotSame($uri, $uri3);
        // Ensure same username still doesn't create the same object
        self::assertNotSame($uri2, $uri3);
        self::assertNotSame($uri, $uri4);
        self::assertNotSame($uri4, $uri5);

        self::assertSame('', $uri->getUsername());
        self::assertSame('username', $uri2->getUsername());
        self::assertSame('username', $uri3->getUsername());
        self::assertSame('username2', $uri4->getUsername());
        self::assertSame('', $uri5->getUsername());

        self::assertSame('', $uri->getPassword());
        self::assertSame('password', $uri2->getPassword());
        self::assertSame('password', $uri3->getPassword());
        self::assertSame('password2', $uri4->getPassword());
        // If no username is present in the URI then the password should also be blank
        self::assertSame('', $uri5->getPassword());

        self::assertSame('', $uri->getUserInfo());
        self::assertSame('username:password', $uri2->getUserInfo());
        self::assertSame('username:password', $uri3->getUserInfo());
        self::assertSame('username2:password2', $uri4->getUserInfo());
        // If no username is present in the URI then the password should also be blank
        self::assertSame('', $uri5->getUserInfo());
    }

    public function testWithHost(): void
    {
        $uri  = Uri::fromString(self::URI);
        $uri2 = $uri->withHost('localhost');
        $uri3 = $uri->withHost('website.com');
        $uri4 = $uri->withHost('www.host.com');

        self::assertNotSame($uri, $uri2);
        self::assertNotSame($uri, $uri3);
        self::assertNotSame($uri2, $uri3);
        self::assertNotSame($uri, $uri4);

        self::assertSame('www.example.com', $uri->getHost());
        self::assertSame('localhost', $uri2->getHost());
        self::assertSame('website.com', $uri3->getHost());
        self::assertSame('www.host.com', $uri4->getHost());
    }

    public function testWithPort(): void
    {
        $uri  = Uri::fromString(self::URI);
        $uri2 = $uri->withPort(80);
        $uri3 = $uri->withPort(90);
        $uri4 = $uri->withScheme(Scheme::HTTPS)->withPort(443);
        $uri5 = $uri->withScheme(Scheme::HTTP)->withPort(80);

        self::assertNotSame($uri, $uri2);
        self::assertNotSame($uri, $uri3);
        self::assertNotSame($uri2, $uri3);
        self::assertNotSame($uri, $uri4);
        self::assertNotSame($uri, $uri5);

        // No port passed
        self::assertNull($uri->getPort());
        // Empty Scheme with a standard port should still come through
        self::assertSame(80, $uri2->getPort());
        self::assertSame(90, $uri3->getPort());
        // Https scheme with standard https port should be null
        self::assertNull($uri4->getPort());
        // Http scheme with standard http port should be null
        self::assertNull($uri5->getPort());
    }

    public function testInvalidPort(): void
    {
        $this->expectException(InvalidPortException::class);

        new Uri(port: 65536);
    }

    public function testInvalidPort2(): void
    {
        $this->expectException(InvalidPortException::class);

        new Uri(port: 0);
    }

    public function testWithPath(): void
    {
        $uri  = Uri::fromString(self::URI);
        $uri2 = $uri->withPath('/path');
        $uri3 = $uri->withPath('/another-path');
        $uri4 = $uri->withPath('/');

        self::assertNotSame($uri, $uri2);
        self::assertNotSame($uri, $uri3);
        self::assertNotSame($uri2, $uri3);
        self::assertNotSame($uri, $uri4);

        self::assertSame('', $uri->getPath());
        self::assertSame('/path', $uri2->getPath());
        self::assertSame('/another-path', $uri3->getPath());
        self::assertSame('/', $uri4->getPath());
    }

    public function testPathQueryIncluded(): void
    {
        $this->expectException(InvalidPathException::class);

        new Uri(path: '/path?query=test');
    }

    public function testPathFragmentIncluded(): void
    {
        $this->expectException(InvalidPathException::class);

        new Uri(path: '/path#anchor');
    }

    public function testWithPathQueryIncluded(): void
    {
        $this->expectException(InvalidPathException::class);

        $uri = Uri::fromString(self::URI);
        $uri->withPath('/path?query=test');
    }

    public function testWithPathFragmentIncluded(): void
    {
        $this->expectException(InvalidPathException::class);

        $uri = Uri::fromString(self::URI);
        $uri->withPath('/path#anchor');
    }

    public function testWithQuery(): void
    {
        $uri  = Uri::fromString(self::URI);
        $uri2 = $uri->withQuery('arg=value');
        $uri3 = $uri->withQuery('?some=bar&foo[]=1&foo[]=2');
        $uri4 = $uri->withQuery('another=three');

        self::assertNotSame($uri, $uri2);
        self::assertNotSame($uri, $uri3);
        self::assertNotSame($uri2, $uri3);
        self::assertNotSame($uri, $uri4);

        self::assertSame('', $uri->getQuery());
        self::assertSame('arg=value', $uri2->getQuery());
        self::assertSame('some=bar&foo[]=1&foo[]=2', $uri3->getQuery());
        self::assertSame('another=three', $uri4->getQuery());
    }

    public function testWithQueryFragmentIncluded(): void
    {
        $this->expectException(InvalidQueryException::class);

        $uri = Uri::fromString(self::URI);
        $uri->withQuery('?query=test#anchor');
    }

    public function testWithFragment(): void
    {
        $uri  = Uri::fromString(self::URI);
        $uri2 = $uri->withFragment('anchor');
        $uri3 = $uri->withFragment('#something');
        $uri4 = $uri->withFragment('#another');

        self::assertNotSame($uri, $uri2);
        self::assertNotSame($uri, $uri3);
        self::assertNotSame($uri2, $uri3);
        self::assertNotSame($uri, $uri4);

        self::assertSame('', $uri->getFragment());
        self::assertSame('anchor', $uri2->getFragment());
        self::assertSame('something', $uri3->getFragment());
        self::assertSame('another', $uri4->getFragment());
    }

    public function testToString(): void
    {
        $uri         = Uri::fromString(self::URI);
        $uriHttps    = Uri::fromString(self::URI_HTTPS);
        $uriHttp     = Uri::fromString(self::URI_HTTP);
        $uriEmpty    = Uri::fromString(self::URI_EMPTY);
        $uriAllParts = Uri::fromString(self::URI_ALL_PARTS);

        // Since an authority is present the Uri will be prefixed by `//`
        self::assertSame('//' . self::URI, $uri->__toString());
        // Test to ensure secondary call is the same as previous
        self::assertSame('//' . self::URI, $uri->__toString());
        self::assertSame(self::URI_HTTPS, $uriHttps->__toString());
        self::assertSame(self::URI_HTTP, $uriHttp->__toString());
        self::assertSame(self::URI_EMPTY, $uriEmpty->__toString());
        self::assertSame(self::URI_ALL_PARTS, $uriAllParts->__toString());

        $uri2 = $uri->withPath('/');

        // Test to ensure cached to string value from $uri isn't used again
        self::assertSame('//' . self::URI . '/', $uri2->__toString());

        $uri3 = $uri->withPath('test');

        // Test to ensure cached to string value from $uri isn't used again
        self::assertSame('//' . self::URI . '/test', $uri3->__toString());
    }
}
