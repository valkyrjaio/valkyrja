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

use Valkyrja\Http\Message\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Http\Message\Uri\Enum\Scheme;
use Valkyrja\Http\Message\Uri\Factory\UriFactory;
use Valkyrja\Http\Message\Uri\Throwable\Exception\InvalidPathException;
use Valkyrja\Http\Message\Uri\Throwable\Exception\InvalidPortException;
use Valkyrja\Http\Message\Uri\Throwable\Exception\InvalidQueryException;
use Valkyrja\Http\Message\Uri\Uri;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class UriFactoryTest extends TestCase
{
    protected const string URI           = 'www.example.com';
    protected const string URI_HTTPS     = 'https://' . self::URI;
    protected const string URI_HTTP      = 'http://' . self::URI;
    protected const string URI_EMPTY     = '//' . self::URI;
    protected const string URI_ALL_PARTS = 'https://username:password@example.com:9090/path?arg=value#anchor';

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
        $this->expectException(InvalidArgumentException::class);

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
        UriFactory::validatePort(null);

        self::assertTrue(true); // If we reach here, no exception was thrown
    }

    public function testValidatePortInvalid(): void
    {
        $this->expectException(InvalidPortException::class);

        UriFactory::validatePort(-1);
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
        $this->expectException(InvalidPathException::class);

        UriFactory::validatePath('/path?query');
    }

    public function testValidatePathWithFragment(): void
    {
        $this->expectException(InvalidPathException::class);

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
        $this->expectException(InvalidQueryException::class);

        UriFactory::validateQuery('query=value#fragment');
    }

    public function testFilterFragment(): void
    {
        self::assertSame('fragment', UriFactory::filterFragment('fragment'));
        self::assertSame('fragment', UriFactory::filterFragment('#fragment'));
        self::assertSame('', UriFactory::filterFragment(''));
    }

    public function testIsStandardPort(): void
    {
        // Empty scheme with host but no port
        self::assertTrue(UriFactory::isStandardPort(Scheme::EMPTY, 'example.com', null));
        // Empty scheme with no host
        self::assertFalse(UriFactory::isStandardPort(Scheme::EMPTY, '', null));

        // HTTP scheme with standard port 80
        self::assertTrue(UriFactory::isStandardPort(Scheme::HTTP, 'example.com', 80));
        // HTTP scheme with non-standard port
        self::assertFalse(UriFactory::isStandardPort(Scheme::HTTP, 'example.com', 8080));
        // HTTP scheme with no port
        self::assertTrue(UriFactory::isStandardPort(Scheme::HTTP, 'example.com', null));

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
        self::assertFalse(UriFactory::isStandardUnsecurePort(Scheme::HTTP, null));
    }

    public function testIsStandardSecurePort(): void
    {
        self::assertTrue(UriFactory::isStandardSecurePort(Scheme::HTTPS, 443));
        self::assertFalse(UriFactory::isStandardSecurePort(Scheme::HTTPS, 8443));
        self::assertFalse(UriFactory::isStandardSecurePort(Scheme::HTTP, 443));
        self::assertFalse(UriFactory::isStandardSecurePort(Scheme::HTTPS, null));
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

        self::assertTrue(true); // If we reach here, no exception was thrown
    }

    public function testValidatePathValid(): void
    {
        // Valid paths should not throw
        UriFactory::validatePath('');
        UriFactory::validatePath('/');
        UriFactory::validatePath('/path');
        UriFactory::validatePath('/path/to/resource');
        UriFactory::validatePath('relative/path');

        self::assertTrue(true); // If we reach here, no exception was thrown
    }

    public function testValidateQueryValid(): void
    {
        // Valid queries should not throw
        UriFactory::validateQuery('');
        UriFactory::validateQuery('key=value');
        UriFactory::validateQuery('key1=value1&key2=value2');

        self::assertTrue(true); // If we reach here, no exception was thrown
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
        $this->expectException(InvalidPathException::class);

        UriFactory::filterPath('/path?with=query');
    }

    public function testFilterQueryThrowsForInvalidQuery(): void
    {
        $this->expectException(InvalidQueryException::class);

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
        self::assertTrue(UriFactory::isStandardPort(Scheme::HTTP, '', null));
        self::assertTrue(UriFactory::isStandardPort(Scheme::HTTPS, '', null));
    }
}
