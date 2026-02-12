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

namespace Valkyrja\Tests\Unit\Http\Message\Response\Psr;

use Valkyrja\Http\Message\Enum\ProtocolVersion;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Header\Collection\HeaderCollection;
use Valkyrja\Http\Message\Header\Header;
use Valkyrja\Http\Message\Response\Psr\Response as PsrResponse;
use Valkyrja\Http\Message\Response\Response;
use Valkyrja\Http\Message\Stream\Psr\Stream as PsrStream;
use Valkyrja\Http\Message\Stream\Stream;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class ResponseTest extends TestCase
{
    public function testProtocolVersion(): void
    {
        $response    = new Response();
        $psrResponse = new PsrResponse($response);

        $protocolVersion = ProtocolVersion::V3;

        $psrResponse2 = $psrResponse->withProtocolVersion($protocolVersion->value);

        self::assertNotSame($psrResponse, $psrResponse2);
        self::assertSame($response->getProtocolVersion()->value, $psrResponse->getProtocolVersion());
        self::assertSame($protocolVersion->value, $psrResponse2->getProtocolVersion());
    }

    public function testHeaders(): void
    {
        $response    = new Response(headers: new HeaderCollection(new Header('Test', $value = 'test')));
        $psrResponse = new PsrResponse($response);

        $psrResponse2 = $psrResponse->withHeader('Cheese', 'foo');
        $psrResponse3 = $psrResponse->withAddedHeader('Test', 'bar');
        $psrResponse4 = $psrResponse2->withoutHeader('Test');

        self::assertNotSame($psrResponse, $psrResponse2);
        self::assertNotSame($psrResponse, $psrResponse3);
        self::assertNotSame($psrResponse2, $psrResponse4);
        self::assertSame(['Test' => ['test']], $psrResponse->getHeaders());
        self::assertSame(['Test' => ['test'], 'Cheese' => ['foo']], $psrResponse2->getHeaders());
        self::assertSame(['Test' => ['test', 'bar']], $psrResponse3->getHeaders());
        self::assertSame(['Cheese' => ['foo']], $psrResponse4->getHeaders());
        self::assertTrue($psrResponse->hasHeader('Test'));
        self::assertSame([$value], $psrResponse->getHeader('Test'));
        self::assertSame([], $psrResponse->getHeader('Non-Existent'));
        self::assertSame($value, $psrResponse->getHeaderLine('Test'));
    }

    public function testHasHeaderReturnsFalseForEmptyName(): void
    {
        $psrResponse = new PsrResponse();

        self::assertFalse($psrResponse->hasHeader(''));
    }

    public function testGetHeaderReturnsEmptyArrayForEmptyName(): void
    {
        $psrResponse = new PsrResponse();

        self::assertSame([], $psrResponse->getHeader(''));
    }

    public function testGetHeaderLineReturnsEmptyStringForEmptyName(): void
    {
        $psrResponse = new PsrResponse();

        self::assertSame('', $psrResponse->getHeaderLine(''));
    }

    public function testWithoutHeaderReturnsCloneForEmptyName(): void
    {
        $response    = new Response(headers: new HeaderCollection(new Header('Test', 'value')));
        $psrResponse = new PsrResponse($response);

        $psrResponse2 = $psrResponse->withoutHeader('');

        self::assertNotSame($psrResponse, $psrResponse2);
        self::assertSame(['Test' => ['value']], $psrResponse2->getHeaders());
    }

    public function testBody(): void
    {
        $body = new Stream();
        $body->write($value = 'test');
        $body->rewind();

        $response    = new Response($body);
        $psrResponse = new PsrResponse($response);

        $body2 = new Stream();
        $body2->write($value2 = 'tickle');
        $body2->rewind();

        $psrBody = new PsrStream($body2);

        $psrResponse2 = $psrResponse->withBody($psrBody);

        self::assertNotSame($psrResponse, $psrResponse2);
        self::assertSame($value, $psrResponse->getBody()->getContents());
        self::assertSame($value2, $psrResponse2->getBody()->getContents());
    }

    public function testStatus(): void
    {
        $status = StatusCode::NO_CONTENT;

        $response    = new Response(statusCode: $status);
        $psrResponse = new PsrResponse($response);

        $status2 = StatusCode::FOUND;

        $psrResponse2 = $psrResponse->withStatus($status2->value, $value = 'pie');

        self::assertNotSame($psrResponse, $psrResponse2);
        self::assertSame($response->getStatusCode()->value, $psrResponse->getStatusCode());
        self::assertSame($response->getReasonPhrase(), $psrResponse->getReasonPhrase());
        self::assertSame($status2->value, $psrResponse2->getStatusCode());
        self::assertSame($value, $psrResponse2->getReasonPhrase());
    }
}
