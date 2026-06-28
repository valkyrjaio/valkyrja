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

namespace Valkyrja\Tests\Unit\Http\Message\Response;

use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Valkyrja\Http\Message\Constant\HeaderName;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Header\Collection\HeaderCollection;
use Valkyrja\Http\Message\Header\ContentType;
use Valkyrja\Http\Message\Header\Value\Cookie;
use Valkyrja\Http\Message\Response\Response;
use Valkyrja\Http\Message\Stream\Stream;
use Valkyrja\Support\Time\Time;
use Valkyrja\Tests\Fixtures\Http\Message\Response\ResponseSendRecorderClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;

use function ob_get_clean;
use function ob_start;

#[RunTestsInSeparateProcesses]
final class ResponseTest extends TestCase
{
    public function testCreate(): void
    {
        $response  = Response::create();
        $response2 = Response::create('test', StatusCode::CREATED, HeaderCollection::fromArray([new ContentType('text/html')]));

        self::assertEmpty($response->getBody()->getContents());
        self::assertSame(StatusCode::OK, $response->getStatusCode());
        self::assertEmpty($response->getHeaders()->getAll());

        self::assertSame('test', $response2->getBody()->getContents());
        self::assertSame(StatusCode::CREATED, $response2->getStatusCode());

        self::assertSame('text/html', $response2->getHeaders()->getHeaderLine(HeaderName::CONTENT_TYPE));
    }

    public function testGetStatusCode(): void
    {
        $response  = Response::create();
        $response2 = Response::create('test', StatusCode::CREATED, HeaderCollection::fromArray([new ContentType('text/html')]));

        self::assertSame(StatusCode::OK, $response->getStatusCode());
        self::assertSame(StatusCode::CREATED, $response2->getStatusCode());
    }

    public function testWithStatus(): void
    {
        $response  = Response::create();
        $response2 = $response->withStatusCode(StatusCode::CREATED);

        self::assertNotSame($response, $response2);
        self::assertSame(StatusCode::OK, $response->getStatusCode());
        self::assertSame(StatusCode::CREATED, $response2->getStatusCode());
    }

    public function testGetReasonPhrase(): void
    {
        $response  = Response::create();
        $response2 = $response->withStatusCode(StatusCode::CREATED);
        $response3 = $response2->withReasonPhrase('pie');

        self::assertNotSame($response, $response2);
        self::assertNotSame($response2, $response3);
        self::assertSame(StatusCode::OK->asPhrase(), $response->getReasonPhrase());
        self::assertSame(StatusCode::CREATED->asPhrase(), $response2->getReasonPhrase());
        self::assertSame('pie', $response3->getReasonPhrase());
    }

    public function testWithCookie(): void
    {
        $cookie = new Cookie(name: 'foo', value: 'bar');

        $response  = Response::create();
        $response2 = $response->withCookie($cookie);

        self::assertNotSame($response, $response2);
        self::assertSame('foo=bar; path=/; httponly; samesite=lax', $response2->getHeaders()->getHeaderLine(HeaderName::SET_COOKIE));
    }

    public function testWithoutCookie(): void
    {
        Time::freeze(1734553175);

        $cookie        = new Cookie(name: 'foo', value: 'bar');
        $deletedCookie = $cookie->delete();

        $response  = Response::create();
        $response2 = $response->withoutCookie($cookie);

        self::assertNotSame($response, $response2);
        self::assertSame((string) $deletedCookie, $response2->getHeaders()->getHeaderLine(HeaderName::SET_COOKIE));

        Time::unfreeze();
    }

    public function testSendHttpLine(): void
    {
        $response = new ResponseSendRecorderClass(new Stream(), StatusCode::CREATED, HeaderCollection::fromArray([new ContentType('text/html')]));
        $response = $response->withStatusCode(StatusCode::CREATED)->withReasonPhrase('Created Phrase');

        $response->sendHttpLine();

        self::assertSame(['HTTP/1.1 201 Created Phrase', true], $response->sentHeaders);
        self::assertSame(201, $response->responseCode);
    }

    public function testSendHeaders(): void
    {
        $response = new ResponseSendRecorderClass(new Stream(), StatusCode::OK, HeaderCollection::fromArray([new ContentType('text/html')]));

        $response->sendHeaders();

        self::assertSame(['Content-Type: text/html', false], $response->sentHeaders);
    }

    public function testSendBody(): void
    {
        $stream = new Stream();
        $stream->write('test');
        $stream->rewind();

        $response = new ResponseSendRecorderClass($stream, StatusCode::OK, HeaderCollection::fromArray([new ContentType('text/html')]));

        self::assertSame('test', $stream->getContents());

        ob_start();
        $response->sendBody();
        $contents = ob_get_clean();

        self::assertSame('test', $contents);
        self::assertSame('test', $stream->getContents());
        self::assertTrue($response->obFlushCalled);
        self::assertTrue($response->flushCalled);
    }

    public function testSendBodyWithoutObFlush(): void
    {
        $stream = new Stream();
        $stream->write('test');
        $stream->rewind();

        $response          = new ResponseSendRecorderClass($stream, StatusCode::OK, HeaderCollection::fromArray([new ContentType('text/html')]));
        $response->obLevel = 0;

        self::assertSame('test', $stream->getContents());

        ob_start();
        $response->sendBody();
        $contents = ob_get_clean();

        self::assertSame('test', $contents);
        self::assertSame('test', $stream->getContents());
        self::assertFalse($response->obFlushCalled);
        self::assertTrue($response->flushCalled);
    }

    public function testSend(): void
    {
        $stream = new Stream();
        $stream->write('test');
        $stream->rewind();

        $response = new ResponseSendRecorderClass($stream, StatusCode::CREATED, HeaderCollection::fromArray([new ContentType('text/html')]));

        self::assertSame('test', $stream->getContents());

        ob_start();
        $response->send();
        $contents = ob_get_clean();

        self::assertSame('test', $contents);
        self::assertSame('test', $stream->getContents());
        self::assertSame([
            'HTTP/1.1 201 Created',
            true,
            'Content-Type: text/html',
            false,
        ], $response->sentHeaders);
        self::assertTrue($response->obFlushCalled);
        self::assertTrue($response->flushCalled);
    }

    public function testSendExecutesNativeIoSeams(): void
    {
        $stream = new Stream();
        $stream->write('test');
        $stream->rewind();

        $response = new Response($stream, StatusCode::OK, HeaderCollection::fromArray([new ContentType('text/html')]));

        // Exercises the real header()/ob_get_level()/ob_flush()/flush() seam
        // bodies. header() is a no-op under the CLI SAPI; the nested output
        // buffers capture and discard the flushed body so no stray output leaks.
        ob_start();
        ob_start();
        $result = $response->send();
        ob_get_clean();
        ob_get_clean();

        self::assertSame($response, $result);
    }
}
