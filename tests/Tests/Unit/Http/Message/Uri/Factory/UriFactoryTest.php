<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Http\Message\Uri\Factory;

use PHPUnit\Framework\Attributes\DataProvider;
use Valkyrja\Http\Message\Throwable\Exception\Abstract\HttpMessageInvalidArgumentException;
use Valkyrja\Http\Message\Uri\Enum\Scheme;
use Valkyrja\Http\Message\Uri\Factory\UriFactory;
use Valkyrja\Http\Message\Uri\Throwable\Exception\HttpUriInvalidPathException;
use Valkyrja\Http\Message\Uri\Throwable\Exception\HttpUriInvalidPortException;
use Valkyrja\Http\Message\Uri\Throwable\Exception\HttpUriInvalidQueryException;
use Valkyrja\Http\Message\Uri\Uri;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class UriFactoryTest extends TestCase
{
    protected const string URI           = 'www.example.com';
    protected const string URI_HTTPS     = 'https://' . self::URI;
    protected const string URI_HTTP      = 'http://' . self::URI;
    protected const string URI_EMPTY     = '//' . self::URI;
    protected const string URI_ALL_PARTS = 'https://username:password@example.com:9090/path?arg=value#anchor';

    /**
     * @return array<string, array{string, string}>
     */
    public static function filterUserInfoEncodesProvider(): array
    {
        return [
            'keeps the unreserved characters'  => ['aZ0-_.~', 'aZ0-_.~'],
            'keeps the sub delimiters'         => ['!$&\'()*+,;=', '!$&\'()*+,;='],
            'keeps the username separator'     => ['user:pass', 'user:pass'],
            'encodes a space'                  => ['user name', 'user%20name'],
            'encodes an at sign'               => ['user:p@ss', 'user:p%40ss'],
            'encodes a forward slash'          => ['user/name', 'user%2Fname'],
            'encodes a question mark'          => ['user?name', 'user%3Fname'],
            'encodes a multibyte character'    => ['usér', 'us%C3%A9r'],
            'keeps a valid triplet'            => ['us%C3%A9r', 'us%C3%A9r'],
            'uppercases a triplet'             => ['us%c3%a9r', 'us%C3%A9r'],
            'encodes a lone percent sign'      => ['100%', '100%25'],
            'encodes an incomplete triplet'    => ['%2', '%252'],
            'encodes a non hexadecimal escape' => ['%zz', '%25zz'],
        ];
    }

    /**
     * @return array<string, array{string, string}>
     */
    public static function filterPathEncodesProvider(): array
    {
        return [
            'keeps the unreserved characters' => ['/aZ0-_.~', '/aZ0-_.~'],
            'keeps the sub delimiters'        => ['/!$&\'()*+,;=', '/!$&\'()*+,;='],
            'keeps a colon and an at sign'    => ['/a:b@c', '/a:b@c'],
            'keeps the segment separator'     => ['/a/b/c', '/a/b/c'],
            'encodes a space'                 => ['/foo bar', '/foo%20bar'],
            'encodes a multibyte character'   => ['/café', '/caf%C3%A9'],
            'keeps a valid triplet'           => ['/foo%20bar', '/foo%20bar'],
            'uppercases a triplet'            => ['/foo%2fbar', '/foo%2Fbar'],
            'encodes a lone percent sign'     => ['/100%/x', '/100%25/x'],
            'encodes a bracket'               => ['/a[b]c', '/a%5Bb%5Dc'],
            'normalizes the leading slashes'  => ['///a b', '/a%20b'],
            'keeps a relative path'           => ['a b', 'a%20b'],
        ];
    }

    /**
     * @return array<string, array{string, string}>
     */
    public static function filterQueryEncodesProvider(): array
    {
        return [
            'keeps the unreserved characters' => ['a=Z0-_.~', 'a=Z0-_.~'],
            'keeps the sub delimiters'        => ['!$&\'()*+,;=', '!$&\'()*+,;='],
            'keeps a colon and an at sign'    => ['a=b:c@d', 'a=b:c@d'],
            'keeps a slash'                   => ['a=b/c', 'a=b/c'],
            'keeps an inner question mark'    => ['?a=b?c', 'a=b?c'],
            'encodes a space'                 => ['a=b c&d=e', 'a=b%20c&d=e'],
            'encodes a multibyte character'   => ['a=café', 'a=caf%C3%A9'],
            'keeps a valid triplet'           => ['a=%C3%A9', 'a=%C3%A9'],
            'uppercases a triplet'            => ['a=%c3%a9', 'a=%C3%A9'],
            'encodes a lone percent sign'     => ['a=100%', 'a=100%25'],
            'encodes a bracket'               => ['a[]=b', 'a%5B%5D=b'],
        ];
    }

    /**
     * @return array<string, array{string, string}>
     */
    public static function filterFragmentEncodesProvider(): array
    {
        return [
            'keeps the unreserved characters' => ['aZ0-_.~', 'aZ0-_.~'],
            'keeps a colon and an at sign'    => ['a:b@c', 'a:b@c'],
            'keeps a slash and a question'    => ['a/b?c', 'a/b?c'],
            'encodes a space'                 => ['#a b', 'a%20b'],
            'encodes a multibyte character'   => ['café', 'caf%C3%A9'],
            'keeps a valid triplet'           => ['%C3%A9', '%C3%A9'],
            'uppercases a triplet'            => ['%c3%a9', '%C3%A9'],
            'encodes a lone percent sign'     => ['100%', '100%25'],
        ];
    }

    /**
     * @return array<string, array{string, string}>
     */
    public static function filterHostEncodesProvider(): array
    {
        return [
            'lowercases the reg name'       => ['EXAMPLE.COM', 'example.com'],
            'keeps the sub delimiters'      => ['a!$&\'()*+,;=b', 'a!$&\'()*+,;=b'],
            'encodes a space'               => ['exa mple.com', 'exa%20mple.com'],
            'encodes a colon'               => ['example.com:x', 'example.com%3Ax'],
            'encodes a multibyte character' => ['café.com', 'caf%C3%A9.com'],
            'keeps a valid triplet'         => ['caf%C3%A9.com', 'caf%C3%A9.com'],
            'encodes a lone percent sign'   => ['100%.com', '100%25.com'],
        ];
    }

    public function testFromString(): void
    {
        $uri              = UriFactory::fromString(self::URI);
        $uriWithAllParts  = UriFactory::fromString(self::URI_ALL_PARTS);
        $uriSecure        = UriFactory::fromString(self::URI_HTTPS);
        $uriNotSecure     = UriFactory::fromString(self::URI_HTTP);
        $uriUnknownSecure = UriFactory::fromString(self::URI_EMPTY);
        $uriWithJustPath  = UriFactory::fromString('/');
        $uriWithEmptyPath = UriFactory::fromString('');

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
        $uri = UriFactory::fromString('/');

        self::assertSame('', $uri->getHost());
        self::assertSame('/', $uri->getPath());
    }

    public function testFromStringException(): void
    {
        $this->expectException(HttpMessageInvalidArgumentException::class);

        UriFactory::fromString('//');
    }

    public function testFilterScheme(): void
    {
        self::assertSame(Scheme::HTTPS, UriFactory::filterScheme('HTTPS'));
        self::assertSame(Scheme::HTTPS, UriFactory::filterScheme('https:'));
        self::assertSame(Scheme::HTTPS, UriFactory::filterScheme('https://'));
        self::assertSame(Scheme::HTTP, UriFactory::filterScheme('http'));
        self::assertSame(Scheme::EMPTY, UriFactory::filterScheme(''));
    }

    public function testValidatePort(): void
    {
        // Valid ports should not throw
        UriFactory::validatePort(80);
        UriFactory::validatePort(443);
        UriFactory::validatePort(8080);

        $this->expectNotToPerformAssertions();
    }

    public function testValidatePortInvalidForNegative(): void
    {
        $this->expectException(HttpUriInvalidPortException::class);

        UriFactory::validatePort(-1);
    }

    public function testValidatePortInvalidFor0(): void
    {
        $this->expectException(HttpUriInvalidPortException::class);

        UriFactory::validatePort(0);
    }

    public function testFilterUserInfo(): void
    {
        self::assertSame('user', UriFactory::filterUserInfo('user'));
        self::assertSame('user:pass', UriFactory::filterUserInfo('user:pass'));
        self::assertSame('', UriFactory::filterUserInfo(''));
    }

    public function testFilterPath(): void
    {
        self::assertSame('/path', UriFactory::filterPath('/path'));
        self::assertSame('/path', UriFactory::filterPath('///path'));
        self::assertSame('path', UriFactory::filterPath('path'));
        self::assertSame('', UriFactory::filterPath(''));
    }

    public function testValidatePathWithQuery(): void
    {
        $this->expectException(HttpUriInvalidPathException::class);

        UriFactory::validatePath('/path?query');
    }

    public function testValidatePathWithFragment(): void
    {
        $this->expectException(HttpUriInvalidPathException::class);

        UriFactory::validatePath('/path#fragment');
    }

    public function testFilterQuery(): void
    {
        self::assertSame('query=value', UriFactory::filterQuery('query=value'));
        self::assertSame('query=value', UriFactory::filterQuery('?query=value'));
        self::assertSame('', UriFactory::filterQuery(''));
    }

    public function testValidateQueryWithFragment(): void
    {
        $this->expectException(HttpUriInvalidQueryException::class);

        UriFactory::validateQuery('query=value#fragment');
    }

    public function testFilterFragment(): void
    {
        self::assertSame('fragment', UriFactory::filterFragment('fragment'));
        self::assertSame('fragment', UriFactory::filterFragment('#fragment'));
        self::assertSame('', UriFactory::filterFragment(''));
    }

    public function testFilterHost(): void
    {
        self::assertSame('example.com', UriFactory::filterHost('EXAMPLE.com'));
        self::assertSame('example.com', UriFactory::filterHost('example.com'));
        self::assertSame('', UriFactory::filterHost(''));
    }

    /**
     * An IP literal is in brackets, and it holds colons that a reg-name does not allow.
     */
    public function testFilterHostKeepsIpLiteral(): void
    {
        self::assertSame('[::1]', UriFactory::filterHost('[::1]'));
        self::assertSame('[2001:db8::ff00:42:8329]', UriFactory::filterHost('[2001:DB8::FF00:42:8329]'));
        // A bracket on one side only does not make an IP literal, so the value is a reg-name.
        self::assertSame('%5B%3A%3A1', UriFactory::filterHost('[::1'));
        self::assertSame('%3A%3A1%5D', UriFactory::filterHost('::1]'));
    }

    #[DataProvider('filterUserInfoEncodesProvider')]
    public function testFilterUserInfoEncodes(string $userInfo, string $expected): void
    {
        self::assertSame($expected, UriFactory::filterUserInfo($userInfo));
    }

    #[DataProvider('filterPathEncodesProvider')]
    public function testFilterPathEncodes(string $path, string $expected): void
    {
        self::assertSame($expected, UriFactory::filterPath($path));
    }

    #[DataProvider('filterQueryEncodesProvider')]
    public function testFilterQueryEncodes(string $query, string $expected): void
    {
        self::assertSame($expected, UriFactory::filterQuery($query));
    }

    #[DataProvider('filterFragmentEncodesProvider')]
    public function testFilterFragmentEncodes(string $fragment, string $expected): void
    {
        self::assertSame($expected, UriFactory::filterFragment($fragment));
    }

    #[DataProvider('filterHostEncodesProvider')]
    public function testFilterHostEncodes(string $host, string $expected): void
    {
        self::assertSame($expected, UriFactory::filterHost($host));
    }

    /**
     * A value that arrives already encoded keeps its meaning through a second filter pass.
     */
    public function testFilterIsIdempotent(): void
    {
        $path     = UriFactory::filterPath('/foo bar/100%');
        $query    = UriFactory::filterQuery('a=b c&d=100%');
        $fragment = UriFactory::filterFragment('a b 100%');
        $userInfo = UriFactory::filterUserInfo('user:p@ss word');
        $host     = UriFactory::filterHost('exa mple.com');

        self::assertSame($path, UriFactory::filterPath($path));
        self::assertSame($query, UriFactory::filterQuery($query));
        self::assertSame($fragment, UriFactory::filterFragment($fragment));
        self::assertSame($userInfo, UriFactory::filterUserInfo($userInfo));
        self::assertSame($host, UriFactory::filterHost($host));
    }

    public function testIsStandardPort(): void
    {
        // Empty scheme with host but no port
        self::assertTrue(UriFactory::isStandardPort(Scheme::EMPTY, 'example.com', 0));
        // Empty scheme with host but no port
        self::assertTrue(UriFactory::isStandardPort(Scheme::EMPTY, 'example.com', -1));
        // Empty scheme with no host
        self::assertFalse(UriFactory::isStandardPort(Scheme::EMPTY, '', 0));
        // Empty scheme with no host
        self::assertFalse(UriFactory::isStandardPort(Scheme::EMPTY, '', -1));

        // HTTP scheme with standard port 80
        self::assertTrue(UriFactory::isStandardPort(Scheme::HTTP, 'example.com', 80));
        // HTTP scheme with non-standard port
        self::assertFalse(UriFactory::isStandardPort(Scheme::HTTP, 'example.com', 8080));
        // HTTP scheme with no port
        self::assertTrue(UriFactory::isStandardPort(Scheme::HTTP, 'example.com', 0));
        // HTTP scheme with no port
        self::assertTrue(UriFactory::isStandardPort(Scheme::HTTP, 'example.com', -1));

        // HTTPS scheme with standard port 443
        self::assertTrue(UriFactory::isStandardPort(Scheme::HTTPS, 'example.com', 443));
        // HTTPS scheme with non-standard port
        self::assertFalse(UriFactory::isStandardPort(Scheme::HTTPS, 'example.com', 8443));
    }

    public function testIsStandardUnsecurePort(): void
    {
        self::assertTrue(UriFactory::isStandardUnsecurePort(Scheme::HTTP, 80));
        self::assertFalse(UriFactory::isStandardUnsecurePort(Scheme::HTTP, 8080));
        self::assertFalse(UriFactory::isStandardUnsecurePort(Scheme::HTTPS, 80));
        self::assertFalse(UriFactory::isStandardUnsecurePort(Scheme::HTTP, 0));
        self::assertFalse(UriFactory::isStandardUnsecurePort(Scheme::HTTP, -1));
    }

    public function testIsStandardSecurePort(): void
    {
        self::assertTrue(UriFactory::isStandardSecurePort(Scheme::HTTPS, 443));
        self::assertFalse(UriFactory::isStandardSecurePort(Scheme::HTTPS, 8443));
        self::assertFalse(UriFactory::isStandardSecurePort(Scheme::HTTP, 443));
        self::assertFalse(UriFactory::isStandardSecurePort(Scheme::HTTPS, 0));
        self::assertFalse(UriFactory::isStandardSecurePort(Scheme::HTTPS, -1));
    }

    public function testGetSchemeStringPart(): void
    {
        $uriWithScheme    = new Uri(scheme: Scheme::HTTPS);
        $uriWithoutScheme = new Uri();

        self::assertSame('https:', UriFactory::getSchemeStringPart($uriWithScheme));
        self::assertSame('', UriFactory::getSchemeStringPart($uriWithoutScheme));
    }

    public function testGetAuthorityStringPart(): void
    {
        $uriWithAuthority    = new Uri(host: 'example.com');
        $uriWithoutAuthority = new Uri();

        self::assertSame('//example.com', UriFactory::getAuthorityStringPart($uriWithAuthority));
        self::assertSame('', UriFactory::getAuthorityStringPart($uriWithoutAuthority));
    }

    public function testGetPathStringPart(): void
    {
        $uriWithPath        = new Uri(path: '/path');
        $uriWithPathNoSlash = new Uri(path: 'path');
        $uriWithoutPath     = new Uri();

        self::assertSame('/path', UriFactory::getPathStringPart($uriWithPath));
        self::assertSame('/path', UriFactory::getPathStringPart($uriWithPathNoSlash));
        self::assertSame('', UriFactory::getPathStringPart($uriWithoutPath));
    }

    public function testGetQueryStringPart(): void
    {
        $uriWithQuery    = new Uri(query: 'key=value');
        $uriWithoutQuery = new Uri();

        self::assertSame('?key=value', UriFactory::getQueryStringPart($uriWithQuery));
        self::assertSame('', UriFactory::getQueryStringPart($uriWithoutQuery));
    }

    public function testGetFragmentStringPart(): void
    {
        $uriWithFragment    = new Uri(fragment: 'section');
        $uriWithoutFragment = new Uri();

        self::assertSame('#section', UriFactory::getFragmentStringPart($uriWithFragment));
        self::assertSame('', UriFactory::getFragmentStringPart($uriWithoutFragment));
    }

    public function testToString(): void
    {
        $uri = new Uri(
            scheme: Scheme::HTTPS,
            host: 'example.com',
            port: 8080,
            path: '/path',
            query: 'key=value',
            fragment: 'section'
        );

        self::assertSame('https://example.com:8080/path?key=value#section', UriFactory::toString($uri));

        $emptyUri = new Uri();
        self::assertSame('', UriFactory::toString($emptyUri));
    }

    public function testValidateFragment(): void
    {
        // validateFragment is currently empty but should not throw for any input
        UriFactory::validateFragment('');
        UriFactory::validateFragment('section');
        UriFactory::validateFragment('section-with-special-chars!@#');

        $this->expectNotToPerformAssertions();
    }

    public function testValidatePathValid(): void
    {
        // Valid paths should not throw
        UriFactory::validatePath('');
        UriFactory::validatePath('/');
        UriFactory::validatePath('/path');
        UriFactory::validatePath('/path/to/resource');
        UriFactory::validatePath('relative/path');

        $this->expectNotToPerformAssertions();
    }

    public function testValidateQueryValid(): void
    {
        // Valid queries should not throw
        UriFactory::validateQuery('');
        UriFactory::validateQuery('key=value');
        UriFactory::validateQuery('key1=value1&key2=value2');

        $this->expectNotToPerformAssertions();
    }

    public function testFilterPathWithValidPath(): void
    {
        // filterPath calls validatePath internally, then normalizes slashes
        self::assertSame('/single', UriFactory::filterPath('/single'));
        self::assertSame('/normalized', UriFactory::filterPath('//normalized'));
        self::assertSame('/multiple', UriFactory::filterPath('////multiple'));
    }

    public function testFilterPathThrowsForInvalidPath(): void
    {
        $this->expectException(HttpUriInvalidPathException::class);

        UriFactory::filterPath('/path?with=query');
    }

    public function testFilterQueryThrowsForInvalidQuery(): void
    {
        $this->expectException(HttpUriInvalidQueryException::class);

        UriFactory::filterQuery('query=value#fragment');
    }

    public function testToStringWithOnlyPath(): void
    {
        $uri = new Uri(path: '/path');

        self::assertSame('/path', UriFactory::toString($uri));
    }

    public function testToStringWithSchemeAndHost(): void
    {
        $uri = new Uri(
            scheme: Scheme::HTTPS,
            host: 'example.com'
        );

        self::assertSame('https://example.com', UriFactory::toString($uri));
    }

    public function testIsStandardPortWithNoHost(): void
    {
        // When host is empty but port is provided with HTTP scheme
        self::assertTrue(UriFactory::isStandardPort(Scheme::HTTP, '', 0));
        self::assertTrue(UriFactory::isStandardPort(Scheme::HTTPS, '', 0));
        self::assertTrue(UriFactory::isStandardPort(Scheme::HTTP, '', -1));
        self::assertTrue(UriFactory::isStandardPort(Scheme::HTTPS, '', -1));
    }
}
