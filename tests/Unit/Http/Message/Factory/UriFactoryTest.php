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

namespace Valkyrja\Tests\Unit\Http\Message\Factory;

use Valkyrja\Http\Message\Factory\UriFactory;
use Valkyrja\Http\Message\Uri\Data\HostPortAccumulator;
use Valkyrja\Http\Message\Uri\Enum\Scheme;
use Valkyrja\Http\Message\Uri\Psr\Uri as PsrUri;
use Valkyrja\Http\Message\Uri\Uri;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class UriFactoryTest extends TestCase
{
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

        UriFactory::marshalHostAndPortFromHeaders($hostHeaderAccumulator, [], ['host' => "$host:$port"]);

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
            'Array'  => ['test1', 'test2'],
            'String' => 'string',
        ];

        self::assertSame('string', UriFactory::getHeader('String', $headers));
        self::assertSame('test1, test2', UriFactory::getHeader('Array', $headers));
    }
}
