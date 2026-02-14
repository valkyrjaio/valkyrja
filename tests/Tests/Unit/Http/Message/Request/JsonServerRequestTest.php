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
            $request3->getParsedJson()->withAdded(['test3' => 'fire'])
        );
        $request8 = $request4->withParsedJson(
            $request3->getParsedJson()->withAdded(['test3' => 'pie'])
        );

        self::assertNotSame($request3, $request5);
        self::assertNotSame($request4, $request6);
        self::assertNotSame($request3, $request7);
        self::assertNotSame($request4, $request8);

        self::assertEmpty($request->getParsedJson()->getAll());
        self::assertEmpty($request2->getParsedJson()->getAll());
        self::assertNotEmpty($request3->getParsedJson()->getAll());
        self::assertNotEmpty($request4->getParsedJson()->getAll());
        self::assertNotEmpty($request5->getParsedJson()->getAll());
        self::assertNotEmpty($request6->getParsedJson()->getAll());
        self::assertNotEmpty($request7->getParsedJson()->getAll());
        self::assertNotEmpty($request8->getParsedJson()->getAll());

        self::assertSame(
            array_filter(
                $json,
                static fn (string|int $name): bool => $name === 'test' || $name === 'null',
                ARRAY_FILTER_USE_KEY
            ),
            $request3->getParsedJson()->getOnly('test', 'null')
        );
        self::assertSameCount(
            array_filter(
                $json,
                static fn (string|int $name): bool => $name !== 'test' && $name !== 'null',
                ARRAY_FILTER_USE_KEY
            ),
            $request3->getParsedJson()->getAllExcept('test', 'null')
        );

        self::assertEmpty($request3->getParsedBody()->getAll());
        self::assertNotEmpty($request4->getParsedBody()->getAll());
        self::assertEmpty($request5->getParsedBody()->getAll());
        self::assertNotEmpty($request6->getParsedBody()->getAll());
        self::assertEmpty($request7->getParsedBody()->getAll());
        self::assertNotEmpty($request8->getParsedBody()->getAll());

        self::assertTrue($request3->getParsedJson()->has('test'));
        self::assertTrue($request3->getParsedJson()->has('foo'));
        self::assertTrue($request3->getParsedJson()->has('bar'));
        self::assertFalse($request3->getParsedJson()->has('null'));

        self::assertTrue($request4->getParsedJson()->has('test'));
        self::assertTrue($request4->getParsedJson()->has('foo'));
        self::assertTrue($request4->getParsedJson()->has('bar'));
        self::assertFalse($request4->getParsedJson()->has('null'));

        self::assertFalse($request5->getParsedJson()->has('test'));
        self::assertFalse($request5->getParsedJson()->has('foo'));
        self::assertFalse($request5->getParsedJson()->has('bar'));
        self::assertFalse($request5->getParsedJson()->has('null'));
        self::assertTrue($request5->getParsedJson()->has('test2'));
        self::assertTrue($request5->getParsedJson()->has('foo2'));

        self::assertFalse($request6->getParsedJson()->has('test'));
        self::assertFalse($request6->getParsedJson()->has('foo'));
        self::assertFalse($request6->getParsedJson()->has('bar'));
        self::assertFalse($request6->getParsedJson()->has('null'));
        self::assertTrue($request6->getParsedJson()->has('test2'));
        self::assertTrue($request6->getParsedJson()->has('foo2'));

        self::assertTrue($request7->getParsedJson()->has('test'));
        self::assertTrue($request7->getParsedJson()->has('foo'));
        self::assertTrue($request7->getParsedJson()->has('bar'));
        self::assertFalse($request7->getParsedJson()->has('null'));
        self::assertTrue($request7->getParsedJson()->has('test3'));

        self::assertTrue($request8->getParsedJson()->has('test'));
        self::assertTrue($request8->getParsedJson()->has('foo'));
        self::assertTrue($request8->getParsedJson()->has('bar'));
        self::assertFalse($request8->getParsedJson()->has('null'));
        self::assertTrue($request8->getParsedJson()->has('test3'));

        self::assertSame('foo', $request3->getParsedJson()->get('test'));
        self::assertSame('foo', $request3->getParsedJson()->get('test'));
        self::assertSame(2, $request3->getParsedJson()->get('foo'));
        self::assertInstanceOf(ParsedJsonParamCollection::class, $request3->getParsedJson()->get('bar'));
        self::assertNull($request3->getParsedJson()->get('null'));

        self::assertSame('foo', $request4->getParsedJson()->get('test'));
        self::assertSame('notFoo', $request4->getParsedBody()->get('test'));

        self::assertSame('bar', $request5->getParsedJson()->get('test2'));
        self::assertSame(1, $request5->getParsedJson()->get('foo2'));

        self::assertSame('bar', $request6->getParsedJson()->get('test2'));
        self::assertSame(1, $request6->getParsedJson()->get('foo2'));

        self::assertSame('fire', $request7->getParsedJson()->get('test3'));
        self::assertSame('pie', $request8->getParsedJson()->get('test3'));
    }
}
