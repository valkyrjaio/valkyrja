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

use Valkyrja\Http\Message\Header\Header;
use Valkyrja\Http\Message\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Http\Message\Uri\Data\HostPortAccumulator;
use Valkyrja\Http\Message\Uri\Enum\Scheme;
use Valkyrja\Http\Message\Uri\Factory\UriFactory;
use Valkyrja\Http\Message\Uri\Psr\Uri as PsrUri;
use Valkyrja\Http\Message\Uri\Uri;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class UriFactoryTest extends TestCase
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

    public function testMarshalUriFromServer(): void
    {
        $host     = 'https://www.host.com';
        $port     = 70;
        $path     = '/path';
        $query    = 'query=test';
        $fragment = 'fragment';
        $server   = [
            'HTTPS'        => 'on',
            'SERVER_NAME'  => $host,
            'SERVER_PORT'  => (string) $port,
            'REQUEST_URI'  => "$path#$fragment",
            'QUERY_STRING' => $query,
        ];
        $headers  = [];

        $uri = UriFactory::marshalUriFromServer($server, $headers);

        self::assertSame(Scheme::HTTPS, $uri->getScheme());
        self::assertSame($host, $uri->getHost());
        self::assertSame($port, $uri->getPort());
        self::assertSame($path, $uri->getPath());
        self::assertSame($query, $uri->getQuery());
        self::assertSame($fragment, $uri->getFragment());
    }

    public function testMarshalEmptyServerAndHeaders(): void
    {
        $blankAccumulator = new HostPortAccumulator();

        UriFactory::marshalHostAndPortFromHeaders($blankAccumulator, [], []);

        self::assertEmpty($blankAccumulator->host);
        self::assertNull($blankAccumulator->port ?? null);
    }

    public function testMarshalHostAndPortFromHeaders(): void
    {
        $host = 'https://www.host.com';
        $port = 70;

        $hostHeaderAccumulator = new HostPortAccumulator();

        UriFactory::marshalHostAndPortFromHeaders($hostHeaderAccumulator, [], ['host' => new Header('Host', "$host:$port")]);

        self::assertSame($host, $hostHeaderAccumulator->host);
        self::assertSame($port, $hostHeaderAccumulator->port);
    }

    public function testServerHostAndPortAccumulator(): void
    {
        $host = 'https://www.host.com';
        $port = 70;

        $serverAccumulator = new HostPortAccumulator();

        UriFactory::marshalHostAndPortFromHeaders(
            $serverAccumulator,
            [
                'SERVER_NAME' => $host,
                'SERVER_PORT' => (string) $port,
            ],
            []
        );

        self::assertSame($host, $serverAccumulator->host);
        self::assertSame($port, $serverAccumulator->port);
    }

    public function testIp6FallbackAccumulator(): void
    {
        $port = 70;

        $ip6FallbackAccumulator = new HostPortAccumulator();

        UriFactory::marshalHostAndPortFromHeaders(
            $ip6FallbackAccumulator,
            [
                'SERVER_NAME' => '[FE80::0202:B3FF:FE1E:8329]',
                'SERVER_PORT' => (string) $port,
                'SERVER_ADDR' => 'FE80::0202:B3FF:FE1E:8329',
            ],
            []
        );

        self::assertSame('[FE80::0202:B3FF:FE1E:8329]', $ip6FallbackAccumulator->host);
        self::assertSame($port, $ip6FallbackAccumulator->port);
    }

    public function testIp6FallbackAccumulatorNullPort(): void
    {
        $ip6FallbackAccumulatorNullPort = new HostPortAccumulator();

        UriFactory::marshalHostAndPortFromHeaders(
            $ip6FallbackAccumulatorNullPort,
            [
                'SERVER_NAME' => '[FE80::0202:B3FF:FE1E:8329]',
                'SERVER_PORT' => '90',
                'SERVER_ADDR' => 'FE80::0202:B3FF:FE1E:8329:90',
            ],
            []
        );

        self::assertSame('[FE80::0202:B3FF:FE1E:8329:90]', $ip6FallbackAccumulatorNullPort->host);
        self::assertNull($ip6FallbackAccumulatorNullPort->port);
    }

    public function testMarshalRequestUri(): void
    {
        $unencodedUrl = UriFactory::marshalRequestUri([
            'IIS_WasUrlRewritten' => '1',
            'UNENCODED_URL'       => $unencodedUrlExpected = '/unencodedUrl',
        ]);

        self::assertSame($unencodedUrlExpected, $unencodedUrl);

        $httpXRewriteUrl = UriFactory::marshalRequestUri([
            'HTTP_X_REWRITE_URL' => $httpXRewriteUrlExpected = '/httpXRewriteUrl',
        ]);

        self::assertSame($httpXRewriteUrlExpected, $httpXRewriteUrl);

        $httpXOriginalUrl = UriFactory::marshalRequestUri([
            'HTTP_X_ORIGINAL_URL' => $httpXOriginalUrlExpected = '/httpXOriginalUrl',
        ]);

        self::assertSame($httpXOriginalUrlExpected, $httpXOriginalUrl);

        $requestUri = UriFactory::marshalRequestUri([
            'REQUEST_URI' => $requestUriExpected = '/requestUri',
        ]);

        self::assertSame($requestUriExpected, $requestUri);

        $origPathInfo = UriFactory::marshalRequestUri([
            'ORIG_PATH_INFO' => $origPathInfoExpected = '/origPathInfo',
        ]);

        self::assertSame($origPathInfoExpected, $origPathInfo);

        $origPathInfoEmpty = UriFactory::marshalRequestUri([
            'ORIG_PATH_INFO' => '',
        ]);

        self::assertSame('/', $origPathInfoEmpty);

        $noMatchedServerParams = UriFactory::marshalRequestUri([]);

        self::assertSame('/', $noMatchedServerParams);
    }

    public function testStripQueryString(): void
    {
        $pathString      = UriFactory::stripQueryString($path = '/path');
        $pathStringWithQ = UriFactory::stripQueryString('/path?query=string');

        self::assertSame($path, $pathString);
        self::assertSame($path, $pathStringWithQ);
    }

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

        $fromPsr = UriFactory::fromPsr($psr);

        self::assertSame($scheme, $fromPsr->getScheme());
        self::assertSame($username, $fromPsr->getUsername());
        self::assertSame($password, $fromPsr->getPassword());
        self::assertSame($host, $fromPsr->getHost());
        self::assertSame($port, $fromPsr->getPort());
        self::assertSame($path, $fromPsr->getPath());
        self::assertSame($query, $fromPsr->getQuery());
        self::assertSame($fragment, $fromPsr->getFragment());
    }

    public function testGetHeader(): void
    {
        $headers = [
            'array'  => new Header('Array', 'test1, test2'),
            'string' => new Header('String', 'string'),
        ];

        self::assertSame('string', UriFactory::getHeader('String', $headers));
        self::assertSame('test1, test2', UriFactory::getHeader('Array', $headers));
    }

    public function testGetHeaderWithDefault(): void
    {
        $headers = [];

        self::assertSame('', UriFactory::getHeader('NonExistent', $headers));
        self::assertSame('default', UriFactory::getHeader('NonExistent', $headers, 'default'));
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
        $this->expectException(\Valkyrja\Http\Message\Uri\Throwable\Exception\InvalidPortException::class);

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
        $this->expectException(\Valkyrja\Http\Message\Uri\Throwable\Exception\InvalidPathException::class);

        UriFactory::validatePath('/path?query');
    }

    public function testValidatePathWithFragment(): void
    {
        $this->expectException(\Valkyrja\Http\Message\Uri\Throwable\Exception\InvalidPathException::class);

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
        $this->expectException(\Valkyrja\Http\Message\Uri\Throwable\Exception\InvalidQueryException::class);

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
        $uriWithScheme = new Uri(scheme: Scheme::HTTPS);
        $uriWithoutScheme = new Uri();

        self::assertSame('https:', UriFactory::getSchemeStringPart($uriWithScheme));
        self::assertSame('', UriFactory::getSchemeStringPart($uriWithoutScheme));
    }

    public function testGetAuthorityStringPart(): void
    {
        $uriWithAuthority = new Uri(host: 'example.com');
        $uriWithoutAuthority = new Uri();

        self::assertSame('//example.com', UriFactory::getAuthorityStringPart($uriWithAuthority));
        self::assertSame('', UriFactory::getAuthorityStringPart($uriWithoutAuthority));
    }

    public function testGetPathStringPart(): void
    {
        $uriWithPath = new Uri(path: '/path');
        $uriWithPathNoSlash = new Uri(path: 'path');
        $uriWithoutPath = new Uri();

        self::assertSame('/path', UriFactory::getPathStringPart($uriWithPath));
        self::assertSame('/path', UriFactory::getPathStringPart($uriWithPathNoSlash));
        self::assertSame('', UriFactory::getPathStringPart($uriWithoutPath));
    }

    public function testGetQueryStringPart(): void
    {
        $uriWithQuery = new Uri(query: 'key=value');
        $uriWithoutQuery = new Uri();

        self::assertSame('?key=value', UriFactory::getQueryStringPart($uriWithQuery));
        self::assertSame('', UriFactory::getQueryStringPart($uriWithoutQuery));
    }

    public function testGetFragmentStringPart(): void
    {
        $uriWithFragment = new Uri(fragment: 'section');
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

    public function testFromPsrWithoutPassword(): void
    {
        $uri = new Uri(
            scheme: Scheme::HTTPS,
            username: 'username',
            host: 'host',
            path: '/path',
        );

        $psr = new PsrUri($uri);

        $fromPsr = UriFactory::fromPsr($psr);

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

        $fromPsr = UriFactory::fromPsr($psr);

        self::assertSame('', $fromPsr->getUsername());
        self::assertSame('', $fromPsr->getPassword());
    }

    public function testMarshalUriFromServerWithXForwardedProto(): void
    {
        $server = [
            'SERVER_NAME' => 'example.com',
            'SERVER_PORT' => '80',
            'REQUEST_URI' => '/path',
        ];
        $headers = [
            'x-forwarded-proto' => new Header('X-Forwarded-Proto', 'https'),
        ];

        $uri = UriFactory::marshalUriFromServer($server, $headers);

        self::assertSame(Scheme::HTTPS, $uri->getScheme());
    }

    public function testMarshalUriFromServerWithHttpsOff(): void
    {
        $server = [
            'HTTPS'       => 'off',
            'SERVER_NAME' => 'example.com',
            'SERVER_PORT' => '80',
            'REQUEST_URI' => '/path',
        ];
        $headers = [];

        $uri = UriFactory::marshalUriFromServer($server, $headers);

        self::assertSame(Scheme::HTTP, $uri->getScheme());
    }

    public function testMarshalUriFromServerWithQueryStringLeadingQuestionMark(): void
    {
        $server = [
            'SERVER_NAME'  => 'example.com',
            'REQUEST_URI'  => '/path',
            'QUERY_STRING' => '?key=value',
        ];
        $headers = [];

        $uri = UriFactory::marshalUriFromServer($server, $headers);

        self::assertSame('key=value', $uri->getQuery());
    }

    public function testMarshalRequestUriWithFullUrl(): void
    {
        $result = UriFactory::marshalRequestUri([
            'REQUEST_URI' => 'http://example.com/path',
        ]);

        self::assertSame('/path', $result);
    }
}
