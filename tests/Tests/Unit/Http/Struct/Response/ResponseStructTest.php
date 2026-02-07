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

namespace Valkyrja\Tests\Unit\Http\Struct\Response;

use Valkyrja\Http\Struct\Contract\StructContract;
use Valkyrja\Http\Struct\Response\Contract\ResponseStructContract;
use Valkyrja\Tests\Classes\Http\Struct\IndexedResponseStructEnum;
use Valkyrja\Tests\Classes\Http\Struct\ResponseStructEnum;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the ResponseStruct.
 */
final class ResponseStructTest extends TestCase
{
    public function testContract(): void
    {
        self::assertMethodExists(ResponseStructContract::class, 'getStructuredData');
        self::assertIsA(StructContract::class, ResponseStructContract::class);
    }

    public function testStruct(): void
    {
        $data                = [
            'first'  => 'test',
            'second' => null,
            'third'  => 'test3',
        ];
        $dataStructured      = $data;
        $data2               = [
            'first'  => 'test',
            'second' => 'test2',
            'third'  => 'test3',
            'fourth' => 'test4',
        ];
        $data2Structured     = [
            'first'  => 'test',
            'second' => 'test2',
            'third'  => 'test3',
        ];
        $data3               = [
            'first' => 'test',
            'third' => 'test3',
        ];
        $data3Structured     = [
            'first' => 'test',
            'third' => 'test3',
        ];
        $dataEmptyStructured = [
            'first'  => null,
            'second' => null,
            'third'  => null,
        ];

        self::assertSame($dataStructured, ResponseStructEnum::getStructuredData(data: $data));
        self::assertSame($data2Structured, ResponseStructEnum::getStructuredData(data: $data2));
        self::assertSame($dataStructured, ResponseStructEnum::getStructuredData(data: $data3));
        self::assertSame($data3Structured, ResponseStructEnum::getStructuredData(data: $data3, includeAll: false));
        self::assertSame($dataEmptyStructured, ResponseStructEnum::getStructuredData(data: []));
        self::assertEmpty(ResponseStructEnum::getStructuredData(data: [], includeAll: false));
    }

    public function testIndexedStruct(): void
    {
        $data                = [
            'first'  => 'test',
            'second' => null,
            'third'  => 'test3',
        ];
        $dataStructured      = [
            1 => 'test',
            2 => null,
            3 => 'test3',
        ];
        $data2               = [
            'first'  => 'test',
            'second' => 'test2',
            'third'  => 'test3',
            'fourth' => 'test4',
        ];
        $data2Structured     = [
            1 => 'test',
            2 => 'test2',
            3 => 'test3',
        ];
        $data3               = [
            'first' => 'test',
            'third' => 'test3',
        ];
        $data3Structured     = [
            1 => 'test',
            3 => 'test3',
        ];
        $dataEmptyStructured = [
            1 => null,
            2 => null,
            3 => null,
        ];

        self::assertSame($dataStructured, IndexedResponseStructEnum::getStructuredData(data: $data));
        self::assertSame($data2Structured, IndexedResponseStructEnum::getStructuredData(data: $data2));
        self::assertSame($dataStructured, IndexedResponseStructEnum::getStructuredData(data: $data3));
        self::assertSame(
            $data3Structured,
            IndexedResponseStructEnum::getStructuredData(data: $data3, includeAll: false)
        );
        self::assertSame($dataEmptyStructured, IndexedResponseStructEnum::getStructuredData(data: []));
        self::assertEmpty(IndexedResponseStructEnum::getStructuredData(data: [], includeAll: false));
    }
}
