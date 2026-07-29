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

use Valkyrja\Http\Message\Param\ParsedJsonParamCollection;
use Valkyrja\Http\Message\Request\JsonServerRequest;
use Valkyrja\Http\Message\Request\ServerRequest;
use Valkyrja\Http\Struct\Throwable\Exception\HttpStructJsonServerRequestExpectedException;
use Valkyrja\Tests\Fixtures\Http\Struct\JsonRequestStructEnum;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class JsonRequestStructTest extends TestCase
{
    public function testGetDataFromRequestReturnsOnlyDefinedJsonParams(): void
    {
        $request = $this->request([
            'first'  => 'a',
            'second' => 'b',
            'third'  => 'c',
        ]);

        self::assertSame(
            ['first' => 'a', 'second' => 'b', 'third' => 'c'],
            JsonRequestStructEnum::getDataFromRequest($request),
        );
    }

    public function testDetermineIfRequestContainsExtraDataUsesExceptParams(): void
    {
        $withExtra = $this->request([
            'first'  => 'a',
            'second' => 'b',
            'third'  => 'c',
            'fourth' => 'extra',
        ]);
        $withoutExtra = $this->request([
            'first'  => 'a',
            'second' => 'b',
            'third'  => 'c',
        ]);

        self::assertTrue(JsonRequestStructEnum::determineIfRequestContainsExtraData($withExtra));
        self::assertFalse(JsonRequestStructEnum::determineIfRequestContainsExtraData($withoutExtra));
    }

    public function testThrowsWhenRequestIsNotJsonServerRequest(): void
    {
        $this->expectException(HttpStructJsonServerRequestExpectedException::class);

        JsonRequestStructEnum::getDataFromRequest(new ServerRequest());
    }

    /**
     * @param array<array-key, mixed> $json
     */
    private function request(array $json): JsonServerRequest
    {
        return new JsonServerRequest(
            parsedJson: ParsedJsonParamCollection::fromArray($json),
        );
    }
}
