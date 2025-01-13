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
use Valkyrja\Http\Struct\Contract\Struct;
use Valkyrja\Http\Struct\Exception\InvalidArgumentException;
use Valkyrja\Http\Struct\Request\Contract\RequestStruct as Contract;
use Valkyrja\Tests\Classes\Http\Struct\TestIndexedJsonRequestStruct;
use Valkyrja\Tests\Classes\Http\Struct\TestIndexedParsedBodyRequestStruct;
use Valkyrja\Tests\Classes\Http\Struct\TestIndexedQueryRequestStruct;
use Valkyrja\Tests\Classes\Http\Struct\TestJsonRequestStruct;
use Valkyrja\Tests\Classes\Http\Struct\TestParsedBodyRequestStruct;
use Valkyrja\Tests\Classes\Http\Struct\TestQueryRequestStruct;
use Valkyrja\Tests\Classes\Http\Struct\TestWithNoRulesQueryRequestStruct;
use Valkyrja\Tests\Unit\TestCase;

use function array_filter;

use const ARRAY_FILTER_USE_KEY;

/**
 * Test the RequestStruct.
 *
 * @author Melech Mizrachi
 */
class RequestStructTest extends TestCase
{
    public function testContract(): void
    {
        self::assertMethodExists(Contract::class, 'getValidationRules');
        self::assertMethodExists(Contract::class, 'validate');
        self::assertMethodExists(Contract::class, 'getDataFromRequest');
        self::assertMethodExists(Contract::class, 'determineIfRequestContainsExtraData');
        self::assertIsA(Struct::class, Contract::class);
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

        self::assertNull(TestWithNoRulesQueryRequestStruct::getValidationRules($request));
        self::assertNotEmpty(TestQueryRequestStruct::getValidationRules($request));
        self::assertNotEmpty(TestParsedBodyRequestStruct::getValidationRules($request));
        self::assertNotEmpty(TestJsonRequestStruct::getValidationRules($request));

        self::assertNull(TestWithNoRulesQueryRequestStruct::getValidationRules($request2));
        self::assertNotEmpty(TestQueryRequestStruct::getValidationRules($request2));
        self::assertNotEmpty(TestParsedBodyRequestStruct::getValidationRules($request2));
        self::assertNotEmpty(TestJsonRequestStruct::getValidationRules($request2));

        self::assertFalse(TestWithNoRulesQueryRequestStruct::determineIfRequestContainsExtraData($request));
        self::assertFalse(TestQueryRequestStruct::determineIfRequestContainsExtraData($request));
        self::assertFalse(TestParsedBodyRequestStruct::determineIfRequestContainsExtraData($request));
        self::assertFalse(TestJsonRequestStruct::determineIfRequestContainsExtraData($request));

        self::assertTrue(TestWithNoRulesQueryRequestStruct::determineIfRequestContainsExtraData($request2));
        self::assertTrue(TestQueryRequestStruct::determineIfRequestContainsExtraData($request2));
        self::assertTrue(TestParsedBodyRequestStruct::determineIfRequestContainsExtraData($request2));
        self::assertTrue(TestJsonRequestStruct::determineIfRequestContainsExtraData($request2));

        self::assertSame($query, TestWithNoRulesQueryRequestStruct::getDataFromRequest($request));
        self::assertSame($query, TestQueryRequestStruct::getDataFromRequest($request));
        self::assertSame($body, TestParsedBodyRequestStruct::getDataFromRequest($request));
        self::assertSame($json, TestJsonRequestStruct::getDataFromRequest($request));

        self::assertNotSame($query2, TestWithNoRulesQueryRequestStruct::getDataFromRequest($request2));
        self::assertNotSame($query2, TestQueryRequestStruct::getDataFromRequest($request2));
        self::assertNotSame($body2, TestParsedBodyRequestStruct::getDataFromRequest($request2));
        self::assertNotSame($json2, TestJsonRequestStruct::getDataFromRequest($request2));

        $callback = static fn (string $key): bool => $key !== 'fourth';

        self::assertSame(
            array_filter($query2, $callback, ARRAY_FILTER_USE_KEY),
            TestQueryRequestStruct::getDataFromRequest($request2)
        );
        self::assertSame(
            array_filter($body2, $callback, ARRAY_FILTER_USE_KEY),
            TestParsedBodyRequestStruct::getDataFromRequest($request2)
        );
        self::assertSame(
            array_filter($json2, $callback, ARRAY_FILTER_USE_KEY),
            TestJsonRequestStruct::getDataFromRequest($request2)
        );

        $validateQuery = TestQueryRequestStruct::validate($request);
        $validateBody  = TestParsedBodyRequestStruct::validate($request);
        $validateJson  = TestJsonRequestStruct::validate($request);

        self::assertTrue($validateQuery->rules());
        self::assertTrue($validateBody->rules());
        self::assertTrue($validateJson->rules());

        $validateQuery2 = TestQueryRequestStruct::validate($request2);
        $validateBody2  = TestParsedBodyRequestStruct::validate($request2);
        $validateJson2  = TestJsonRequestStruct::validate($request2);

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

        self::assertNotEmpty(TestIndexedQueryRequestStruct::getValidationRules($request));
        self::assertNotEmpty(TestIndexedParsedBodyRequestStruct::getValidationRules($request));
        self::assertNotEmpty(TestIndexedJsonRequestStruct::getValidationRules($request));

        self::assertNotEmpty(TestIndexedQueryRequestStruct::getValidationRules($request2));
        self::assertNotEmpty(TestIndexedParsedBodyRequestStruct::getValidationRules($request2));
        self::assertNotEmpty(TestIndexedJsonRequestStruct::getValidationRules($request2));

        self::assertFalse(TestIndexedQueryRequestStruct::determineIfRequestContainsExtraData($request));
        self::assertFalse(TestIndexedParsedBodyRequestStruct::determineIfRequestContainsExtraData($request));
        self::assertFalse(TestIndexedJsonRequestStruct::determineIfRequestContainsExtraData($request));

        self::assertTrue(TestIndexedQueryRequestStruct::determineIfRequestContainsExtraData($request2));
        self::assertTrue(TestIndexedParsedBodyRequestStruct::determineIfRequestContainsExtraData($request2));
        self::assertTrue(TestIndexedJsonRequestStruct::determineIfRequestContainsExtraData($request2));

        self::assertSame($query, TestIndexedQueryRequestStruct::getDataFromRequest($request));
        self::assertSame($body, TestIndexedParsedBodyRequestStruct::getDataFromRequest($request));
        self::assertSame($json, TestIndexedJsonRequestStruct::getDataFromRequest($request));

        self::assertNotSame($query2, TestIndexedQueryRequestStruct::getDataFromRequest($request2));
        self::assertNotSame($body2, TestIndexedParsedBodyRequestStruct::getDataFromRequest($request2));
        self::assertNotSame($json2, TestIndexedJsonRequestStruct::getDataFromRequest($request2));

        $callback = static fn (int $key): bool => $key !== 4;

        self::assertSame(
            array_filter($query2, $callback, ARRAY_FILTER_USE_KEY),
            TestIndexedQueryRequestStruct::getDataFromRequest($request2)
        );
        self::assertSame(
            array_filter($body2, $callback, ARRAY_FILTER_USE_KEY),
            TestIndexedParsedBodyRequestStruct::getDataFromRequest($request2)
        );
        self::assertSame(
            array_filter($json2, $callback, ARRAY_FILTER_USE_KEY),
            TestIndexedJsonRequestStruct::getDataFromRequest($request2)
        );

        $validateQuery = TestIndexedQueryRequestStruct::validate($request);
        $validateBody  = TestIndexedParsedBodyRequestStruct::validate($request);
        $validateJson  = TestIndexedJsonRequestStruct::validate($request);

        self::assertTrue($validateQuery->rules());
        self::assertTrue($validateBody->rules());
        self::assertTrue($validateJson->rules());

        $validateQuery2 = TestIndexedQueryRequestStruct::validate($request2);
        $validateBody2  = TestIndexedParsedBodyRequestStruct::validate($request2);
        $validateJson2  = TestIndexedJsonRequestStruct::validate($request2);

        self::assertFalse($validateQuery2->rules());
        self::assertFalse($validateBody2->rules());
        self::assertFalse($validateJson2->rules());
    }

    public function testJsonRequestStructException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $request = new ServerRequest();

        TestIndexedJsonRequestStruct::getDataFromRequest($request);
    }
}
