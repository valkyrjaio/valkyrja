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

use PHPUnit\Framework\Attributes\DataProvider;
use Valkyrja\Http\Message\Constant\HeaderName;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Header\ContentType;
use Valkyrja\Http\Message\Header\Header;
use Valkyrja\Http\Message\Header\Location;
use Valkyrja\Http\Message\Header\Referer;
use Valkyrja\Http\Message\Request\ServerRequest;
use Valkyrja\Http\Message\Response\RedirectResponse;
use Valkyrja\Http\Message\Throwable\Exception\HttpRedirectException;
use Valkyrja\Http\Message\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Http\Message\Uri\Uri;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class RedirectResponseTest extends TestCase
{
    /**
     * @return array<int, array{0: StatusCode}>
     */
    public static function validStatusCodesProvider(): array
    {
        $codes = [];

        foreach (StatusCode::cases() as $value) {
            if ($value->isRedirect()) {
                $codes[] = [$value];
            }
        }

        return $codes;
    }

    /**
     * @return array<int, array{0: StatusCode}>
     */
    public static function invalidStatusCodesProvider(): array
    {
        $codes = [];

        foreach (StatusCode::cases() as $value) {
            if (! $value->isRedirect()) {
                $codes[] = [$value];
            }
        }

        return $codes;
    }

    public function testConstruct(): void
    {
        $response = new RedirectResponse(
            new Uri(path: '/'),
            headers: [
                new Header('Random-Header', 'test'),
            ]
        );

        self::assertSame(StatusCode::FOUND, $response->getStatusCode());
        self::assertSame(StatusCode::FOUND->asPhrase(), $response->getReasonPhrase());
        self::assertSame('test', $response->getHeaderLine('Random-Header'));
        self::assertSame('/', $response->getHeaderLine(HeaderName::LOCATION));
    }

    public function testCannotReplaceLocationHeaderFromConstruct(): void
    {
        $response = new RedirectResponse(
            new Uri(path: '/'),
            headers: [
                new ContentType('text'),
                new Location('uri.com'),
            ]
        );

        self::assertSame('text', $response->getHeaderLine(HeaderName::CONTENT_TYPE));
        self::assertSame('/', $response->getHeaderLine(HeaderName::LOCATION));
    }

    public function testGetUri(): void
    {
        $response = new RedirectResponse(
            $uri = new Uri(path: '/')
        );

        self::assertSame($uri, $response->getUri());
    }

    public function testWithUri(): void
    {
        $response  = new RedirectResponse(
            $uri = new Uri(path: '/')
        );
        $response2 = $response->withUri(
            $uri2 = new Uri(path: '/')
        );

        self::assertNotSame($response, $response2);
        self::assertNotSame($uri, $uri2);
        self::assertSame($uri, $response->getUri());
        self::assertSame($uri2, $response2->getUri());
        self::assertSame('/', $response->getHeaderLine(HeaderName::LOCATION));
        self::assertSame('/', $response2->getHeaderLine(HeaderName::LOCATION));
    }

    public function testSecure(): void
    {
        $response  = new RedirectResponse(
            $uri = new Uri(path: '/')
        );
        $response2 = $response->secure(
            '/new-path',
            new ServerRequest(uri: new Uri(host: $host = 'www.example.com')),
        );

        self::assertNotSame($response, $response2);
        self::assertSame($uri, $response->getUri());
        self::assertNotSame($uri, $response2->getUri());
        self::assertSame($host, $response2->getUri()->getHost());
        self::assertSame('https://www.example.com/new-path', (string) $response2->getUri());
        self::assertSame('https://www.example.com/new-path', $response2->getHeaderLine(HeaderName::LOCATION));
    }

    public function testBack(): void
    {
        $response  = new RedirectResponse(
            $uri = new Uri(path: '/')
        );
        $response2 = $response->back(
            new ServerRequest(headers: [new Referer($path = '/path')]),
        );
        $response3 = $response->back(
            new ServerRequest(),
        );
        $response4 = $response->back(
            new ServerRequest(headers: [new Referer('//www.external-uri.com/path')]),
        );
        $response5 = $response->back(
            new ServerRequest(
                uri: new Uri(host: 'www.example.com'),
                headers: [new Referer($url = '//www.example.com/path')]
            ),
        );

        self::assertNotSame($response, $response2);
        self::assertNotSame($response, $response3);
        self::assertSame($uri, $response->getUri());
        self::assertNotSame($uri, $response2->getUri());
        self::assertNotSame($uri, $response3->getUri());

        self::assertSame($path, (string) $response2->getUri());
        self::assertSame($path, $response2->getHeaderLine(HeaderName::LOCATION));

        self::assertSame('/', (string) $response3->getUri());
        self::assertSame('/', $response3->getHeaderLine(HeaderName::LOCATION));

        self::assertSame('/', (string) $response4->getUri());
        self::assertSame('/', $response4->getHeaderLine(HeaderName::LOCATION));

        self::assertSame($url, (string) $response5->getUri());
        self::assertSame($url, $response5->getHeaderLine(HeaderName::LOCATION));
    }

    public function testThrow(): void
    {
        $this->expectException(HttpRedirectException::class);

        $response = new RedirectResponse();

        $response->throw();
    }

    #[DataProvider('invalidStatusCodesProvider')]
    public function testExceptionForNonRedirectStatusCode(StatusCode $code): void
    {
        $this->expectException(InvalidArgumentException::class);

        new RedirectResponse(
            new Uri(path: '/'),
            $code
        );
    }

    #[DataProvider('validStatusCodesProvider')]
    public function testValidRedirectStatusCode(StatusCode $code): void
    {
        $response = new RedirectResponse(
            new Uri(path: '/'),
            $code
        );

        self::assertSame($code, $response->getStatusCode());
    }
}
