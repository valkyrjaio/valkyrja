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

use Valkyrja\Http\Message\Param\ParsedBodyParamCollection;
use Valkyrja\Http\Message\Request\ServerRequest;
use Valkyrja\Tests\Fixtures\Http\Struct\ParsedBodyRequestStructEnum;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class ParsedBodyRequestStructTest extends TestCase
{
    public function testGetDataFromRequestReturnsOnlyDefinedParsedBodyParams(): void
    {
        $request = new ServerRequest(
            parsedBody: ParsedBodyParamCollection::fromArray([
                'first'  => 'a',
                'second' => 'b',
                'third'  => 'c',
            ]),
        );

        self::assertSame(
            ['first' => 'a', 'second' => 'b', 'third' => 'c'],
            ParsedBodyRequestStructEnum::getDataFromRequest($request),
        );
    }

    public function testDetermineIfRequestContainsExtraDataUsesExceptParams(): void
    {
        $withExtra = new ServerRequest(
            parsedBody: ParsedBodyParamCollection::fromArray([
                'first'  => 'a',
                'second' => 'b',
                'third'  => 'c',
                'fourth' => 'extra',
            ]),
        );
        $withoutExtra = new ServerRequest(
            parsedBody: ParsedBodyParamCollection::fromArray([
                'first'  => 'a',
                'second' => 'b',
                'third'  => 'c',
            ]),
        );

        self::assertTrue(ParsedBodyRequestStructEnum::determineIfRequestContainsExtraData($withExtra));
        self::assertFalse(ParsedBodyRequestStructEnum::determineIfRequestContainsExtraData($withoutExtra));
    }
}
