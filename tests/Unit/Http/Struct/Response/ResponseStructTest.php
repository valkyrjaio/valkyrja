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

use Valkyrja\Http\Struct\Contract\Struct;
use Valkyrja\Http\Struct\Response\Contract\ResponseStruct as Contract;
use Valkyrja\Tests\Classes\Http\Struct\TestIndexedResponseStruct;
use Valkyrja\Tests\Classes\Http\Struct\TestResponseStruct;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the ResponseStruct.
 *
 * @author Melech Mizrachi
 */
class ResponseStructTest extends TestCase
{
    public function testContract(): void
    {
        self::assertMethodExists(Contract::class, 'getStructuredData');
        self::assertIsA(Struct::class, Contract::class);
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

        self::assertSame($dataStructured, TestResponseStruct::getStructuredData(data: $data));
        self::assertSame($data2Structured, TestResponseStruct::getStructuredData(data: $data2));
        self::assertSame($dataStructured, TestResponseStruct::getStructuredData(data: $data3));
        self::assertSame($data3Structured, TestResponseStruct::getStructuredData(data: $data3, includeAll: false));
        self::assertSame($dataEmptyStructured, TestResponseStruct::getStructuredData(data: []));
        self::assertEmpty(TestResponseStruct::getStructuredData(data: [], includeAll: false));
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

        self::assertSame($dataStructured, TestIndexedResponseStruct::getStructuredData(data: $data));
        self::assertSame($data2Structured, TestIndexedResponseStruct::getStructuredData(data: $data2));
        self::assertSame($dataStructured, TestIndexedResponseStruct::getStructuredData(data: $data3));
        self::assertSame(
            $data3Structured,
            TestIndexedResponseStruct::getStructuredData(data: $data3, includeAll: false)
        );
        self::assertSame($dataEmptyStructured, TestIndexedResponseStruct::getStructuredData(data: []));
        self::assertEmpty(TestIndexedResponseStruct::getStructuredData(data: [], includeAll: false));
    }
}
