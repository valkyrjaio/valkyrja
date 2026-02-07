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

namespace Valkyrja\Tests\Unit\Type\Array\Factory;

use JsonException;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\Array\Factory\ArrayFactory;
use Valkyrja\Type\Throwable\Exception\RuntimeException;

use function PHPUnit\Framework\assertSame;

final class ArrayFactoryTest extends TestCase
{
    /** @var array[] */
    protected const array VALUE = [
        'one'      => [
            'two' => [
                'three' => 'test',
            ],
        ],
        'notarray' => 'notarrayvalue',
    ];

    public function testGetValueDotNotation(): void
    {
        $default = 'default';

        $result        = ArrayFactory::getValueDotNotation(self::VALUE, 'one.two.three', $default);
        $resultDefault = ArrayFactory::getValueDotNotation(self::VALUE, 'one.two.non_existent', $default);

        $resultDefaultNonArray = ArrayFactory::getValueDotNotation(self::VALUE, 'notarray.nonexistent', $default);

        self::assertSame(self::VALUE['one']['two']['three'], $result);
        self::assertSame($default, $resultDefault);
        self::assertSame($default, $resultDefaultNonArray);
    }

    /**
     * @throws JsonException
     */
    public function testToString(): void
    {
        $arrEmpty    = [];
        $result      = ArrayFactory::toString(self::VALUE);
        $resultEmpty = ArrayFactory::toString($arrEmpty);

        self::assertSame(json_encode(self::VALUE), $result);
        self::assertSame(json_encode($arrEmpty), $resultEmpty);
    }

    /**
     * @throws JsonException
     */
    public function testFromString(): void
    {
        $arrEmpty    = [];
        $result      = ArrayFactory::fromString(json_encode(self::VALUE));
        $resultEmpty = ArrayFactory::fromString(json_encode($arrEmpty));

        self::assertSame(self::VALUE, $result);
        self::assertSame($arrEmpty, $resultEmpty);
    }

    /**
     * @throws JsonException
     */
    public function testFromStringInvalidString(): void
    {
        $this->expectException(RuntimeException::class);

        ArrayFactory::fromString('"validbutnotarray"');
    }

    public function testWithoutNull(): void
    {
        $arrWithNull         = self::VALUE;
        $arrWithNull['null'] = null;

        self::assertArrayHasKey('null', $arrWithNull);

        $arrWithoutNull = ArrayFactory::withoutNull($arrWithNull);

        self::assertArrayNotHasKey('null', $arrWithNull);
        // Should be the same array
        self::assertArrayNotHasKey('null', $arrWithoutNull);
    }

    public function testNewWithoutNull(): void
    {
        $arrWithNull         = self::VALUE;
        $arrWithNull['null'] = null;

        self::assertArrayHasKey('null', $arrWithNull);

        $arrWithoutNull = ArrayFactory::newWithoutNull($arrWithNull);

        self::assertArrayHasKey('null', $arrWithNull);
        // Should be a new array with the original unmodified
        self::assertArrayNotHasKey('null', $arrWithoutNull);
    }

    public function testFilterEmptyStrings(): void
    {
        $arr = ['', 'test'];

        $filtered = ArrayFactory::filterEmptyStrings(...$arr);

        assertSame([1 => 'test'], $filtered);
    }
}
