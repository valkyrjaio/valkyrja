<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Attribute\Collector;

use ReflectionException;
use Valkyrja\Attribute\Collector\Collector;
use Valkyrja\Tests\Fixtures\Attribute\AttributeClassChildFixture;
use Valkyrja\Tests\Fixtures\Attribute\AttributedFixture;
use Valkyrja\Tests\Fixtures\Attribute\AttributeFixture;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the Collector service.
 */
final class CollectorTest extends TestCase
{
    public const int VALUE1  = 1;
    public const int VALUE2  = 2;
    public const int VALUE3  = 3;
    public const int VALUE4  = 4;
    public const int VALUE5  = 5;
    public const int VALUE6  = 6;
    public const int VALUE7  = 7;
    public const int VALUE8  = 8;
    public const int VALUE9  = 9;
    public const int VALUE10 = 10;
    public const int VALUE11 = 11;
    public const int VALUE12 = 12;
    public const int VALUE13 = 13;
    public const int VALUE14 = 14;
    public const int VALUE15 = 15;
    public const int VALUE16 = 16;
    public const int VALUE17 = 17;
    public const int VALUE18 = 18;
    public const int VALUE19 = 19;
    public const int VALUE20 = 20;
    public const int VALUE21 = 21;

    public const string THREE      = 'three';
    public const string SIX        = 'six';
    public const string NINE       = 'nine';
    public const string TWELVE     = 'twelve';
    public const string FIFTEEN    = 'fifteen';
    public const string EIGHTEEN   = 'eighteen';
    public const string TWENTY_ONE = 'twenty one';

    protected const string CONST_NAME                  = 'CONST';
    protected const string PROTECTED_CONST_NAME        = 'PROTECTED_CONST';
    protected const string STATIC_PROPERTY_NAME        = 'staticProperty';
    protected const string PROPERTY_NAME               = 'property';
    protected const string STATIC_METHOD_NAME          = 'staticMethod';
    protected const string METHOD_NAME                 = 'method';
    protected const string METHOD_WITH_PARAMETERS_NAME = 'methodWithParameter';

    /**
     * The attributes service.
     *
     * @var Collector
     */
    protected Collector $attributes;

    /**
     * The class  to test with.
     *
     * @var AttributedFixture
     */
    protected AttributedFixture $class;

    /**
     * Setup the test.
     */
    protected function setUp(): void
    {
        $this->attributes = new Collector();
        $this->class      = new AttributedFixture();
    }

    /**
     * Test the forClass() method.
     *
     * @throws ReflectionException
     */
    public function testForClass(): void
    {
        $attributes = $this->attributes->forClass(AttributedFixture::class, AttributeFixture::class);

        $this->baseTests(...$attributes);
        $this->valueTests(
            self::VALUE1,
            self::VALUE2,
            self::VALUE3,
            self::THREE,
            ...$attributes
        );
    }

    /**
     * Test the forClassMembers() method.
     *
     * @throws ReflectionException
     */
    public function testForClassMembers(): void
    {
        $attributes = $this->attributes->forClassMembers(AttributedFixture::class, AttributeFixture::class);

        self::assertCount(21, $attributes);
        $this->testsForConst($attributes[0], $attributes[1], $attributes[2]);
        $this->testsForProtectedConst($attributes[3], $attributes[4], $attributes[5]);
        $this->testsForStaticProperty($attributes[6], $attributes[7], $attributes[8]);
        $this->testsForProperty($attributes[9], $attributes[10], $attributes[11]);
        $this->testsForStaticMethod($attributes[12], $attributes[13], $attributes[14]);
        $this->testsForMethod($attributes[15], $attributes[16], $attributes[17]);
        $this->testsForMethod($attributes[18], $attributes[19], $attributes[20]);
    }

    /**
     * Test the forClassMembers() method.
     *
     * @throws ReflectionException
     */
    public function testForClassAndMembers(): void
    {
        $attributes = $this->attributes->forClassAndMembers(AttributedFixture::class, AttributeFixture::class);

        self::assertCount(24, $attributes);
        $this->baseTests($attributes[0], $attributes[1], $attributes[2]);
        $this->testsForConst($attributes[3], $attributes[4], $attributes[5]);
        $this->testsForProtectedConst($attributes[6], $attributes[7], $attributes[8]);
        $this->testsForStaticProperty($attributes[9], $attributes[10], $attributes[11]);
        $this->testsForProperty($attributes[12], $attributes[13], $attributes[14]);
        $this->testsForStaticMethod($attributes[15], $attributes[16], $attributes[17]);
        $this->testsForMethod($attributes[18], $attributes[19], $attributes[20]);
        $this->testsForMethod($attributes[21], $attributes[22], $attributes[23]);
    }

    /**
     * Test the forConstant() method.
     *
     * @throws ReflectionException
     */
    public function testForConstant(): void
    {
        $attributes = $this->attributes->forConstant(
            AttributedFixture::class,
            self::CONST_NAME,
            AttributeFixture::class
        );

        $this->testsForConst(...$attributes);

        $attributes = $this->attributes->forConstant(
            AttributedFixture::class,
            self::PROTECTED_CONST_NAME,
            AttributeFixture::class
        );

        $this->testsForProtectedConst(...$attributes);
    }

    /**
     * Test the forConstants() method.
     *
     * @throws ReflectionException
     */
    public function testForConstants(): void
    {
        $attributes = $this->attributes->forConstants(AttributedFixture::class, AttributeFixture::class);

        self::assertCount(6, $attributes);
        $this->testsForConst($attributes[0], $attributes[1], $attributes[2]);
        $this->testsForProtectedConst($attributes[3], $attributes[4], $attributes[5]);
    }

    /**
     * Test the forProperty() method.
     *
     * @throws ReflectionException
     */
    public function testForProperty(): void
    {
        $attributes = $this->attributes->forProperty(
            AttributedFixture::class,
            self::STATIC_PROPERTY_NAME,
            AttributeFixture::class
        );

        $this->testsForStaticProperty(...$attributes);

        $attributes = $this->attributes->forProperty(
            AttributedFixture::class,
            self::PROPERTY_NAME,
            AttributeFixture::class
        );

        $this->testsForProperty(...$attributes);
    }

    /**
     * Test the forProperties() method.
     *
     * @throws ReflectionException
     */
    public function testForProperties(): void
    {
        $attributes = $this->attributes->forProperties(AttributedFixture::class, AttributeFixture::class);

        self::assertCount(6, $attributes);
        $this->testsForStaticProperty($attributes[0], $attributes[1], $attributes[2]);
        $this->testsForProperty($attributes[3], $attributes[4], $attributes[5]);
    }

    /**
     * Test the forMethod() method.
     *
     * @throws ReflectionException
     */
    public function testForMethod(): void
    {
        $attributes = $this->attributes->forMethod(
            AttributedFixture::class,
            self::STATIC_METHOD_NAME,
            AttributeFixture::class
        );

        $this->testsForStaticMethod(...$attributes);

        $attributes = $this->attributes->forMethod(
            AttributedFixture::class,
            self::METHOD_NAME,
            AttributeFixture::class
        );

        $this->testsForMethod(...$attributes);

        $attributes = $this->attributes->forMethodParameters(
            AttributedFixture::class,
            self::METHOD_WITH_PARAMETERS_NAME,
            AttributeFixture::class
        );

        $this->testsForMethod(...$attributes);

        $attributes = $this->attributes->forMethodParameter(
            AttributedFixture::class,
            self::METHOD_WITH_PARAMETERS_NAME,
            'parameter',
            AttributeFixture::class
        );

        $this->testsForMethod(...$attributes);

        $attributesEmpty = $this->attributes->forMethodParameter(
            AttributedFixture::class,
            self::METHOD_WITH_PARAMETERS_NAME,
            'nonExistent',
            AttributeFixture::class
        );

        self::assertEmpty($attributesEmpty);
    }

    /**
     * Test the forMethods() method.
     *
     * @throws ReflectionException
     */
    public function testForMethods(): void
    {
        $attributes = $this->attributes->forMethods(AttributedFixture::class, AttributeFixture::class);

        self::assertCount(9, $attributes);
        $this->testsForStaticMethod($attributes[0], $attributes[1], $attributes[2]);
        $this->testsForMethod($attributes[3], $attributes[4], $attributes[5]);
        $this->testsForMethod($attributes[6], $attributes[7], $attributes[8]);
    }

    /**
     * Test the forFunction() method.
     *
     * @throws ReflectionException
     */
    public function testForFunction(): void
    {
        #[AttributeFixture(1)]
        #[AttributeFixture(2)]
        #[AttributeClassChildFixture(3, 'three')]
        function testFunction(
            #[AttributeFixture(1)]
            #[AttributeFixture(2)]
            #[AttributeClassChildFixture(3, 'three')]
            string $param
        ): void {
        }

        $attributes = $this->attributes->forFunction('\Valkyrja\Tests\Unit\Attribute\Collector\testFunction', AttributeFixture::class);

        $this->baseTests(...$attributes);
        $this->valueTests(
            self::VALUE1,
            self::VALUE2,
            self::VALUE3,
            self::THREE,
            ...$attributes
        );

        $attributes = $this->attributes->forFunctionParameters('\Valkyrja\Tests\Unit\Attribute\Collector\testFunction', AttributeFixture::class);

        $this->baseTests(...$attributes);
        $this->valueTests(
            self::VALUE1,
            self::VALUE2,
            self::VALUE3,
            self::THREE,
            ...$attributes
        );
    }

    /**
     * Test the forClosure() method.
     *
     * @throws ReflectionException
     */
    public function testForClosure(): void
    {
        $attributes = $this->attributes->forClosure(
            #[AttributeFixture(self::VALUE4)]
            #[AttributeFixture(self::VALUE5)]
            #[AttributeClassChildFixture(self::VALUE6, self::SIX)]
            static function (): void {
            },
            AttributeFixture::class
        );

        $this->baseTests(...$attributes);
        $this->valueTests(
            self::VALUE4,
            self::VALUE5,
            self::VALUE6,
            self::SIX,
            ...$attributes
        );

        $attributes = $this->attributes->forClosure(
            #[AttributeFixture(self::VALUE7)]
            #[AttributeFixture(self::VALUE8)]
            #[AttributeClassChildFixture(self::VALUE9, self::NINE)]
            static function (): void {
            },
            AttributeFixture::class
        );

        $this->baseTests(...$attributes);
        $this->valueTests(
            self::VALUE7,
            self::VALUE8,
            self::VALUE9,
            self::NINE,
            ...$attributes
        );

        $attributes = $this->attributes->forClosureParameters(
            #[AttributeFixture(self::VALUE4)]
            #[AttributeFixture(self::VALUE5)]
            #[AttributeClassChildFixture(self::VALUE6, self::SIX)]
            static function (
                #[AttributeFixture(CollectorTest::VALUE1)]
                #[AttributeFixture(CollectorTest::VALUE2)]
                #[AttributeClassChildFixture(CollectorTest::VALUE3, CollectorTest::THREE)]
                string $param
            ): void {
            },
            AttributeFixture::class
        );

        $this->baseTests(...$attributes);
        $this->valueTests(
            self::VALUE1,
            self::VALUE2,
            self::VALUE3,
            self::THREE,
            ...$attributes
        );
    }

    /**
     * Tests for the const member.
     *
     * @param AttributeFixture|AttributeClassChildFixture ...$attributes The attributes
     */
    protected function testsForConst(AttributeFixture|AttributeClassChildFixture ...$attributes): void
    {
        $this->baseTests(...$attributes);
        $this->valueTests(
            self::VALUE4,
            self::VALUE5,
            self::VALUE6,
            self::SIX,
            ...$attributes
        );
    }

    /**
     * Tests for the protectedConst member.
     *
     * @param AttributeFixture|AttributeClassChildFixture ...$attributes The attributes
     */
    protected function testsForProtectedConst(AttributeFixture|AttributeClassChildFixture ...$attributes): void
    {
        $this->baseTests(...$attributes);
        $this->valueTests(
            self::VALUE7,
            self::VALUE8,
            self::VALUE9,
            self::NINE,
            ...$attributes
        );
    }

    /**
     * Tests for the staticProperty member.
     *
     * @param AttributeFixture|AttributeClassChildFixture ...$attributes The attributes
     */
    protected function testsForStaticProperty(AttributeFixture|AttributeClassChildFixture ...$attributes): void
    {
        $this->baseTests(...$attributes);
        $this->valueTests(
            self::VALUE10,
            self::VALUE11,
            self::VALUE12,
            self::TWELVE,
            ...$attributes
        );
    }

    /**
     * Tests for the property member.
     *
     * @param AttributeFixture|AttributeClassChildFixture ...$attributes The attributes
     */
    protected function testsForProperty(AttributeFixture|AttributeClassChildFixture ...$attributes): void
    {
        $this->baseTests(...$attributes);
        $this->valueTests(
            self::VALUE13,
            self::VALUE14,
            self::VALUE15,
            self::FIFTEEN,
            ...$attributes
        );
    }

    /**
     * Tests for the staticMember() member.
     *
     * @param AttributeFixture|AttributeClassChildFixture ...$attributes The attributes
     */
    protected function testsForStaticMethod(AttributeFixture|AttributeClassChildFixture ...$attributes): void
    {
        $this->baseTests(...$attributes);
        $this->valueTests(
            self::VALUE16,
            self::VALUE17,
            self::VALUE18,
            self::EIGHTEEN,
            ...$attributes
        );
    }

    /**
     * Tests for the method() member.
     *
     * @param AttributeFixture|AttributeClassChildFixture ...$attributes The attributes
     */
    protected function testsForMethod(AttributeFixture|AttributeClassChildFixture ...$attributes): void
    {
        $this->baseTests(...$attributes);
        $this->valueTests(
            self::VALUE19,
            self::VALUE20,
            self::VALUE21,
            self::TWENTY_ONE,
            ...$attributes
        );
    }

    /**
     * Base tests for all items.
     *
     * @param AttributeFixture ...$attributes The attributes
     */
    protected function baseTests(AttributeFixture ...$attributes): void
    {
        self::assertCount(3, $attributes);
        self::assertInstanceOf(AttributeFixture::class, $attributes[0]);
        self::assertInstanceOf(AttributeFixture::class, $attributes[1]);
        self::assertInstanceOf(AttributeClassChildFixture::class, $attributes[2]);
        self::assertNotNull($attributes[0]->getReflection());
        self::assertNotNull($attributes[1]->getReflection());
        self::assertNotNull($attributes[2]->getReflection());
    }

    /**
     * Test values.
     *
     * @param int                        $value1     The value in the first attribute
     * @param int                        $value2     The value in the second attribute
     * @param int                        $value3     The first value in the third attribute
     * @param string                     $value4     The second value in the third attribute
     * @param AttributeFixture           $attribute1 The first attribute
     * @param AttributeFixture           $attribute2 The second attribute
     * @param AttributeClassChildFixture $attribute3 The third attribute
     */
    protected function valueTests(
        int $value1,
        int $value2,
        int $value3,
        string $value4,
        AttributeFixture $attribute1,
        AttributeFixture $attribute2,
        AttributeClassChildFixture $attribute3
    ): void {
        self::assertSame($value1, $attribute1->counter);
        self::assertSame($value2, $attribute2->counter);
        self::assertSame($value3, $attribute3->counter);
        self::assertSame($value4, $attribute3->test);
    }
}
