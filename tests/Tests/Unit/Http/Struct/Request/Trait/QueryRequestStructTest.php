<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Http\Struct\Request\Trait;

use Valkyrja\Http\Message\Param\QueryParamCollection;
use Valkyrja\Http\Message\Request\ServerRequest;
use Valkyrja\Tests\Fixtures\Http\Struct\QueryRequestStructEnum;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class QueryRequestStructTest extends TestCase
{
    public function testGetDataFromRequestReturnsOnlyDefinedQueryParams(): void
    {
        $request = new ServerRequest(
            query: QueryParamCollection::fromArray([
                'first'  => 'a',
                'second' => 'b',
                'third'  => 'c',
            ]),
        );

        self::assertSame(
            ['first' => 'a', 'second' => 'b', 'third' => 'c'],
            QueryRequestStructEnum::getDataFromRequest($request),
        );
    }

    public function testDetermineIfRequestContainsExtraDataUsesExceptParams(): void
    {
        $withExtra = new ServerRequest(
            query: QueryParamCollection::fromArray([
                'first'  => 'a',
                'second' => 'b',
                'third'  => 'c',
                'fourth' => 'extra',
            ]),
        );
        $withoutExtra = new ServerRequest(
            query: QueryParamCollection::fromArray([
                'first'  => 'a',
                'second' => 'b',
                'third'  => 'c',
            ]),
        );

        self::assertTrue(QueryRequestStructEnum::determineIfRequestContainsExtraData($withExtra));
        self::assertFalse(QueryRequestStructEnum::determineIfRequestContainsExtraData($withoutExtra));
    }
}
