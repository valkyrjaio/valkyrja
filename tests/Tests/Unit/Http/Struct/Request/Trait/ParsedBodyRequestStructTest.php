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

use Valkyrja\Http\Message\Param\ParsedBodyParamCollection;
use Valkyrja\Http\Message\Request\ServerRequest;
use Valkyrja\Tests\Classes\Http\Struct\ParsedBodyRequestStructEnum;
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
