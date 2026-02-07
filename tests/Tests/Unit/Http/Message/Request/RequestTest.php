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

namespace Valkyrja\Tests\Unit\Http\Message\Request;

use Valkyrja\Http\Message\Constant\HeaderName;
use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Message\Header\Header;
use Valkyrja\Http\Message\Request\Request;
use Valkyrja\Http\Message\Request\Throwable\Exception\InvalidRequestTargetException;
use Valkyrja\Http\Message\Uri\Factory\UriFactory;
use Valkyrja\Http\Message\Uri\Uri;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class RequestTest extends TestCase
{
    public function testRequestTarget(): void
    {
        $requestTarget = '/test';

        $request  = new Request();
        $request2 = new Request(uri: new Uri(path: '/path'));
        $request3 = new Request(uri: new Uri(path: '/path', query: 'arg=value'));
        $request4 = $request3->withRequestTarget($requestTarget);
        $request5 = new Request(uri: UriFactory::fromString('https://www.example.com/path?arg=value#fragment'));

        self::assertNotSame($request3, $request4);
        self::assertSame('/', $request->getRequestTarget());
        self::assertSame('/path', $request2->getRequestTarget());
        self::assertSame('/path?arg=value', $request3->getRequestTarget());
        self::assertSame($requestTarget, $request4->getRequestTarget());
        self::assertSame('/path?arg=value', $request5->getRequestTarget());
    }

    public function testInvalidRequestTarget(): void
    {
        $this->expectException(InvalidRequestTargetException::class);

        $request = new Request();
        $request->withRequestTarget('fire ');
    }

    public function testMethod(): void
    {
        $request   = new Request();
        $request2  = $request->withMethod(RequestMethod::GET);
        $request3  = $request->withMethod(RequestMethod::HEAD);
        $request4  = $request->withMethod(RequestMethod::POST);
        $request5  = $request->withMethod(RequestMethod::PATCH);
        $request6  = $request->withMethod(RequestMethod::PUT);
        $request7  = $request->withMethod(RequestMethod::OPTIONS);
        $request8  = $request->withMethod(RequestMethod::TRACE);
        $request9  = $request->withMethod(RequestMethod::DELETE);
        $request10 = $request->withMethod(RequestMethod::CONNECT);

        self::assertNotSame($request, $request2);
        self::assertNotSame($request, $request3);
        self::assertNotSame($request, $request4);
        self::assertNotSame($request, $request5);
        self::assertNotSame($request, $request6);
        self::assertNotSame($request, $request7);
        self::assertNotSame($request, $request8);
        self::assertNotSame($request, $request9);
        self::assertNotSame($request, $request10);

        self::assertSame(RequestMethod::GET, $request->getMethod());
        self::assertSame(RequestMethod::GET, $request2->getMethod());
        self::assertSame(RequestMethod::HEAD, $request3->getMethod());
        self::assertSame(RequestMethod::POST, $request4->getMethod());
        self::assertSame(RequestMethod::PATCH, $request5->getMethod());
        self::assertSame(RequestMethod::PUT, $request6->getMethod());
        self::assertSame(RequestMethod::OPTIONS, $request7->getMethod());
        self::assertSame(RequestMethod::TRACE, $request8->getMethod());
        self::assertSame(RequestMethod::DELETE, $request9->getMethod());
        self::assertSame(RequestMethod::CONNECT, $request10->getMethod());
    }

    public function testUri(): void
    {
        $uri   = 'https://www.example.com/path?arg=value#fragment';
        $uri2  = 'https://www.example.com:8080/path?arg=value#fragment';
        $host  = 'www.test.com';
        $host2 = 'www.example.com';
        $host3 = 'www.example.com:8080';
        $path  = '/path?arg=value#fragment';

        $request  = new Request();
        $request2 = new Request(headers: [new Header(HeaderName::HOST, $host)]);
        $request3 = new Request(
            uri: UriFactory::fromString($uri),
            headers: [new Header(HeaderName::HOST, $host)]
        );
        $request4 = $request->withUri(UriFactory::fromString($uri));
        $request5 = $request->withUri(UriFactory::fromString($path));
        $request6 = $request2->withUri(UriFactory::fromString($uri));
        $request7 = $request2->withUri(UriFactory::fromString($uri), true);
        $request8 = $request3->withUri(UriFactory::fromString($path));
        $request9 = $request->withUri(UriFactory::fromString($uri2));

        self::assertNotSame($request, $request4);
        self::assertNotSame($request, $request5);
        self::assertNotSame($request2, $request6);
        self::assertNotSame($request2, $request7);
        self::assertNotSame($request3, $request8);
        self::assertNotSame($request, $request9);
        self::assertSame('', (string) $request->getUri());
        self::assertSame('', (string) $request2->getUri());
        self::assertSame($uri, (string) $request3->getUri());
        self::assertSame($uri, (string) $request4->getUri());
        self::assertSame($path, (string) $request5->getUri());
        self::assertSame($uri, (string) $request6->getUri());
        self::assertSame($uri, (string) $request7->getUri());
        self::assertSame($path, (string) $request8->getUri());
        self::assertSame($uri2, (string) $request9->getUri());

        self::assertSame('', $request->getHeaderLine(HeaderName::HOST));
        self::assertSame($host, $request2->getHeaderLine(HeaderName::HOST));
        self::assertSame($host, $request3->getHeaderLine(HeaderName::HOST));
        self::assertSame($host2, $request4->getHeaderLine(HeaderName::HOST));
        self::assertSame('', $request5->getHeaderLine(HeaderName::HOST));
        self::assertSame($host2, $request6->getHeaderLine(HeaderName::HOST));
        self::assertSame($host, $request7->getHeaderLine(HeaderName::HOST));
        self::assertSame($host, $request8->getHeaderLine(HeaderName::HOST));
        self::assertSame($host3, $request9->getHeaderLine(HeaderName::HOST));
    }
}
