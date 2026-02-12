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

use Valkyrja\Http\Message\Constant\HeaderName;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Header\Collection\HeaderCollection;
use Valkyrja\Http\Message\Header\ContentType;
use Valkyrja\Http\Message\Header\Header;
use Valkyrja\Http\Message\Response\HtmlResponse;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class HtmlResponseTest extends TestCase
{
    protected const string HTML = '<html lang="en"></html>';

    public function testConstruct(): void
    {
        $response = new HtmlResponse(
            self::HTML,
            headers: HeaderCollection::fromArray([new Header('Random-Header', 'test')])
        );

        self::assertSame(self::HTML, $response->getBody()->getContents());
        self::assertSame(StatusCode::OK, $response->getStatusCode());
        self::assertSame(StatusCode::OK->asPhrase(), $response->getReasonPhrase());
        self::assertSame('test', $response->getHeaders()->getHeaderLine('Random-Header'));
        self::assertSame('text/html; charset=utf-8', $response->getHeaders()->getHeaderLine(HeaderName::CONTENT_TYPE));
    }

    public function testCannotReplaceContentTypeFromConstruct(): void
    {
        $response = new HtmlResponse(
            self::HTML,
            headers: HeaderCollection::fromArray([new ContentType('text')])
        );

        self::assertSame('text/html; charset=utf-8', $response->getHeaders()->getHeaderLine(HeaderName::CONTENT_TYPE));
    }
}
