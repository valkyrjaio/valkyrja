<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Http\Message\Response;

use Valkyrja\Http\Message\Constant\ContentTypeValue;
use Valkyrja\Http\Message\Constant\HeaderName;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Header\Collection\HeaderCollection;
use Valkyrja\Http\Message\Header\ContentType;
use Valkyrja\Http\Message\Header\Header;
use Valkyrja\Http\Message\Response\TextResponse;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class TextResponseTest extends TestCase
{
    protected const string TEXT = 'test';

    public function testConstruct(): void
    {
        $response = new TextResponse(self::TEXT, headers: HeaderCollection::fromArray([new Header('Random-Header', 'test')]));

        self::assertSame(self::TEXT, $response->getBody()->getContents());
        self::assertSame(StatusCode::OK, $response->getStatusCode());
        self::assertSame(StatusCode::OK->asPhrase(), $response->getReasonPhrase());
        self::assertSame('test', $response->getHeaders()->getHeaderLine('Random-Header'));
        self::assertSame(ContentTypeValue::TEXT_PLAIN_UTF8, $response->getHeaders()->getHeaderLine(HeaderName::CONTENT_TYPE));
    }

    public function testCannotReplaceContentTypeFromConstruct(): void
    {
        $response = new TextResponse(self::TEXT, headers: HeaderCollection::fromArray([new ContentType('text')]));

        self::assertSame(ContentTypeValue::TEXT_PLAIN_UTF8, $response->getHeaders()->getHeaderLine(HeaderName::CONTENT_TYPE));
    }

    public function testCreateFactory(): void
    {
        $response = TextResponse::create(self::TEXT);

        self::assertSame(self::TEXT, $response->getBody()->getContents());
        self::assertSame(StatusCode::OK, $response->getStatusCode());
        self::assertSame(ContentTypeValue::TEXT_PLAIN_UTF8, $response->getHeaders()->getHeaderLine(HeaderName::CONTENT_TYPE));
    }
}
