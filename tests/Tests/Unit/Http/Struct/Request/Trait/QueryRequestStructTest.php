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

namespace Valkyrja\Tests\Unit\Http\Struct\Request\Trait;

use Valkyrja\Http\Message\Param\QueryParamCollection;
use Valkyrja\Http\Message\Request\ServerRequest;
use Valkyrja\Tests\Classes\Http\Struct\QueryRequestStructEnum;
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