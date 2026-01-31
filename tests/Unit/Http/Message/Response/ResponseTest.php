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
    use Valkyrja\Http\Message\Header\ContentType;
    use Valkyrja\Http\Message\Header\Value\Cookie;
    use Valkyrja\Http\Message\Response\Response;
    use Valkyrja\Http\Message\Stream\Stream;
    use Valkyrja\Support\Time\Time;
    use Valkyrja\Tests\Unit\Abstract\TestCase;

    use function ob_get_clean;
    use function ob_get_contents;
    use function ob_start;

    class ResponseTest extends TestCase
    {
        /** @var string[] */
        public static array $headers = [];

        public static int $responseCode = 0;

        public function testCreate(): void
        {
            $response  = Response::create();
            $response2 = Response::create('test', StatusCode::CREATED, [new ContentType('text/html')]);

            self::assertEmpty($response->getBody()->getContents());
            self::assertSame(StatusCode::OK, $response->getStatusCode());
            self::assertEmpty($response->getHeaders());

            self::assertSame('test', $response2->getBody()->getContents());
            self::assertSame(StatusCode::CREATED, $response2->getStatusCode());

            self::assertSame('text/html', $response2->getHeaderLine(HeaderName::CONTENT_TYPE));
        }

        public function testGetStatusCode(): void
        {
            $response  = Response::create();
            $response2 = Response::create('test', StatusCode::CREATED, [new ContentType('text/html')]);

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
            self::assertSame('foo=bar; path=/; httponly', $response2->getHeaderLine(HeaderName::SET_COOKIE));
        }

        public function testWithoutCookie(): void
        {
            Time::freeze(1734553175);

            $cookie        = new Cookie(name: 'foo', value: 'bar');
            $deletedCookie = $cookie->delete();

            $response  = Response::create();
            $response2 = $response->withoutCookie($cookie);

            self::assertNotSame($response, $response2);
            self::assertSame((string) $deletedCookie, $response2->getHeaderLine(HeaderName::SET_COOKIE));

            Time::unfreeze();
        }

        public function testSendHttpLine(): void
        {
            $this->resetHeadersAndResponseCode();

            $response = new Response(new Stream(), StatusCode::OK, [new ContentType('text/html')]);

            $response->sendHttpLine();

            /** @var string[] $headers */
            $headers = self::$headers;

            self::assertCount(1, $headers);
            self::assertNotNull($headers[0] ?? null);
            self::assertSame('HTTP/1.1 200 OK', $headers[0]);
        }

        public function testSendHeaders(): void
        {
            $this->resetHeadersAndResponseCode();

            $response = new Response(new Stream(), StatusCode::OK, [new ContentType('text/html')]);

            $response->sendHeaders();

            /** @var string[] $headers */
            $headers = self::$headers;

            self::assertCount(1, $headers);
            self::assertNotNull($headers[0] ?? null);
            self::assertSame('Content-Type:text/html', $headers[0]);
        }

        public function testSendBody(): void
        {
            $stream = new Stream();
            $stream->write('test');
            $stream->rewind();

            $response = new Response($stream, StatusCode::OK, [new ContentType('text/html')]);

            ob_start();
            $response->sendBody();
            $contents = ob_get_contents();
            ob_get_clean();

            self::assertSame($stream->getContents(), $contents);
        }

        public function testSend(): void
        {
            $this->resetHeadersAndResponseCode();

            $stream = new Stream();
            $stream->write('test');
            $stream->rewind();

            $response = new Response($stream, StatusCode::OK, [new ContentType('text/html')]);

            ob_start();
            $response->send();
            $contents = ob_get_contents();
            ob_get_clean();

            /** @var string[] $headers */
            $headers = self::$headers;

            self::assertSame($stream->getContents(), $contents);
            self::assertCount(2, $headers);
            self::assertNotNull($headers[0] ?? null);
            self::assertSame('HTTP/1.1 200 OK', $headers[0]);
            self::assertNotNull($headers[1] ?? null);
            self::assertSame('Content-Type:text/html', $headers[1]);
        }

        protected function resetHeadersAndResponseCode(): void
        {
            self::$headers      = [];
            self::$responseCode = 0;
        }
    }
}

namespace Valkyrja\Http\Message\Response
{
    use Valkyrja\Tests\Unit\Http\Message\Response\ResponseTest;

    function header(string $header, bool $replace = true, int $response_code = 0): void
    {
        ResponseTest::$headers[] = $header;

        if ($response_code > 0) {
            ResponseTest::$responseCode = $response_code;
        }
    }

    function ob_flush(): void
    {
    }

    function flush(): void
    {
    }
}
