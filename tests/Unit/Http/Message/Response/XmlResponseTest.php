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
use Valkyrja\Http\Message\Response\XmlResponse;
use Valkyrja\Tests\Unit\TestCase;

class XmlResponseTest extends TestCase
{
    protected const string XML = '<?xml version="1.0" encoding="" ?><test></test>';

    public function testConstruct(): void
    {
        $response = new XmlResponse(self::XML, headers: ['Random-Header' => ['test']]);

        self::assertSame(self::XML, $response->getBody()->getContents());
        self::assertSame(StatusCode::OK, $response->getStatusCode());
        self::assertSame(StatusCode::OK->asPhrase(), $response->getReasonPhrase());
        self::assertSame('test', $response->getHeaderLine('Random-Header'));
        self::assertSame(ContentType::APPLICATION_XML_UTF8, $response->getHeaderLine(HeaderName::CONTENT_TYPE));
    }

    public function testCannotReplaceContentTypeFromConstruct(): void
    {
        $response = new XmlResponse(self::XML, headers: [HeaderName::CONTENT_TYPE => ['text']]);

        self::assertSame(ContentType::APPLICATION_XML_UTF8, $response->getHeaderLine(HeaderName::CONTENT_TYPE));
    }
}
