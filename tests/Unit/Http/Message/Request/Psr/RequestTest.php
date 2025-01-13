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

namespace Valkyrja\Tests\Unit\Http\Message\Request\Psr;

use Valkyrja\Http\Message\Enum\ProtocolVersion;
use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Message\Request\Psr\Request as PsrRequest;
use Valkyrja\Http\Message\Request\Request;
use Valkyrja\Http\Message\Stream\Psr\Stream as PsrStream;
use Valkyrja\Http\Message\Stream\Stream;
use Valkyrja\Http\Message\Uri\Psr\Uri as PsrUri;
use Valkyrja\Http\Message\Uri\Uri;
use Valkyrja\Tests\Unit\TestCase;

class RequestTest extends TestCase
{
    public function testProtocolVersion(): void
    {
        $request    = new Request();
        $psrRequest = new PsrRequest($request);

        $protocolVersion = ProtocolVersion::V3;

        $psrRequest2 = $psrRequest->withProtocolVersion($protocolVersion->value);

        self::assertNotSame($psrRequest, $psrRequest2);
        self::assertSame($request->getProtocolVersion()->value, $psrRequest->getProtocolVersion());
        self::assertSame($protocolVersion->value, $psrRequest2->getProtocolVersion());
    }

    public function testHeaders(): void
    {
        $request    = new Request(headers: ['Test' => [$value = 'test']]);
        $psrRequest = new PsrRequest($request);

        $psrRequest2 = $psrRequest->withHeader('Cheese', 'foo');
        $psrRequest3 = $psrRequest->withAddedHeader('Test', 'bar');
        $psrRequest4 = $psrRequest2->withoutHeader('Test');

        self::assertNotSame($psrRequest, $psrRequest2);
        self::assertNotSame($psrRequest, $psrRequest3);
        self::assertNotSame($psrRequest2, $psrRequest4);
        self::assertSame($request->getHeaders(), $psrRequest->getHeaders());
        self::assertSame(['Test' => ['test'], 'Cheese' => ['foo']], $psrRequest2->getHeaders());
        self::assertSame(['Test' => ['test', 'bar']], $psrRequest3->getHeaders());
        self::assertSame(['Cheese' => ['foo']], $psrRequest4->getHeaders());
        self::assertTrue($psrRequest->hasHeader('Test'));
        self::assertSame([$value], $psrRequest->getHeader('Test'));
        self::assertSame($value, $psrRequest->getHeaderLine('Test'));
    }

    public function testBody(): void
    {
        $body = new Stream();
        $body->write($value = 'test');
        $body->rewind();

        $request    = new Request(body: $body);
        $psrRequest = new PsrRequest($request);

        $body2 = new Stream();
        $body2->write($value2 = 'tickle');
        $body2->rewind();

        $psrBody = new PsrStream($body2);

        $psrRequest2 = $psrRequest->withBody($psrBody);

        self::assertNotSame($psrRequest, $psrRequest2);
        self::assertSame($value, $psrRequest->getBody()->getContents());
        self::assertSame($value2, $psrRequest2->getBody()->getContents());
    }

    public function testRequestTarget(): void
    {
        $uri = new Uri(path: $value = '/path');

        $request    = new Request(uri: $uri);
        $psrRequest = new PsrRequest($request);

        $psrRequest2 = $psrRequest->withRequestTarget($value2 = 'new-path');

        self::assertNotSame($psrRequest, $psrRequest2);
        self::assertSame($value, $psrRequest->getRequestTarget());
        self::assertSame($value2, $psrRequest2->getRequestTarget());
    }

    public function testMethod(): void
    {
        $request    = new Request(method: $value = RequestMethod::CONNECT);
        $psrRequest = new PsrRequest($request);

        $psrRequest2 = $psrRequest->withMethod($value2 = RequestMethod::POST->value);

        self::assertNotSame($psrRequest, $psrRequest2);
        self::assertSame($value->value, $psrRequest->getMethod());
        self::assertSame($value2, $psrRequest2->getMethod());
    }

    public function testUri(): void
    {
        $uri = new Uri(host: $value = 'www.example.com');

        $request    = new Request(uri: $uri);
        $psrRequest = new PsrRequest($request);

        $uri2 = new Uri(host: $value2 = 'localhost');

        $psrUri = new PsrUri($uri2);

        $psrRequest2 = $psrRequest->withUri($psrUri);

        self::assertNotSame($psrRequest, $psrRequest2);
        self::assertSame($value, $psrRequest->getUri()->getHost());
        self::assertSame($value2, $psrRequest2->getUri()->getHost());
    }
}
