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

use JsonException;
use Valkyrja\Http\Message\Constant\HeaderName;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Exception\InvalidArgumentException;
use Valkyrja\Http\Message\Response\JsonResponse;
use Valkyrja\Tests\Unit\TestCase;

class JsonResponseTest extends TestCase
{
    protected const JSON         = ['test' => ['foo', 'bar']];
    protected const JSON_AS_TEXT = '{"test":["foo","bar"]}';

    /**
     * @throws JsonException
     */
    public function testConstruct(): void
    {
        $response = new JsonResponse(self::JSON, headers: ['Random-Header' => ['test']]);

        self::assertSame(self::JSON_AS_TEXT, $response->getBody()->getContents());
        self::assertSame(StatusCode::OK, $response->getStatusCode());
        self::assertSame(StatusCode::OK->asPhrase(), $response->getReasonPhrase());
        self::assertSame('test', $response->getHeaderLine('Random-Header'));
        self::assertSame('application/json', $response->getHeaderLine(HeaderName::CONTENT_TYPE));
    }

    /**
     * @throws JsonException
     */
    public function testCannotReplaceContentTypeFromConstruct(): void
    {
        $response = new JsonResponse(self::JSON, headers: [HeaderName::CONTENT_TYPE => ['text']]);

        self::assertSame('application/json', $response->getHeaderLine(HeaderName::CONTENT_TYPE));
    }

    /**
     * @throws JsonException
     */
    public function testWithCallback(): void
    {
        $response  = new JsonResponse(self::JSON, headers: [HeaderName::CONTENT_TYPE => ['text']]);
        $response2 = $response->withCallback('test');

        self::assertNotSame($response, $response2);

        self::assertSame('application/json', $response->getHeaderLine(HeaderName::CONTENT_TYPE));
        self::assertSame('text/javascript', $response2->getHeaderLine(HeaderName::CONTENT_TYPE));

        self::assertSame(self::JSON_AS_TEXT, $response->getBody()->getContents());
        self::assertSame('/**/test(' . self::JSON_AS_TEXT . ');', $response2->getBody()->getContents());
    }

    /**
     * @throws JsonException
     */
    public function testWithCallbackWithInvalidCallback(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $response = new JsonResponse(self::JSON, headers: [HeaderName::CONTENT_TYPE => ['text']]);
        $response->withCallback('test();');
    }

    /**
     * @throws JsonException
     */
    public function testWithoutCallback(): void
    {
        $response  = new JsonResponse(self::JSON, headers: [HeaderName::CONTENT_TYPE => ['text']]);
        $response2 = $response->withCallback('test');
        $response3 = $response2->withoutCallback();

        self::assertNotSame($response, $response2);
        self::assertNotSame($response, $response3);
        self::assertNotSame($response2, $response3);

        self::assertSame('application/json', $response->getHeaderLine(HeaderName::CONTENT_TYPE));
        self::assertSame('text/javascript', $response2->getHeaderLine(HeaderName::CONTENT_TYPE));
        self::assertSame('application/json', $response3->getHeaderLine(HeaderName::CONTENT_TYPE));

        self::assertSame(self::JSON_AS_TEXT, $response->getBody()->getContents());
        self::assertSame('/**/test(' . self::JSON_AS_TEXT . ');', $response2->getBody()->getContents());
        self::assertSame(self::JSON_AS_TEXT, $response3->getBody()->getContents());
    }
}
