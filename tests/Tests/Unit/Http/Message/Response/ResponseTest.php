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

namespace Valkyrja\Tests\Unit\Http\Message\Response
{
    use Valkyrja\Http\Message\Constant\HeaderName;
    use Valkyrja\Http\Message\Enum\StatusCode;
    use Valkyrja\Http\Message\Header\Collection\HeaderCollection;
    use Valkyrja\Http\Message\Header\ContentType;
    use Valkyrja\Http\Message\Header\Value\Cookie;
    use Valkyrja\Http\Message\Response\Response;
    use Valkyrja\Http\Message\Stream\Stream;
    use Valkyrja\Support\Time\Time;
    use Valkyrja\Tests\Unit\Abstract\TestCase;

    use function ob_get_clean;
    use function ob_get_contents;
    use function ob_start;

    final class ResponseTest extends TestCase
    {
        /** @var string[] */
        public static array $headers = [];

        public static bool $obFlushCalled = false;
        public static bool $flushCalled   = false;

        public static int $responseCode = 0;

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
            $response2 = $response->withStatus(StatusCode::CREATED);

            self::assertNotSame($response, $response2);
            self::assertSame(StatusCode::OK, $response->getStatusCode());
            self::assertSame(StatusCode::CREATED, $response2->getStatusCode());
        }

        public function testGetReasonPhrase(): void
        {
            $response  = Response::create();
            $response2 = $response->withStatus(StatusCode::CREATED);
            $response3 = $response->withStatus(StatusCode::CREATED, 'pie');

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
            self::assertSame('foo=bar; path=/; httponly', $response2->getHeaders()->getHeaderLine(HeaderName::SET_COOKIE));
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
            $this->resetHeadersAndResponseCode();

            $response = new Response(new Stream(), StatusCode::CREATED, HeaderCollection::fromArray([new ContentType('text/html')]));

            $response->sendHttpLine();

            /** @var array<string|true> $headers */
            $headers = self::$headers;

            self::assertCount(2, $headers);
            self::assertNotNull($headers[0] ?? null);
            self::assertSame('HTTP/1.1 201 Created', $headers[0]);
            self::assertTrue($headers[1]);
        }

        public function testSendHeaders(): void
        {
            $this->resetHeadersAndResponseCode();

            $response = new Response(new Stream(), StatusCode::OK, HeaderCollection::fromArray([new ContentType('text/html')]));

            $response->sendHeaders();

            /** @var array<string|true> $headers */
            $headers = self::$headers;

            self::assertCount(2, $headers);
            self::assertNotNull($headers[0] ?? null);
            self::assertSame('Content-Type: text/html', $headers[0]);
            self::assertFalse($headers[1]);
        }

        public function testSendBody(): void
        {
            $stream = new Stream();
            $stream->write('test');
            $stream->rewind();

            $response = new Response($stream, StatusCode::OK, HeaderCollection::fromArray([new ContentType('text/html')]));

            self::assertSame('test', $stream->getContents());

            ob_start();
            $response->sendBody();
            $contents = ob_get_contents();
            ob_get_clean();

            self::assertSame('test', $contents);
            self::assertSame('test', $stream->getContents());
            self::assertTrue(self::$obFlushCalled);
            self::assertTrue(self::$flushCalled);
        }

        public function testSend(): void
        {
            $this->resetHeadersAndResponseCode();

            $stream = new Stream();
            $stream->write('test');
            $stream->rewind();

            $response = new Response($stream, StatusCode::CREATED, HeaderCollection::fromArray([new ContentType('text/html')]));

            self::assertSame('test', $stream->getContents());

            ob_start();
            $response->send();
            $contents = ob_get_contents();
            ob_get_clean();

            /** @var array<string|true> $headers */
            $headers = self::$headers;

            self::assertSame('test', $contents);
            self::assertSame('test', $stream->getContents());
            self::assertCount(4, $headers);
            self::assertNotNull($headers[0] ?? null);
            self::assertSame('HTTP/1.1 201 Created', $headers[0]);
            self::assertTrue($headers[1]);
            self::assertNotNull($headers[2] ?? null);
            self::assertSame('Content-Type: text/html', $headers[2]);
            self::assertFalse($headers[3]);
            self::assertTrue(self::$obFlushCalled);
            self::assertTrue(self::$flushCalled);
        }

        protected function resetHeadersAndResponseCode(): void
        {
            self::$headers       = [];
            self::$responseCode  = 0;
            self::$obFlushCalled = false;
            self::$flushCalled   = false;
        }
    }
}

namespace Valkyrja\Http\Message\Response
{
    use Valkyrja\Tests\Unit\Http\Message\Response\ResponseTest;

    function header(string $header, bool $replace = true, int $response_code = 0): void
    {
        ResponseTest::$headers[] = $header;
        ResponseTest::$headers[] = $replace;

        if ($response_code > 0) {
            ResponseTest::$responseCode = $response_code;
        }
    }

    function ob_flush(): void
    {
        ResponseTest::$obFlushCalled = true;
    }

    function flush(): void
    {
        ResponseTest::$flushCalled = true;
    }
}
