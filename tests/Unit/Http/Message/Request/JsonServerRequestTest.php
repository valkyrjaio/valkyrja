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
use Valkyrja\Http\Message\Constant\ContentType;
use Valkyrja\Http\Message\Constant\HeaderName;
use Valkyrja\Http\Message\Request\JsonServerRequest;
use Valkyrja\Http\Message\Stream\Stream;
use Valkyrja\Tests\Unit\Abstract\TestCase;

use function array_filter;
use function json_encode;

use const ARRAY_FILTER_USE_KEY;
use const JSON_THROW_ON_ERROR;

class JsonServerRequestTest extends TestCase
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
        $jsonHeaders = [HeaderName::CONTENT_TYPE => [ContentType::APPLICATION_JSON]];
        $jsonBody    = new Stream();
        $jsonBody->write(json_encode($json, JSON_THROW_ON_ERROR));

        $request  = new JsonServerRequest();
        $request2 = new JsonServerRequest(headers: $jsonHeaders);
        $request3 = new JsonServerRequest(body: $jsonBody, headers: $jsonHeaders);
        $request4 = new JsonServerRequest(body: $jsonBody, headers: $jsonHeaders, parsedBody: ['test' => 'notFoo']);
        $request5 = $request3->withParsedJson($json2);
        $request6 = $request4->withParsedJson($json2);
        $request7 = $request3->withAddedParsedJsonParam('test3', 'fire');
        $request8 = $request4->withAddedParsedJsonParam('test3', 'pie');

        self::assertNotSame($request3, $request5);
        self::assertNotSame($request4, $request6);
        self::assertNotSame($request3, $request7);
        self::assertNotSame($request4, $request8);

        self::assertEmpty($request->getParsedJson());
        self::assertEmpty($request2->getParsedJson());
        self::assertNotEmpty($request3->getParsedJson());
        self::assertNotEmpty($request4->getParsedJson());
        self::assertNotEmpty($request5->getParsedJson());
        self::assertNotEmpty($request6->getParsedJson());
        self::assertNotEmpty($request7->getParsedJson());
        self::assertNotEmpty($request8->getParsedJson());

        self::assertSame(
            array_filter(
                $json,
                static fn (string|int $name): bool => $name === 'test' || $name === 'null',
                ARRAY_FILTER_USE_KEY
            ),
            $request3->onlyParsedJson('test', 'null')
        );
        self::assertSame(
            array_filter(
                $json,
                static fn (string|int $name): bool => $name !== 'test' && $name !== 'null',
                ARRAY_FILTER_USE_KEY
            ),
            $request3->exceptParsedJson('test', 'null')
        );

        self::assertSame($request3->getParsedJson(), $request3->getParsedBody());
        self::assertNotSame($request4->getParsedJson(), $request4->getParsedBody());
        self::assertSame($request5->getParsedJson(), $request5->getParsedBody());
        self::assertNotSame($request6->getParsedJson(), $request6->getParsedBody());
        self::assertSame($request7->getParsedJson(), $request7->getParsedBody());
        self::assertNotSame($request8->getParsedJson(), $request8->getParsedBody());

        self::assertTrue($request3->hasParsedJsonParam('test'));
        self::assertTrue($request3->hasParsedJsonParam('foo'));
        self::assertTrue($request3->hasParsedJsonParam('bar'));
        self::assertTrue($request3->hasParsedJsonParam('null'));

        self::assertTrue($request4->hasParsedJsonParam('test'));
        self::assertTrue($request4->hasParsedJsonParam('foo'));
        self::assertTrue($request4->hasParsedJsonParam('bar'));
        self::assertTrue($request4->hasParsedJsonParam('null'));

        self::assertFalse($request5->hasParsedJsonParam('test'));
        self::assertFalse($request5->hasParsedJsonParam('foo'));
        self::assertFalse($request5->hasParsedJsonParam('bar'));
        self::assertFalse($request5->hasParsedJsonParam('null'));
        self::assertTrue($request5->hasParsedJsonParam('test2'));
        self::assertTrue($request5->hasParsedJsonParam('foo2'));

        self::assertFalse($request6->hasParsedJsonParam('test'));
        self::assertFalse($request6->hasParsedJsonParam('foo'));
        self::assertFalse($request6->hasParsedJsonParam('bar'));
        self::assertFalse($request6->hasParsedJsonParam('null'));
        self::assertTrue($request6->hasParsedJsonParam('test2'));
        self::assertTrue($request6->hasParsedJsonParam('foo2'));

        self::assertTrue($request7->hasParsedJsonParam('test'));
        self::assertTrue($request7->hasParsedJsonParam('foo'));
        self::assertTrue($request7->hasParsedJsonParam('bar'));
        self::assertTrue($request7->hasParsedJsonParam('null'));
        self::assertTrue($request7->hasParsedJsonParam('test3'));

        self::assertTrue($request8->hasParsedJsonParam('test'));
        self::assertTrue($request8->hasParsedJsonParam('foo'));
        self::assertTrue($request8->hasParsedJsonParam('bar'));
        self::assertTrue($request8->hasParsedJsonParam('null'));
        self::assertTrue($request8->hasParsedJsonParam('test3'));

        self::assertSame('foo', $request3->getParsedJsonParam('test'));
        self::assertSame('foo', $request3->getParsedBodyParam('test'));
        self::assertSame(2, $request3->getParsedJsonParam('foo'));
        self::assertSame([], $request3->getParsedJsonParam('bar'));
        self::assertNull($request3->getParsedJsonParam('null'));

        self::assertSame('foo', $request4->getParsedJsonParam('test'));
        self::assertSame('notFoo', $request4->getParsedBodyParam('test'));

        self::assertSame('bar', $request5->getParsedJsonParam('test2'));
        self::assertSame(1, $request5->getParsedJsonParam('foo2'));

        self::assertSame('bar', $request6->getParsedJsonParam('test2'));
        self::assertSame(1, $request6->getParsedJsonParam('foo2'));

        self::assertSame('fire', $request7->getParsedJsonParam('test3'));
        self::assertSame('pie', $request8->getParsedJsonParam('test3'));
    }
}
