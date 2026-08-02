<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Functional\Http\Message\Request;

use PHPUnit\Framework\Attributes\DataProvider;
use Valkyrja\Http\Message\Constant\HeaderName;
use Valkyrja\Http\Message\Enum\ProtocolVersion;
use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Message\File\Collection\Contract\UploadedFileCollectionContract;
use Valkyrja\Http\Message\File\Contract\UploadedFileContract;
use Valkyrja\Http\Message\File\Enum\UploadError;
use Valkyrja\Http\Message\Header\Collection\HeaderCollection;
use Valkyrja\Http\Message\Header\Header;
use Valkyrja\Http\Message\Param\Contract\ParamCollectionContract;
use Valkyrja\Http\Message\Request\Factory\RequestFactory;
use Valkyrja\Http\Message\Request\ServerRequest;
use Valkyrja\Http\Message\Stream\Stream;
use Valkyrja\Http\Message\Uri\Enum\Scheme;
use Valkyrja\Http\Message\Uri\Uri;
use Valkyrja\Tests\Functional\Abstract\TestCase;

use function array_intersect;
use function array_keys;
use function array_values;

/**
 * Message-mapping fidelity for an incoming HTTP request.
 *
 * Asserts that raw request inputs — the $_SERVER superglobal, query, parsed
 * body, cookies, and uploaded files — land on the framework's own
 * ServerRequest object exactly as supplied, independent of routing.
 */
final class RequestMappingTest extends TestCase
{
    /**
     * Every method the RequestMethod enum defines.
     *
     * @return array<string, array{RequestMethod}>
     */
    public static function provideRequestMethods(): array
    {
        $methods = [];

        foreach (RequestMethod::cases() as $case) {
            $methods[$case->value] = [$case];
        }

        return $methods;
    }

    /**
     * @return array<string, array{string, ProtocolVersion}>
     */
    public static function provideProtocolVersions(): array
    {
        return [
            'bare 1.0'     => ['1.0', ProtocolVersion::V1],
            'bare 1.1'     => ['1.1', ProtocolVersion::V1_1],
            'bare 2'       => ['2', ProtocolVersion::V2],
            'bare 3'       => ['3', ProtocolVersion::V3],
            'prefixed 1.0' => ['HTTP/1.0', ProtocolVersion::V1],
            'prefixed 1.1' => ['HTTP/1.1', ProtocolVersion::V1_1],
            'prefixed 2'   => ['HTTP/2', ProtocolVersion::V2],
            'prefixed 3'   => ['HTTP/3', ProtocolVersion::V3],
        ];
    }

    /**
     * @return array<string, array{array<string, string>, string}>
     */
    public static function provideRequestTargets(): array
    {
        return [
            'plain path'      => [['REQUEST_URI' => '/users/42'], '/users/42'],
            'path with query' => [
                ['REQUEST_URI' => '/users/42?page=2', 'QUERY_STRING' => 'page=2'],
                '/users/42?page=2',
            ],
            'root path'       => [['REQUEST_URI' => '/'], '/'],
            'no request uri'  => [[], '/'],
            'x rewrite url'   => [['HTTP_X_REWRITE_URL' => '/rewritten'], '/rewritten'],
            'x original url'  => [['HTTP_X_ORIGINAL_URL' => '/original'], '/original'],
            'unencoded url'   => [
                ['IIS_WasUrlRewritten' => '1', 'UNENCODED_URL' => '/unencoded'],
                '/unencoded',
            ],
            'orig path info'  => [['ORIG_PATH_INFO' => '/orig'], '/orig'],
            'absolute uri'    => [['REQUEST_URI' => 'https://example.com/absolute'], '/absolute'],
        ];
    }

    /**
     * @return array<string, array{array<string, string>, string, int}>
     */
    public static function provideIpv6HostsAndPorts(): array
    {
        return [
            'last address digit mistaken for a port' => [
                ['SERVER_NAME' => '[fe80::1]', 'SERVER_ADDR' => 'fe80::1', 'SERVER_PORT' => '1', 'HTTPS' => 'on'],
                '[fe80::1]',
                0,
            ],
            'explicit port kept'                     => [
                ['SERVER_NAME' => '[fe80::1]', 'SERVER_ADDR' => 'fe80::1', 'SERVER_PORT' => '8080', 'HTTPS' => 'on'],
                '[fe80::1]',
                8080,
            ],
            'absent port falls back to 80'           => [
                ['SERVER_NAME' => '[fe80::1]', 'SERVER_ADDR' => 'fe80::1', 'HTTPS' => 'on'],
                '[fe80::1]',
                80,
            ],
            'server name is not ipv6 shaped'         => [
                ['SERVER_NAME' => 'plain.test', 'SERVER_ADDR' => 'fe80::1', 'SERVER_PORT' => '8080', 'HTTPS' => 'on'],
                'plain.test',
                8080,
            ],
            'ipv6 server name without an address'    => [
                ['SERVER_NAME' => '[fe80::1]', 'SERVER_PORT' => '8080', 'HTTPS' => 'on'],
                '[fe80::1]',
                8080,
            ],
        ];
    }

    /**
     * Every request method spelled in $_SERVER maps onto the enum case.
     */
    #[DataProvider('provideRequestMethods')]
    public function testRequestMethodMapsFromServer(RequestMethod $method): void
    {
        $request = RequestFactory::fromGlobals(server: ['REQUEST_METHOD' => $method->value]);

        self::assertSame(expected: $method, actual: $request->getMethod());
        self::assertSame(expected: $method->value, actual: $request->getMethod()->value);
    }

    /**
     * The method survives a withMethod() round-trip without touching the original.
     */
    #[DataProvider('provideRequestMethods')]
    public function testRequestMethodRoundTrips(RequestMethod $method): void
    {
        $request = new ServerRequest();
        $new     = $request->withMethod($method);

        self::assertSame(expected: RequestMethod::GET, actual: $request->getMethod());
        self::assertSame(expected: $method, actual: $new->getMethod());
    }

    #[DataProvider('provideProtocolVersions')]
    public function testProtocolVersionMapsFromServer(string $serverProtocol, ProtocolVersion $expected): void
    {
        $request = RequestFactory::fromGlobals(server: ['SERVER_PROTOCOL' => $serverProtocol]);

        self::assertSame(expected: $expected, actual: $request->getProtocolVersion());
    }

    /**
     * @param array<string, string> $server
     */
    #[DataProvider('provideRequestTargets')]
    public function testRequestTargetMapsFromServer(array $server, string $expected): void
    {
        $request = RequestFactory::fromGlobals(server: $server);

        self::assertSame(expected: $expected, actual: $request->getRequestTarget());
    }

    /**
     * A fully populated $_SERVER maps onto every URI component.
     */
    public function testUriMapsFromServer(): void
    {
        $request = RequestFactory::fromGlobals(
            server: [
                'REQUEST_METHOD' => RequestMethod::POST->value,
                'REQUEST_URI'    => '/users/42/edit?page=2&sort=name',
                'QUERY_STRING'   => 'page=2&sort=name',
                'HTTPS'          => 'on',
                'HTTP_HOST'      => 'example.com:8443',
            ]
        );

        $uri = $request->getUri();

        self::assertSame(expected: Scheme::HTTPS, actual: $uri->getScheme());
        self::assertTrue($uri->isSecure());
        self::assertSame(expected: 'example.com', actual: $uri->getHost());
        self::assertSame(expected: 8443, actual: $uri->getPort());
        self::assertSame(expected: '/users/42/edit', actual: $uri->getPath());
        self::assertSame(expected: 'page=2&sort=name', actual: $uri->getQuery());
        self::assertSame(expected: '', actual: $uri->getFragment());
        self::assertSame(
            expected: 'https://example.com:8443/users/42/edit?page=2&sort=name',
            actual: $uri->__toString()
        );
        self::assertSame(expected: '/users/42/edit?page=2&sort=name', actual: $request->getRequestTarget());
    }

    /**
     * The scheme falls back to http, and the host comes from SERVER_NAME/SERVER_PORT
     * when no Host header is present.
     */
    public function testUriFallsBackToServerNameAndPort(): void
    {
        $request = RequestFactory::fromGlobals(
            server: [
                'SERVER_NAME' => 'internal.test',
                'SERVER_PORT' => '8080',
                'REQUEST_URI' => '/health',
            ]
        );

        $uri = $request->getUri();

        self::assertSame(expected: Scheme::HTTP, actual: $uri->getScheme());
        self::assertFalse($uri->isSecure());
        self::assertSame(expected: 'internal.test', actual: $uri->getHost());
        self::assertSame(expected: 8080, actual: $uri->getPort());
    }

    /**
     * A fragment is carried through when the request target has no query string.
     */
    public function testUriFragmentMapsFromServer(): void
    {
        $request = RequestFactory::fromGlobals(server: ['REQUEST_URI' => '/docs#section']);

        self::assertSame(expected: '/docs', actual: $request->getUri()->getPath());
        self::assertSame(expected: 'section', actual: $request->getUri()->getFragment());
    }

    /**
     * A SERVER_NAME that looks like a bracketed IPv6 address is re-derived from
     * SERVER_ADDR, and the port is reinterpreted when the address' last digit was
     * mistaken for one (as Safari on Windows reports it).
     *
     * @param array<string, string> $server
     */
    #[DataProvider('provideIpv6HostsAndPorts')]
    public function testIpv6HostAndPortMapFromServer(array $server, string $expectedHost, int $expectedPort): void
    {
        $uri = RequestFactory::fromGlobals(server: $server)->getUri();

        self::assertSame(expected: $expectedHost, actual: $uri->getHost());
        self::assertSame(expected: $expectedPort, actual: $uri->getPort());
    }

    /**
     * The X-Forwarded-Proto header promotes the scheme to https.
     */
    public function testUriSchemeMapsFromForwardedProtoHeader(): void
    {
        $request = RequestFactory::fromGlobals(
            server: [
                'HTTP_X_FORWARDED_PROTO' => Scheme::HTTPS->value,
                'HTTP_HOST'              => 'example.com',
            ]
        );

        self::assertSame(expected: Scheme::HTTPS, actual: $request->getUri()->getScheme());
    }

    /**
     * Query params — including nested arrays — map onto the query collection verbatim.
     */
    public function testQueryParamsMapFromGlobals(): void
    {
        $request = RequestFactory::fromGlobals(
            query: [
                'page'   => '2',
                'sort'   => 'name',
                'filter' => ['status' => 'active', 'tags' => ['a', 'b']],
            ]
        );

        $query = $request->getQueryParams();

        self::assertTrue($query->has('page'));
        self::assertSame(expected: '2', actual: $query->get('page'));
        self::assertSame(expected: 'name', actual: $query->get('sort'));

        $filter = $query->get('filter');

        self::assertInstanceOf(expected: ParamCollectionContract::class, actual: $filter);
        self::assertSame(expected: 'active', actual: $filter->get('status'));

        $tags = $filter->get('tags');

        self::assertInstanceOf(expected: ParamCollectionContract::class, actual: $tags);
        self::assertSame(expected: 'a', actual: $tags->get(0));
        self::assertSame(expected: 'b', actual: $tags->get(1));

        // A missing query param reads back as an empty string, never null.
        self::assertSame(expected: '', actual: $query->get('missing'));
        self::assertFalse($query->has('missing'));
    }

    /**
     * The parsed body maps onto the parsed-body collection verbatim.
     */
    public function testParsedBodyMapsFromGlobals(): void
    {
        $request = RequestFactory::fromGlobals(
            body: [
                'title' => 'hello',
                'count' => '3',
                'meta'  => ['draft' => 'yes'],
            ]
        );

        $parsedBody = $request->getParsedBody();

        self::assertTrue($parsedBody->has('title'));
        self::assertSame(expected: 'hello', actual: $parsedBody->get('title'));
        self::assertSame(expected: '3', actual: $parsedBody->get('count'));

        $meta = $parsedBody->get('meta');

        self::assertInstanceOf(expected: ParamCollectionContract::class, actual: $meta);
        self::assertSame(expected: 'yes', actual: $meta->get('draft'));

        // A missing parsed-body param reads back as an empty string, never null.
        self::assertSame(expected: '', actual: $parsedBody->get('missing'));
        self::assertFalse($parsedBody->has('missing'));
    }

    /**
     * Server params are exposed exactly as supplied.
     */
    public function testServerParamsMapFromGlobals(): void
    {
        $server = [
            'REQUEST_METHOD'  => RequestMethod::PUT->value,
            'REQUEST_URI'     => '/resource',
            'SERVER_PROTOCOL' => 'HTTP/1.1',
        ];

        $request = RequestFactory::fromGlobals(server: $server);

        $serverParams = $request->getServerParams();

        self::assertSame(expected: RequestMethod::PUT->value, actual: $serverParams->get('REQUEST_METHOD'));
        self::assertSame(expected: '/resource', actual: $serverParams->get('REQUEST_URI'));
        self::assertSame(expected: 'HTTP/1.1', actual: $serverParams->get('SERVER_PROTOCOL'));
    }

    /**
     * HTTP_* and CONTENT_* server entries become normalized headers, and lookups
     * are case-insensitive.
     */
    public function testHeadersMapFromServerCaseInsensitively(): void
    {
        $request = RequestFactory::fromGlobals(
            server: [
                'HTTP_HOST'         => 'example.com',
                'HTTP_ACCEPT'       => 'text/html',
                'HTTP_X_CUSTOM_KEY' => 'custom-value',
                'CONTENT_TYPE'      => 'application/json',
                'CONTENT_LENGTH'    => '42',
                'NOT_A_HEADER'      => 'ignored',
            ]
        );

        $headers = $request->getHeaders();

        // Header names are normalized to lower case, in the order they appear in $_SERVER.
        // Only the marshalled names are compared: the environment may contribute an
        // extra `authorization` entry when apache_request_headers() is available.
        $expectedNames = ['host', 'accept', 'x-custom-key', 'content-type', 'content-length'];
        $actualNames   = array_intersect(array_keys($headers->getAll()), $expectedNames);

        self::assertSame(expected: $expectedNames, actual: array_values($actualNames));

        self::assertTrue($headers->has('Accept'));
        self::assertTrue($headers->has('ACCEPT'));
        self::assertTrue($headers->has('accept'));
        self::assertSame(expected: 'text/html', actual: $headers->getHeaderLine('AcCePt'));
        self::assertSame(expected: 'custom-value', actual: $headers->getHeaderLine('X-Custom-Key'));
        self::assertSame(expected: 'application/json', actual: $headers->getHeaderLine('Content-Type'));
        self::assertSame(expected: '42', actual: $headers->getHeaderLine('CONTENT-LENGTH'));

        self::assertFalse($headers->has('Not-A-Header'));
        self::assertSame(expected: '', actual: $headers->getHeaderLine('Not-A-Header'));
    }

    /**
     * A multi-value header exposes each value and joins them for the header line.
     */
    public function testMultiValueHeadersMapOntoRequest(): void
    {
        $request = new ServerRequest(
            headers: new HeaderCollection(
                new Header('Accept', 'text/html', 'application/xhtml+xml'),
                new Header('X-Trace', 'first')
            )
        );

        $accept = $request->getHeaders()->get('accept');

        self::assertSame(expected: 'Accept', actual: $accept->getName());
        self::assertSame(expected: 'accept', actual: $accept->getNormalizedName());
        self::assertSame(expected: ['text/html', 'application/xhtml+xml'], actual: $accept->getValues());
        self::assertSame(expected: 'text/html, application/xhtml+xml', actual: $accept->getHeaderLine());
        self::assertCount(expectedCount: 2, haystack: $accept);

        $added = $request->withHeaders(
            $request->getHeaders()->withAddedHeaders(new Header('ACCEPT', 'application/json'))
        );

        self::assertSame(
            expected: 'text/html, application/xhtml+xml, application/json',
            actual: $added->getHeaders()->getHeaderLine('accept')
        );

        $overridden = $request->withHeaders(
            $request->getHeaders()->withHeader(new Header('ACCEPT', 'text/plain'))
        );

        self::assertSame(expected: 'text/plain', actual: $overridden->getHeaders()->getHeaderLine('Accept'));
        self::assertSame(expected: 'first', actual: $overridden->getHeaders()->getHeaderLine('x-trace'));
    }

    /**
     * A comma-delimited raw header line splits into discrete values.
     */
    public function testHeaderFromRawValueSplitsOnCommas(): void
    {
        $header = Header::fromValue('X-Multi: a,b,c');

        self::assertSame(expected: 'X-Multi', actual: $header->getName());
        self::assertSame(expected: 'x-multi', actual: $header->getNormalizedName());
        self::assertSame(expected: 'a, b, c', actual: $header->getHeaderLine());
        self::assertSame(expected: 'X-Multi: a, b, c', actual: $header->__toString());
    }

    /**
     * Cookies are parsed out of the Cookie header when none are supplied directly.
     */
    public function testCookiesMapFromCookieHeader(): void
    {
        $request = RequestFactory::fromGlobals(
            server: ['HTTP_COOKIE' => 'sid=abc123; theme=dark']
        );

        $cookies = $request->getCookieParams();

        self::assertSame(expected: ['sid' => 'abc123', 'theme' => 'dark'], actual: $cookies->getAll());
        self::assertSame(expected: 'abc123', actual: $cookies->get('sid'));
        self::assertSame(expected: 'dark', actual: $cookies->get('theme'));
        self::assertSame(expected: 'sid=abc123; theme=dark', actual: $request->getHeaders()->getHeaderLine('Cookie'));
    }

    /**
     * Explicitly supplied cookies take precedence over the Cookie header.
     */
    public function testExplicitCookiesTakePrecedenceOverHeader(): void
    {
        $request = RequestFactory::fromGlobals(
            server: ['HTTP_COOKIE' => 'sid=from-header'],
            cookies: ['sid' => 'from-globals']
        );

        self::assertSame(expected: 'from-globals', actual: $request->getCookieParams()->get('sid'));
    }

    /**
     * A flat $_FILES spec maps onto an uploaded-file object.
     */
    public function testUploadedFilesMapFromFlatSpec(): void
    {
        $request = RequestFactory::fromGlobals(
            files: [
                'avatar' => [
                    'tmp_name' => '/tmp/php-upload-avatar',
                    'size'     => 1024,
                    'error'    => UploadError::OK->value,
                    'name'     => 'avatar.png',
                    'type'     => 'image/png',
                ],
            ]
        );

        $file = $request->getUploadedFiles()->get('avatar');

        self::assertInstanceOf(expected: UploadedFileContract::class, actual: $file);
        self::assertSame(expected: 'avatar.png', actual: $file->getClientFilename());
        self::assertSame(expected: 'image/png', actual: $file->getClientMediaType());
        self::assertSame(expected: 1024, actual: $file->getSize());
        self::assertSame(expected: UploadError::OK, actual: $file->getError());
        self::assertTrue($file->hasSize());
        self::assertTrue($file->hasClientFilename());
        self::assertTrue($file->hasClientMediaType());
    }

    /**
     * A nested $_FILES spec maps onto a nested uploaded-file collection.
     */
    public function testUploadedFilesMapFromNestedSpec(): void
    {
        $request = RequestFactory::fromGlobals(
            files: [
                'documents' => [
                    'tmp_name' => ['/tmp/one', '/tmp/two'],
                    'size'     => [10, 20],
                    'error'    => [UploadError::OK->value, UploadError::NO_FILE->value],
                    'name'     => ['one.txt', 'two.txt'],
                    'type'     => ['text/plain', 'text/plain'],
                ],
            ]
        );

        $documents = $request->getUploadedFiles()->get('documents');

        self::assertInstanceOf(expected: UploadedFileCollectionContract::class, actual: $documents);

        $first  = $documents->get(0);
        $second = $documents->get(1);

        self::assertInstanceOf(expected: UploadedFileContract::class, actual: $first);
        self::assertInstanceOf(expected: UploadedFileContract::class, actual: $second);
        self::assertSame(expected: 'one.txt', actual: $first->getClientFilename());
        self::assertSame(expected: 10, actual: $first->getSize());
        self::assertSame(expected: UploadError::OK, actual: $first->getError());
        self::assertSame(expected: 'two.txt', actual: $second->getClientFilename());
        self::assertSame(expected: 20, actual: $second->getSize());
        self::assertSame(expected: UploadError::NO_FILE, actual: $second->getError());
    }

    /**
     * Swapping the URI re-derives the Host header from it — unless the caller asks
     * to preserve an existing Host header, or the new URI carries no host at all.
     */
    public function testWithUriReDerivesTheHostHeader(): void
    {
        $request = new ServerRequest(
            uri: new Uri(host: 'original.test'),
            headers: new HeaderCollection(new Header(HeaderName::HOST, 'original.test'))
        );

        // A host without a port yields a bare host header.
        $swapped = $request->withUri(new Uri(host: 'new.test'));

        self::assertSame(expected: 'new.test', actual: $swapped->getHeaders()->getHeaderLine(HeaderName::HOST));

        // A host with a port yields host:port.
        $withPort = $request->withUri(new Uri(host: 'new.test', port: 9090));

        self::assertSame(expected: 'new.test:9090', actual: $withPort->getHeaders()->getHeaderLine(HeaderName::HOST));

        // preserveHost keeps the existing header even though the URI changed.
        $preserved = $request->withUri(new Uri(host: 'new.test'), preserveHost: true);

        self::assertSame(expected: 'original.test', actual: $preserved->getHeaders()->getHeaderLine(HeaderName::HOST));
        self::assertSame(expected: 'new.test', actual: $preserved->getUri()->getHost());

        // A URI with no host leaves the existing header alone.
        $hostless = $request->withUri(new Uri());

        self::assertSame(expected: 'original.test', actual: $hostless->getHeaders()->getHeaderLine(HeaderName::HOST));
        self::assertSame(expected: '', actual: $hostless->getUri()->getHost());

        // preserveHost with no header to preserve still derives one from the URI.
        $noHeader = new ServerRequest()->withUri(new Uri(host: 'new.test', port: 8080), preserveHost: true);

        self::assertSame(expected: 'new.test:8080', actual: $noHeader->getHeaders()->getHeaderLine(HeaderName::HOST));

        // The original is untouched throughout.
        self::assertSame(expected: 'original.test', actual: $request->getHeaders()->getHeaderLine(HeaderName::HOST));
    }

    /**
     * The body stream is exposed verbatim and rewinds for repeat reads.
     */
    public function testBodyContentsMapOntoRequest(): void
    {
        $body = new Stream();
        $body->write('{"title":"hello"}');
        $body->rewind();

        $request = new ServerRequest(body: $body);

        self::assertSame(expected: '{"title":"hello"}', actual: $request->getBody()->getContents());
        self::assertSame(expected: '{"title":"hello"}', actual: $request->getBody()->__toString());

        $replacement = new Stream();
        $replacement->write('replaced');
        $replacement->rewind();

        $new = $request->withBody($replacement);

        self::assertSame(expected: 'replaced', actual: $new->getBody()->__toString());
        self::assertSame(expected: '{"title":"hello"}', actual: $request->getBody()->__toString());
    }

    /**
     * An empty $_SERVER yields the documented defaults.
     */
    public function testDefaultsWhenNothingIsSupplied(): void
    {
        $request = RequestFactory::fromGlobals(
            server: [],
            query: [],
            body: [],
            cookies: [],
            files: []
        );

        self::assertSame(expected: RequestMethod::GET, actual: $request->getMethod());
        self::assertSame(expected: ProtocolVersion::V1_1, actual: $request->getProtocolVersion());
        self::assertSame(expected: '/', actual: $request->getRequestTarget());
        self::assertSame(expected: '', actual: $request->getUri()->getHost());
        self::assertSame(expected: [], actual: $request->getQueryParams()->getAll());
        self::assertSame(expected: [], actual: $request->getParsedBody()->getAll());
        self::assertSame(expected: [], actual: $request->getCookieParams()->getAll());
        self::assertSame(expected: [], actual: $request->getUploadedFiles()->getAll());
        self::assertSame(expected: [], actual: $request->getAttributes()->getAll());
    }

    /**
     * The X-Requested-With header drives the XHR flag.
     */
    public function testXmlHttpRequestFlagMapsFromHeader(): void
    {
        $xhr   = RequestFactory::fromGlobals(server: ['HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest']);
        $plain = RequestFactory::fromGlobals(server: []);

        self::assertTrue($xhr->isXmlHttpRequest());
        self::assertFalse($plain->isXmlHttpRequest());
    }
}
