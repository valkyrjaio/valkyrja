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

use JsonException;
use Valkyrja\Http\Message\Constant\HeaderName;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Header\Collection\HeaderCollection;
use Valkyrja\Http\Message\Header\ContentType;
use Valkyrja\Http\Message\Header\Header;
use Valkyrja\Http\Message\Response\JsonResponse;
use Valkyrja\Http\Message\Response\Throwable\Exception\HttpRequestInvalidJsonCallbackException;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class JsonResponseTest extends TestCase
{
    /** @var array[] */
    protected const array  JSON         = ['test' => ['foo', 'bar']];
    protected const string JSON_AS_TEXT = '{"test":["foo","bar"]}';

    /**
     * @throws JsonException
     */
    public function testConstruct(): void
    {
        $response = new JsonResponse(self::JSON, headers: HeaderCollection::fromArray([new Header('Random-Header', 'test')]));

        self::assertSame(self::JSON_AS_TEXT, $response->getBody()->getContents());
        self::assertSame(StatusCode::OK, $response->getStatusCode());
        self::assertSame(StatusCode::OK->asPhrase(), $response->getReasonPhrase());
        self::assertSame('test', $response->getHeaders()->getHeaderLine('Random-Header'));
        self::assertSame('application/json', $response->getHeaders()->getHeaderLine(HeaderName::CONTENT_TYPE));
    }

    /**
     * @throws JsonException
     */
    public function testCannotReplaceContentTypeFromConstruct(): void
    {
        $response = new JsonResponse(self::JSON, headers: HeaderCollection::fromArray([new ContentType('text')]));

        self::assertSame('application/json', $response->getHeaders()->getHeaderLine(HeaderName::CONTENT_TYPE));
    }

    /**
     * @throws JsonException
     */
    public function testWithCallback(): void
    {
        $response  = new JsonResponse(self::JSON, headers: HeaderCollection::fromArray([new ContentType('text')]));
        $response2 = $response->withCallback('test');

        self::assertNotSame($response, $response2);

        self::assertSame('application/json', $response->getHeaders()->getHeaderLine(HeaderName::CONTENT_TYPE));
        self::assertSame('text/javascript', $response2->getHeaders()->getHeaderLine(HeaderName::CONTENT_TYPE));

        self::assertSame(self::JSON_AS_TEXT, $response->getBody()->getContents());
        self::assertSame('/**/test(' . self::JSON_AS_TEXT . ');', $response2->getBody()->getContents());
    }

    /**
     * @throws JsonException
     */
    public function testWithCallbackWithInvalidCallback(): void
    {
        $this->expectException(HttpRequestInvalidJsonCallbackException::class);

        $response = new JsonResponse(self::JSON, headers: HeaderCollection::fromArray([new ContentType('text')]));
        $response->withCallback('test();');
    }

    /**
     * @throws JsonException
     */
    public function testWithoutCallback(): void
    {
        $response  = new JsonResponse(self::JSON, headers: HeaderCollection::fromArray([new ContentType('text')]));
        $response2 = $response->withCallback('test');
        $response3 = $response2->withoutCallback();

        self::assertNotSame($response, $response2);
        self::assertNotSame($response, $response3);
        self::assertNotSame($response2, $response3);

        self::assertSame('application/json', $response->getHeaders()->getHeaderLine(HeaderName::CONTENT_TYPE));
        self::assertSame('text/javascript', $response2->getHeaders()->getHeaderLine(HeaderName::CONTENT_TYPE));
        self::assertSame('application/json', $response3->getHeaders()->getHeaderLine(HeaderName::CONTENT_TYPE));

        self::assertSame(self::JSON_AS_TEXT, $response->getBody()->getContents());
        self::assertSame('/**/test(' . self::JSON_AS_TEXT . ');', $response2->getBody()->getContents());
        self::assertSame(self::JSON_AS_TEXT, $response3->getBody()->getContents());
    }

    /**
     * @throws JsonException
     */
    public function testCreateFromDataAndGetBodyAsJson(): void
    {
        $response = JsonResponse::createFromData(self::JSON);

        self::assertSame(StatusCode::OK, $response->getStatusCode());
        self::assertSame(self::JSON, $response->getBodyAsJson());
    }

    /**
     * @throws JsonException
     */
    public function testWithJsonAsBody(): void
    {
        $response = JsonResponse::createFromData()->withJsonAsBody(['updated' => true]);

        self::assertSame(['updated' => true], $response->getBodyAsJson());
    }
}
