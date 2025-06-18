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
use Valkyrja\Http\Message\Response\HtmlResponse;
use Valkyrja\Tests\Unit\TestCase;

class HtmlResponseTest extends TestCase
{
    protected const string HTML = '<html lang="en"></html>';

    public function testConstruct(): void
    {
        $response = new HtmlResponse(self::HTML, headers: ['Random-Header' => ['test']]);

        self::assertSame(self::HTML, $response->getBody()->getContents());
        self::assertSame(StatusCode::OK, $response->getStatusCode());
        self::assertSame(StatusCode::OK->asPhrase(), $response->getReasonPhrase());
        self::assertSame('test', $response->getHeaderLine('Random-Header'));
        self::assertSame('text/html; charset=utf-8', $response->getHeaderLine(HeaderName::CONTENT_TYPE));
    }

    public function testCannotReplaceContentTypeFromConstruct(): void
    {
        $response = new HtmlResponse(self::HTML, headers: [HeaderName::CONTENT_TYPE => ['text']]);

        self::assertSame('text/html; charset=utf-8', $response->getHeaderLine(HeaderName::CONTENT_TYPE));
    }
}
