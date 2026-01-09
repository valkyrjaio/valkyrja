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

use Valkyrja\Http\Message\Constant\ContentType;
use Valkyrja\Http\Message\Constant\HeaderName;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Response\TextResponse;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class TextResponseTest extends TestCase
{
    protected const string TEXT = 'test';

    public function testConstruct(): void
    {
        $response = new TextResponse(self::TEXT, headers: ['Random-Header' => ['test']]);

        self::assertSame(self::TEXT, $response->getBody()->getContents());
        self::assertSame(StatusCode::OK, $response->getStatusCode());
        self::assertSame(StatusCode::OK->asPhrase(), $response->getReasonPhrase());
        self::assertSame('test', $response->getHeaderLine('Random-Header'));
        self::assertSame(ContentType::TEXT_PLAIN_UTF8, $response->getHeaderLine(HeaderName::CONTENT_TYPE));
    }

    public function testCannotReplaceContentTypeFromConstruct(): void
    {
        $response = new TextResponse(self::TEXT, headers: [HeaderName::CONTENT_TYPE => ['text']]);

        self::assertSame(ContentType::TEXT_PLAIN_UTF8, $response->getHeaderLine(HeaderName::CONTENT_TYPE));
    }
}
