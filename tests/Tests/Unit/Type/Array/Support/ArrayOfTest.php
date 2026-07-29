<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Type\Array\Support;

use Throwable;
use Valkyrja\Tests\Fixtures\Enum\EnumFixture;
use Valkyrja\Tests\Fixtures\Enum\IntEnum;
use Valkyrja\Tests\Fixtures\Enum\StringEnum;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\Array\Support\ArrayOf;

final class ArrayOfTest extends TestCase
{
    /**
     * Test the ArrayOf::strings() methods.
     */
    public function testStrings(): void
    {
        $this->testMethods([ArrayOf::class, 'strings'], strings: false);
    }

    /**
     * Test the ArrayOf::int() methods.
     */
    public function testInts(): void
    {
        $this->testMethods([ArrayOf::class, 'ints'], ints: false);
    }

    /**
     * Test the ArrayOf::floats() methods.
     */
    public function testFloats(): void
    {
        // Integers are also valid floats
        $this->testMethods([ArrayOf::class, 'floats'], ints: false, floats: false);
    }

    /**
     * Test the ArrayOf::booleans() methods.
     */
    public function testBooleans(): void
    {
        // True is also a valid boolean
        // False is also a valid boolean
        $this->testMethods([ArrayOf::class, 'booleans'], booleans: false, true: false, false: false);
    }

    /**
     * Test the ArrayOf::true() methods.
     */
    public function testTrue(): void
    {
        $this->testMethods([ArrayOf::class, 'true'], true: false);
    }

    /**
     * Test the ArrayOf::false() methods.
     */
    public function testFalse(): void
    {
        $this->testMethods([ArrayOf::class, 'false'], false: false);
    }

    /**
     * Test the ArrayOf::null() methods.
     */
    public function testNull(): void
    {
        $this->testMethods([ArrayOf::class, 'null'], null: false);
    }

    /**
     * Test the ArrayOf::arrays() methods.
     */
    public function testArrays(): void
    {
        $this->testMethods([ArrayOf::class, 'arrays'], arrays: false);
    }

    /**
     * Test the ArrayOf::objects() methods.
     */
    public function testObjects(): void
    {
        // UnitEnum is also a valid object
        // BackedEnum is also a valid object
        $this->testMethods([ArrayOf::class, 'objects'], objects: false, enums: false, backedEnums: false);
    }

    /**
     * Test the ArrayOf::enum() methods.
     */
    public function testEnums(): void
    {
        // BackedEnum is also a valid UnitEnum
        $this->testMethods([ArrayOf::class, 'enums'], enums: false, backedEnums: false);
    }

    /**
     * Test the ArrayOf::backedEnum() methods.
     */
    public function testBackedEnums(): void
    {
        $this->testMethods([ArrayOf::class, 'backedEnums'], backedEnums: false);
    }

    /**
     * Test the ArrayOf::___() methods.
     */
    protected function testMethods(
        callable $method,
        bool $strings = true,
        bool $ints = true,
        bool $floats = true,
        bool $booleans = true,
        bool $true = true,
        bool $false = true,
        bool $null = true,
        bool $arrays = true,
        bool $objects = true,
        bool $enums = true,
        bool $backedEnums = true
    ): void {
        $stringsThrown     = false;
        $intsThrown        = false;
        $floatsThrown      = false;
        $booleansThrown    = false;
        $trueThrown        = false;
        $falseThrown       = false;
        $nullThrown        = false;
        $arraysThrown      = false;
        $objectsThrown     = false;
        $enumsThrown       = false;
        $backedEnumsThrown = false;

        try {
            $method(...$this->getArrayOfStrings());
        } catch (Throwable) {
            $stringsThrown = true;
        }

        try {
            $method(...$this->getArrayOfInts());
        } catch (Throwable) {
            $intsThrown = true;
        }

        try {
            $method(...$this->getArrayOfFloats());
        } catch (Throwable) {
            $floatsThrown = true;
        }

        try {
            $method(...$this->getArrayOfBooleans());
        } catch (Throwable) {
            $booleansThrown = true;
        }

        try {
            $method(...$this->getArrayOfTrue());
        } catch (Throwable) {
            $trueThrown = true;
        }

        try {
            $method(...$this->getArrayOfFalse());
        } catch (Throwable) {
            $falseThrown = true;
        }

        try {
            $method(...$this->getArrayOfNull());
        } catch (Throwable) {
            $nullThrown = true;
        }

        try {
            $method(...$this->getArrayOfArrays());
        } catch (Throwable) {
            $arraysThrown = true;
        }

        try {
            $method(...$this->getArrayOfObjects());
        } catch (Throwable) {
            $objectsThrown = true;
        }

        try {
            $method(...$this->getArrayOfEnums());
        } catch (Throwable) {
            $enumsThrown = true;
        }

        try {
            $method(...$this->getArrayOfBackedEnums());
        } catch (Throwable) {
            $backedEnumsThrown = true;
        }

        self::assertSame($strings, $stringsThrown);
        self::assertSame($ints, $intsThrown);
        self::assertSame($floats, $floatsThrown);
        self::assertSame($booleans, $booleansThrown);
        self::assertSame($true, $trueThrown);
        self::assertSame($false, $falseThrown);
        self::assertSame($null, $nullThrown);
        self::assertSame($arrays, $arraysThrown);
        self::assertSame($objects, $objectsThrown);
        self::assertSame($enums, $enumsThrown);
        self::assertSame($backedEnums, $backedEnumsThrown);
    }

    /**
     * Get an array of strings.
     *
     * @return string[]
     */
    protected function getArrayOfStrings(): array
    {
        return ['fire', 'blah', 'foo'];
    }

    /**
     * Get an array of integers.
     *
     * @return int[]
     */
    protected function getArrayOfInts(): array
    {
        return [1, 2, 3];
    }

    /**
     * Get an array of floats.
     *
     * @return float[]
     */
    protected function getArrayOfFloats(): array
    {
        return [1.0, 2.2, 3.4];
    }

    /**
     * Get an array of booleans.
     *
     * @return bool[]
     */
    protected function getArrayOfBooleans(): array
    {
        return [true, false, true];
    }

    /**
     * Get an array of true.
     *
     * @return true[]
     */
    protected function getArrayOfTrue(): array
    {
        return [true, true, true];
    }

    /**
     * Get an array of false.
     *
     * @return false[]
     */
    protected function getArrayOfFalse(): array
    {
        return [false, false, false];
    }

    /**
     * Get an array of null.
     *
     * @return null[]
     */
    protected function getArrayOfNull(): array
    {
        return [null, null, null];
    }

    /**
     * Get an array of arrays.
     *
     * @return array<int, array<array-key, mixed>>
     */
    protected function getArrayOfArrays(): array
    {
        return [[], [], []];
    }

    /**
     * Get an array of objects.
     *
     * @return object[]
     */
    protected function getArrayOfObjects(): array
    {
        return [
            new class {
            },
            new class {
            },
            new class {
            },
        ];
    }

    /**
     * Get an array of enums.
     *
     * @return EnumFixture[]
     */
    protected function getArrayOfEnums(): array
    {
        return [
            EnumFixture::heart,
            EnumFixture::club,
            EnumFixture::diamond,
        ];
    }

    /**
     * Get an array of backed enums.
     *
     * @return array<int, IntEnum|StringEnum>
     */
    protected function getArrayOfBackedEnums(): array
    {
        return [
            IntEnum::second,
            StringEnum::foo,
            IntEnum::first,
        ];
    }
}
