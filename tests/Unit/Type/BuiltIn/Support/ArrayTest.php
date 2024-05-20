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

namespace Valkyrja\Tests\Unit\Type\BuiltIn\Support;

use JsonException;
use Valkyrja\Tests\Unit\TestCase;
use Valkyrja\Type\BuiltIn\Support\Arr;

class ArrayTest extends TestCase
{
    protected const VALUE = [
        'one' => [
            'two' => [
                'three' => 'test',
            ],
        ],
    ];

    public function testGetValueDotNotation(): void
    {
        $default = 'default';

        $result        = Arr::getValueDotNotation(self::VALUE, 'one.two.three', $default);
        $resultDefault = Arr::getValueDotNotation(self::VALUE, 'one.two.non_existent', $default);

        self::assertSame(self::VALUE['one']['two']['three'], $result);
        self::assertSame($default, $resultDefault);
    }

    /**
     * @throws JsonException
     */
    public function testToString(): void
    {
        $arrEmpty    = [];
        $result      = Arr::toString(self::VALUE);
        $resultEmpty = Arr::toString($arrEmpty);

        self::assertSame(json_encode(self::VALUE), $result);
        self::assertSame(json_encode($arrEmpty), $resultEmpty);
    }

    /**
     * @throws JsonException
     */
    public function testFromString(): void
    {
        $arrEmpty    = [];
        $result      = Arr::fromString(json_encode(self::VALUE));
        $resultEmpty = Arr::fromString(json_encode($arrEmpty));

        self::assertSame(self::VALUE, $result);
        self::assertSame($arrEmpty, $resultEmpty);
    }

    public function testWithoutNull(): void
    {
        $arrWithNull         = self::VALUE;
        $arrWithNull['null'] = null;

        self::assertArrayHasKey('null', $arrWithNull);

        $arrWithoutNull = Arr::withoutNull($arrWithNull);

        self::assertArrayNotHasKey('null', $arrWithNull);
        // Should be the same array
        self::assertArrayNotHasKey('null', $arrWithoutNull);
    }

    public function testNewWithoutNull(): void
    {
        $arrWithNull         = self::VALUE;
        $arrWithNull['null'] = null;

        self::assertArrayHasKey('null', $arrWithNull);

        $arrWithoutNull = Arr::newWithoutNull($arrWithNull);

        self::assertArrayHasKey('null', $arrWithNull);
        // Should be a new array with the original unmodified
        self::assertArrayNotHasKey('null', $arrWithoutNull);
    }
}
