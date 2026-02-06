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
use Valkyrja\Http\Message\Uri\Data\HostPortAccumulator;
use Valkyrja\Http\Message\Uri\Enum\Scheme;
use Valkyrja\Http\Message\Uri\Factory\MarshalUriFactory;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class MarshalUriFactoryTest extends TestCase
{
    protected const string URI           = 'www.example.com';
    protected const string URI_HTTPS     = 'https://' . self::URI;
    protected const string URI_HTTP      = 'http://' . self::URI;
    protected const string URI_EMPTY     = '//' . self::URI;
    protected const string URI_ALL_PARTS = 'https://username:password@example.com:9090/path?arg=value#anchor';

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

        $uri = MarshalUriFactory::marshalUriFromServer($server, $headers);

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

        MarshalUriFactory::marshalHostAndPortFromHeaders($blankAccumulator, [], []);

        self::assertEmpty($blankAccumulator->host);
        self::assertNull($blankAccumulator->port ?? null);
    }

    public function testMarshalHostAndPortFromHeaders(): void
    {
        $host = 'https://www.host.com';
        $port = 70;

        $hostHeaderAccumulator = new HostPortAccumulator();

        MarshalUriFactory::marshalHostAndPortFromHeaders($hostHeaderAccumulator, [], ['host' => new Header('Host', "$host:$port")]);

        self::assertSame($host, $hostHeaderAccumulator->host);
        self::assertSame($port, $hostHeaderAccumulator->port);
    }

    public function testServerHostAndPortAccumulator(): void
    {
        $host = 'https://www.host.com';
        $port = 70;

        $serverAccumulator = new HostPortAccumulator();

        MarshalUriFactory::marshalHostAndPortFromHeaders(
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

        MarshalUriFactory::marshalHostAndPortFromHeaders(
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

        MarshalUriFactory::marshalHostAndPortFromHeaders(
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
        $unencodedUrl = MarshalUriFactory::marshalRequestUri([
            'IIS_WasUrlRewritten' => '1',
            'UNENCODED_URL'       => $unencodedUrlExpected = '/unencodedUrl',
        ]);

        self::assertSame($unencodedUrlExpected, $unencodedUrl);

        $httpXRewriteUrl = MarshalUriFactory::marshalRequestUri([
            'HTTP_X_REWRITE_URL' => $httpXRewriteUrlExpected = '/httpXRewriteUrl',
        ]);

        self::assertSame($httpXRewriteUrlExpected, $httpXRewriteUrl);

        $httpXOriginalUrl = MarshalUriFactory::marshalRequestUri([
            'HTTP_X_ORIGINAL_URL' => $httpXOriginalUrlExpected = '/httpXOriginalUrl',
        ]);

        self::assertSame($httpXOriginalUrlExpected, $httpXOriginalUrl);

        $requestUri = MarshalUriFactory::marshalRequestUri([
            'REQUEST_URI' => $requestUriExpected = '/requestUri',
        ]);

        self::assertSame($requestUriExpected, $requestUri);

        $origPathInfo = MarshalUriFactory::marshalRequestUri([
            'ORIG_PATH_INFO' => $origPathInfoExpected = '/origPathInfo',
        ]);

        self::assertSame($origPathInfoExpected, $origPathInfo);

        $origPathInfoEmpty = MarshalUriFactory::marshalRequestUri([
            'ORIG_PATH_INFO' => '',
        ]);

        self::assertSame('/', $origPathInfoEmpty);

        $noMatchedServerParams = MarshalUriFactory::marshalRequestUri([]);

        self::assertSame('/', $noMatchedServerParams);
    }

    public function testStripQueryString(): void
    {
        $pathString      = MarshalUriFactory::stripQueryString($path = '/path');
        $pathStringWithQ = MarshalUriFactory::stripQueryString('/path?query=string');

        self::assertSame($path, $pathString);
        self::assertSame($path, $pathStringWithQ);
    }

    public function testGetHeader(): void
    {
        $headers = [
            'array'  => new Header('Array', 'test1, test2'),
            'string' => new Header('String', 'string'),
        ];

        self::assertSame('string', MarshalUriFactory::getHeader('String', $headers));
        self::assertSame('test1, test2', MarshalUriFactory::getHeader('Array', $headers));
    }

    public function testGetHeaderWithDefault(): void
    {
        $headers = [];

        self::assertSame('', MarshalUriFactory::getHeader('NonExistent', $headers));
        self::assertSame('default', MarshalUriFactory::getHeader('NonExistent', $headers, 'default'));
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

        $uri = MarshalUriFactory::marshalUriFromServer($server, $headers);

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

        $uri = MarshalUriFactory::marshalUriFromServer($server, $headers);

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

        $uri = MarshalUriFactory::marshalUriFromServer($server, $headers);

        self::assertSame('key=value', $uri->getQuery());
    }

    public function testMarshalRequestUriWithFullUrl(): void
    {
        $result = MarshalUriFactory::marshalRequestUri([
            'REQUEST_URI' => 'http://example.com/path',
        ]);

        self::assertSame('/path', $result);
    }

    public function testMarshalUriFromServerWithEmptyHost(): void
    {
        $server  = [
            'REQUEST_URI' => '/path',
        ];
        $headers = [];

        $uri = MarshalUriFactory::marshalUriFromServer($server, $headers);

        // With no host information, host should be empty
        self::assertSame('', $uri->getHost());
        self::assertNull($uri->getPort());
        self::assertSame('/path', $uri->getPath());
    }

    public function testMarshalHostAndPortFromHeadersWithHostNoPort(): void
    {
        $accumulator = new HostPortAccumulator();

        MarshalUriFactory::marshalHostAndPortFromHeaders(
            $accumulator,
            [],
            ['host' => new Header('Host', 'example.com')]
        );

        self::assertSame('example.com', $accumulator->host);
        self::assertNull($accumulator->port);
    }

    public function testMarshalHostAndPortFromHeadersWithServerNameNoPort(): void
    {
        $accumulator = new HostPortAccumulator();

        MarshalUriFactory::marshalHostAndPortFromHeaders(
            $accumulator,
            ['SERVER_NAME' => 'example.com'],
            []
        );

        self::assertSame('example.com', $accumulator->host);
        self::assertNull($accumulator->port);
    }

    public function testMarshalUriFromServerWithNoQueryString(): void
    {
        $server = [
            'SERVER_NAME' => 'example.com',
            'REQUEST_URI' => '/path',
        ];
        $headers = [];

        $uri = MarshalUriFactory::marshalUriFromServer($server, $headers);

        self::assertSame('', $uri->getQuery());
    }

    public function testMarshalUriFromServerWithNoFragment(): void
    {
        $server = [
            'SERVER_NAME' => 'example.com',
            'REQUEST_URI' => '/path',
        ];
        $headers = [];

        $uri = MarshalUriFactory::marshalUriFromServer($server, $headers);

        self::assertSame('', $uri->getFragment());
    }
}
