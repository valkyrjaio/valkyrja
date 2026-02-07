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

namespace Valkyrja\Tests\Unit\Http\Struct\Request;

use JsonException;
use Valkyrja\Http\Message\Request\JsonServerRequest;
use Valkyrja\Http\Message\Request\ServerRequest;
use Valkyrja\Http\Struct\Contract\StructContract;
use Valkyrja\Http\Struct\Request\Contract\RequestStructContract;
use Valkyrja\Http\Struct\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Tests\Classes\Http\Struct\IndexedJsonRequestStructEnum;
use Valkyrja\Tests\Classes\Http\Struct\IndexedParsedBodyRequestStructEnum;
use Valkyrja\Tests\Classes\Http\Struct\IndexedQueryRequestStructEnum;
use Valkyrja\Tests\Classes\Http\Struct\JsonRequestStructEnum;
use Valkyrja\Tests\Classes\Http\Struct\ParsedBodyRequestStructEnum;
use Valkyrja\Tests\Classes\Http\Struct\QueryRequestStructEnum;
use Valkyrja\Tests\Classes\Http\Struct\WithNoRulesQueryRequestStructEnum;
use Valkyrja\Tests\Unit\Abstract\TestCase;

use function array_filter;

use const ARRAY_FILTER_USE_KEY;

/**
 * Test the RequestStruct.
 */
final class RequestStructTest extends TestCase
{
    public function testContract(): void
    {
        self::assertMethodExists(RequestStructContract::class, 'getValidationRules');
        self::assertMethodExists(RequestStructContract::class, 'validate');
        self::assertMethodExists(RequestStructContract::class, 'getDataFromRequest');
        self::assertMethodExists(RequestStructContract::class, 'determineIfRequestContainsExtraData');
        self::assertIsA(StructContract::class, RequestStructContract::class);
    }

    /**
     * @throws JsonException
     */
    public function testStruct(): void
    {
        $request  = new JsonServerRequest(
            query: $query = [
                'first'  => 'first1',
                'second' => 1,
                'third'  => 'third1',
            ],
            parsedBody: $body = [
                'first'  => 'first2',
                'second' => 2,
                'third'  => 'third2',
            ],
            parsedJson: $json = [
                'first'  => 'first3',
                'second' => 3,
                'third'  => 'third3',
            ],
        );
        $request2 = new JsonServerRequest(
            query: $query2 = [
                'first'  => '',
                'second' => 1,
                'third'  => 'third1',
                'fourth' => 'pie',
            ],
            parsedBody: $body2 = [
                'first'  => '',
                'second' => 2,
                'third'  => 'third2',
                'fourth' => 'pie',
            ],
            parsedJson: $json2 = [
                'first'  => '',
                'second' => 3,
                'third'  => 'third3',
                'fourth' => 'pie',
            ],
        );

        self::assertNull(WithNoRulesQueryRequestStructEnum::getValidationRules($request));
        self::assertNotEmpty(QueryRequestStructEnum::getValidationRules($request));
        self::assertNotEmpty(ParsedBodyRequestStructEnum::getValidationRules($request));
        self::assertNotEmpty(JsonRequestStructEnum::getValidationRules($request));

        self::assertNull(WithNoRulesQueryRequestStructEnum::getValidationRules($request2));
        self::assertNotEmpty(QueryRequestStructEnum::getValidationRules($request2));
        self::assertNotEmpty(ParsedBodyRequestStructEnum::getValidationRules($request2));
        self::assertNotEmpty(JsonRequestStructEnum::getValidationRules($request2));

        self::assertFalse(WithNoRulesQueryRequestStructEnum::determineIfRequestContainsExtraData($request));
        self::assertFalse(QueryRequestStructEnum::determineIfRequestContainsExtraData($request));
        self::assertFalse(ParsedBodyRequestStructEnum::determineIfRequestContainsExtraData($request));
        self::assertFalse(JsonRequestStructEnum::determineIfRequestContainsExtraData($request));

        self::assertTrue(WithNoRulesQueryRequestStructEnum::determineIfRequestContainsExtraData($request2));
        self::assertTrue(QueryRequestStructEnum::determineIfRequestContainsExtraData($request2));
        self::assertTrue(ParsedBodyRequestStructEnum::determineIfRequestContainsExtraData($request2));
        self::assertTrue(JsonRequestStructEnum::determineIfRequestContainsExtraData($request2));

        self::assertSame($query, WithNoRulesQueryRequestStructEnum::getDataFromRequest($request));
        self::assertSame($query, QueryRequestStructEnum::getDataFromRequest($request));
        self::assertSame($body, ParsedBodyRequestStructEnum::getDataFromRequest($request));
        self::assertSame($json, JsonRequestStructEnum::getDataFromRequest($request));

        self::assertNotSame($query2, WithNoRulesQueryRequestStructEnum::getDataFromRequest($request2));
        self::assertNotSame($query2, QueryRequestStructEnum::getDataFromRequest($request2));
        self::assertNotSame($body2, ParsedBodyRequestStructEnum::getDataFromRequest($request2));
        self::assertNotSame($json2, JsonRequestStructEnum::getDataFromRequest($request2));

        $callback = static fn (string $key): bool => $key !== 'fourth';

        self::assertSame(
            array_filter($query2, $callback, ARRAY_FILTER_USE_KEY),
            QueryRequestStructEnum::getDataFromRequest($request2)
        );
        self::assertSame(
            array_filter($body2, $callback, ARRAY_FILTER_USE_KEY),
            ParsedBodyRequestStructEnum::getDataFromRequest($request2)
        );
        self::assertSame(
            array_filter($json2, $callback, ARRAY_FILTER_USE_KEY),
            JsonRequestStructEnum::getDataFromRequest($request2)
        );

        $validateQuery = QueryRequestStructEnum::validate($request);
        $validateBody  = ParsedBodyRequestStructEnum::validate($request);
        $validateJson  = JsonRequestStructEnum::validate($request);

        self::assertTrue($validateQuery->rules());
        self::assertTrue($validateBody->rules());
        self::assertTrue($validateJson->rules());

        $validateQuery2 = QueryRequestStructEnum::validate($request2);
        $validateBody2  = ParsedBodyRequestStructEnum::validate($request2);
        $validateJson2  = JsonRequestStructEnum::validate($request2);

        self::assertFalse($validateQuery2->rules());
        self::assertFalse($validateBody2->rules());
        self::assertFalse($validateJson2->rules());
    }

    /**
     * @throws JsonException
     */
    public function testIndexedStruct(): void
    {
        $request = new JsonServerRequest(
            query: $query = [
                1 => 'first1',
                2 => 1,
                3 => 'third1',
            ],
            parsedBody: $body = [
                1 => 'first2',
                2 => 2,
                3 => 'third2',
            ],
            parsedJson: $json = [
                1 => 'first3',
                2 => 3,
                3 => 'third3',
            ],
        );

        $request2 = new JsonServerRequest(
            query: $query2 = [
                1 => '',
                2 => 1,
                3 => 'third1',
                4 => 'pie',
            ],
            parsedBody: $body2 = [
                1 => '',
                2 => 2,
                3 => 'third2',
                4 => 'pie',
            ],
            parsedJson: $json2 = [
                1 => '',
                2 => 3,
                3 => 'third3',
                4 => 'pie',
            ],
        );

        self::assertNotEmpty(IndexedQueryRequestStructEnum::getValidationRules($request));
        self::assertNotEmpty(IndexedParsedBodyRequestStructEnum::getValidationRules($request));
        self::assertNotEmpty(IndexedJsonRequestStructEnum::getValidationRules($request));

        self::assertNotEmpty(IndexedQueryRequestStructEnum::getValidationRules($request2));
        self::assertNotEmpty(IndexedParsedBodyRequestStructEnum::getValidationRules($request2));
        self::assertNotEmpty(IndexedJsonRequestStructEnum::getValidationRules($request2));

        self::assertFalse(IndexedQueryRequestStructEnum::determineIfRequestContainsExtraData($request));
        self::assertFalse(IndexedParsedBodyRequestStructEnum::determineIfRequestContainsExtraData($request));
        self::assertFalse(IndexedJsonRequestStructEnum::determineIfRequestContainsExtraData($request));

        self::assertTrue(IndexedQueryRequestStructEnum::determineIfRequestContainsExtraData($request2));
        self::assertTrue(IndexedParsedBodyRequestStructEnum::determineIfRequestContainsExtraData($request2));
        self::assertTrue(IndexedJsonRequestStructEnum::determineIfRequestContainsExtraData($request2));

        self::assertSame($query, IndexedQueryRequestStructEnum::getDataFromRequest($request));
        self::assertSame($body, IndexedParsedBodyRequestStructEnum::getDataFromRequest($request));
        self::assertSame($json, IndexedJsonRequestStructEnum::getDataFromRequest($request));

        self::assertNotSame($query2, IndexedQueryRequestStructEnum::getDataFromRequest($request2));
        self::assertNotSame($body2, IndexedParsedBodyRequestStructEnum::getDataFromRequest($request2));
        self::assertNotSame($json2, IndexedJsonRequestStructEnum::getDataFromRequest($request2));

        $callback = static fn (int $key): bool => $key !== 4;

        self::assertSame(
            array_filter($query2, $callback, ARRAY_FILTER_USE_KEY),
            IndexedQueryRequestStructEnum::getDataFromRequest($request2)
        );
        self::assertSame(
            array_filter($body2, $callback, ARRAY_FILTER_USE_KEY),
            IndexedParsedBodyRequestStructEnum::getDataFromRequest($request2)
        );
        self::assertSame(
            array_filter($json2, $callback, ARRAY_FILTER_USE_KEY),
            IndexedJsonRequestStructEnum::getDataFromRequest($request2)
        );

        $validateQuery = IndexedQueryRequestStructEnum::validate($request);
        $validateBody  = IndexedParsedBodyRequestStructEnum::validate($request);
        $validateJson  = IndexedJsonRequestStructEnum::validate($request);

        self::assertTrue($validateQuery->rules());
        self::assertTrue($validateBody->rules());
        self::assertTrue($validateJson->rules());

        $validateQuery2 = IndexedQueryRequestStructEnum::validate($request2);
        $validateBody2  = IndexedParsedBodyRequestStructEnum::validate($request2);
        $validateJson2  = IndexedJsonRequestStructEnum::validate($request2);

        self::assertFalse($validateQuery2->rules());
        self::assertFalse($validateBody2->rules());
        self::assertFalse($validateJson2->rules());
    }

    public function testJsonRequestStructException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $request = new ServerRequest();

        IndexedJsonRequestStructEnum::getDataFromRequest($request);
    }
}
