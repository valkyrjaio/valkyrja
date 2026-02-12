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

namespace Valkyrja\Tests\Unit\Http\Message\Request;

use JsonException;
use Valkyrja\Http\Message\Constant\ContentTypeValue;
use Valkyrja\Http\Message\Header\Collection\HeaderCollection;
use Valkyrja\Http\Message\Header\ContentType;
use Valkyrja\Http\Message\Param\ParsedBodyParamCollection;
use Valkyrja\Http\Message\Param\ParsedJsonParamCollection;
use Valkyrja\Http\Message\Request\JsonServerRequest;
use Valkyrja\Http\Message\Stream\Stream;
use Valkyrja\Tests\Unit\Abstract\TestCase;

use function array_filter;
use function json_encode;

use const ARRAY_FILTER_USE_KEY;
use const JSON_THROW_ON_ERROR;

final class JsonServerRequestTest extends TestCase
{
    /**
     * @throws JsonException
     */
    public function testParsedJson(): void
    {
        $json        = [
            'test' => 'foo',
            'foo'  => 2,
            'bar'  => [],
            'null' => null,
        ];
        $json2       = [
            'test2' => 'bar',
            'foo2'  => 1,
        ];
        $jsonHeaders = new HeaderCollection(new ContentType(ContentTypeValue::APPLICATION_JSON));
        $jsonBody    = new Stream();
        $jsonBody->write(json_encode($json, JSON_THROW_ON_ERROR));

        $request  = new JsonServerRequest();
        $request2 = new JsonServerRequest(headers: $jsonHeaders);
        $request3 = new JsonServerRequest(body: $jsonBody, headers: $jsonHeaders);
        $request4 = new JsonServerRequest(
            body: $jsonBody,
            headers: $jsonHeaders,
            parsedBody: ParsedBodyParamCollection::fromArray(['test' => 'notFoo'])
        );
        $request5 = $request3->withParsedJson(
            ParsedJsonParamCollection::fromArray($json2)
        );
        $request6 = $request4->withParsedJson(
            ParsedJsonParamCollection::fromArray($json2)
        );
        $request7 = $request3->withParsedJson(
            $request3->getParsedJson()->withAddedParams(['test3' => 'fire'])
        );
        $request8 = $request4->withParsedJson(
            $request3->getParsedJson()->withAddedParams(['test3' => 'pie'])
        );

        self::assertNotSame($request3, $request5);
        self::assertNotSame($request4, $request6);
        self::assertNotSame($request3, $request7);
        self::assertNotSame($request4, $request8);

        self::assertEmpty($request->getParsedJson()->getParams());
        self::assertEmpty($request2->getParsedJson()->getParams());
        self::assertNotEmpty($request3->getParsedJson()->getParams());
        self::assertNotEmpty($request4->getParsedJson()->getParams());
        self::assertNotEmpty($request5->getParsedJson()->getParams());
        self::assertNotEmpty($request6->getParsedJson()->getParams());
        self::assertNotEmpty($request7->getParsedJson()->getParams());
        self::assertNotEmpty($request8->getParsedJson()->getParams());

        self::assertSame(
            array_filter(
                $json,
                static fn (string|int $name): bool => $name === 'test' || $name === 'null',
                ARRAY_FILTER_USE_KEY
            ),
            $request3->getParsedJson()->onlyParams('test', 'null')
        );
        self::assertSameCount(
            array_filter(
                $json,
                static fn (string|int $name): bool => $name !== 'test' && $name !== 'null',
                ARRAY_FILTER_USE_KEY
            ),
            $request3->getParsedJson()->exceptParams('test', 'null')
        );

        self::assertEmpty($request3->getParsedBody()->getParams());
        self::assertNotEmpty($request4->getParsedBody()->getParams());
        self::assertEmpty($request5->getParsedBody()->getParams());
        self::assertNotEmpty($request6->getParsedBody()->getParams());
        self::assertEmpty($request7->getParsedBody()->getParams());
        self::assertNotEmpty($request8->getParsedBody()->getParams());

        self::assertTrue($request3->getParsedJson()->hasParam('test'));
        self::assertTrue($request3->getParsedJson()->hasParam('foo'));
        self::assertTrue($request3->getParsedJson()->hasParam('bar'));
        self::assertFalse($request3->getParsedJson()->hasParam('null'));

        self::assertTrue($request4->getParsedJson()->hasParam('test'));
        self::assertTrue($request4->getParsedJson()->hasParam('foo'));
        self::assertTrue($request4->getParsedJson()->hasParam('bar'));
        self::assertFalse($request4->getParsedJson()->hasParam('null'));

        self::assertFalse($request5->getParsedJson()->hasParam('test'));
        self::assertFalse($request5->getParsedJson()->hasParam('foo'));
        self::assertFalse($request5->getParsedJson()->hasParam('bar'));
        self::assertFalse($request5->getParsedJson()->hasParam('null'));
        self::assertTrue($request5->getParsedJson()->hasParam('test2'));
        self::assertTrue($request5->getParsedJson()->hasParam('foo2'));

        self::assertFalse($request6->getParsedJson()->hasParam('test'));
        self::assertFalse($request6->getParsedJson()->hasParam('foo'));
        self::assertFalse($request6->getParsedJson()->hasParam('bar'));
        self::assertFalse($request6->getParsedJson()->hasParam('null'));
        self::assertTrue($request6->getParsedJson()->hasParam('test2'));
        self::assertTrue($request6->getParsedJson()->hasParam('foo2'));

        self::assertTrue($request7->getParsedJson()->hasParam('test'));
        self::assertTrue($request7->getParsedJson()->hasParam('foo'));
        self::assertTrue($request7->getParsedJson()->hasParam('bar'));
        self::assertFalse($request7->getParsedJson()->hasParam('null'));
        self::assertTrue($request7->getParsedJson()->hasParam('test3'));

        self::assertTrue($request8->getParsedJson()->hasParam('test'));
        self::assertTrue($request8->getParsedJson()->hasParam('foo'));
        self::assertTrue($request8->getParsedJson()->hasParam('bar'));
        self::assertFalse($request8->getParsedJson()->hasParam('null'));
        self::assertTrue($request8->getParsedJson()->hasParam('test3'));

        self::assertSame('foo', $request3->getParsedJson()->getParam('test'));
        self::assertSame('foo', $request3->getParsedJson()->getParam('test'));
        self::assertSame(2, $request3->getParsedJson()->getParam('foo'));
        self::assertInstanceOf(ParsedJsonParamCollection::class, $request3->getParsedJson()->getParam('bar'));
        self::assertNull($request3->getParsedJson()->getParam('null'));

        self::assertSame('foo', $request4->getParsedJson()->getParam('test'));
        self::assertSame('notFoo', $request4->getParsedBody()->getParam('test'));

        self::assertSame('bar', $request5->getParsedJson()->getParam('test2'));
        self::assertSame(1, $request5->getParsedJson()->getParam('foo2'));

        self::assertSame('bar', $request6->getParsedJson()->getParam('test2'));
        self::assertSame(1, $request6->getParsedJson()->getParam('foo2'));

        self::assertSame('fire', $request7->getParsedJson()->getParam('test3'));
        self::assertSame('pie', $request8->getParsedJson()->getParam('test3'));
    }
}
