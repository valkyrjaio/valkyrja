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

namespace Valkyrja\Tests\Unit\Attribute;

use ReflectionException;
use Valkyrja\Attribute\Collector\Collector;
use Valkyrja\Dispatcher\Data\Contract\ConstantDispatch;
use Valkyrja\Dispatcher\Data\Contract\MethodDispatch;
use Valkyrja\Dispatcher\Data\Contract\PropertyDispatch;
use Valkyrja\Tests\Classes\Attribute\AttributeClass;
use Valkyrja\Tests\Classes\Attribute\AttributeClassChildClass;
use Valkyrja\Tests\Classes\Attribute\AttributedClass;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the Attributes service.
 *
 * @author Melech Mizrachi
 */
class AttributesTest extends TestCase
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
     * The class to test with.
     *
     * @var AttributedClass
     */
    protected AttributedClass $class;

    /**
     * Setup the test.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->attributes = new Collector();
        $this->class      = new AttributedClass();
    }

    /**
     * Test the forClass() method.
     *
     * @throws ReflectionException
     *
     * @return void
     */
    public function testForClass(): void
    {
        $attributes = $this->attributes->forClass(AttributedClass::class, AttributeClass::class);

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
     *
     * @return void
     */
    public function testForClassMembers(): void
    {
        $attributes = $this->attributes->forClassMembers(AttributedClass::class, AttributeClass::class);

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
     *
     * @return void
     */
    public function testForClassAndMembers(): void
    {
        $attributes = $this->attributes->forClassAndMembers(AttributedClass::class, AttributeClass::class);

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
     *
     * @return void
     */
    public function testForConstant(): void
    {
        $attributes = $this->attributes->forConstant(
            AttributedClass::class,
            self::CONST_NAME,
            AttributeClass::class
        );

        $this->testsForConst(...$attributes);

        $attributes = $this->attributes->forConstant(
            AttributedClass::class,
            self::PROTECTED_CONST_NAME,
            AttributeClass::class
        );

        $this->testsForProtectedConst(...$attributes);
    }

    /**
     * Test the forConstants() method.
     *
     * @throws ReflectionException
     *
     * @return void
     */
    public function testForConstants(): void
    {
        $attributes = $this->attributes->forConstants(AttributedClass::class, AttributeClass::class);

        self::assertCount(6, $attributes);
        $this->testsForConst($attributes[0], $attributes[1], $attributes[2]);
        $this->testsForProtectedConst($attributes[3], $attributes[4], $attributes[5]);
    }

    /**
     * Test the forProperty() method.
     *
     * @throws ReflectionException
     *
     * @return void
     */
    public function testForProperty(): void
    {
        $attributes = $this->attributes->forProperty(
            AttributedClass::class,
            self::STATIC_PROPERTY_NAME,
            AttributeClass::class
        );

        $this->testsForStaticProperty(...$attributes);

        $attributes = $this->attributes->forProperty(
            AttributedClass::class,
            self::PROPERTY_NAME,
            AttributeClass::class
        );

        $this->testsForProperty(...$attributes);
    }

    /**
     * Test the forProperties() method.
     *
     * @throws ReflectionException
     *
     * @return void
     */
    public function testForProperties(): void
    {
        $attributes = $this->attributes->forProperties(AttributedClass::class, AttributeClass::class);

        self::assertCount(6, $attributes);
        $this->testsForStaticProperty($attributes[0], $attributes[1], $attributes[2]);
        $this->testsForProperty($attributes[3], $attributes[4], $attributes[5]);
    }

    /**
     * Test the forMethod() method.
     *
     * @throws ReflectionException
     *
     * @return void
     */
    public function testForMethod(): void
    {
        $attributes = $this->attributes->forMethod(
            AttributedClass::class,
            self::STATIC_METHOD_NAME,
            AttributeClass::class
        );

        $this->testsForStaticMethod(...$attributes);

        $attributes = $this->attributes->forMethod(
            AttributedClass::class,
            self::METHOD_NAME,
            AttributeClass::class
        );

        $this->testsForMethod(...$attributes);

        $attributes = $this->attributes->forMethodParameters(
            AttributedClass::class,
            self::METHOD_WITH_PARAMETERS_NAME,
            AttributeClass::class
        );

        $this->testsForMethod(...$attributes);

        $attributes = $this->attributes->forMethodParameter(
            AttributedClass::class,
            self::METHOD_WITH_PARAMETERS_NAME,
            'parameter',
            AttributeClass::class
        );

        $this->testsForMethod(...$attributes);

        $attributesEmpty = $this->attributes->forMethodParameter(
            AttributedClass::class,
            self::METHOD_WITH_PARAMETERS_NAME,
            'nonExistent',
            AttributeClass::class
        );

        self::assertEmpty($attributesEmpty);
    }

    /**
     * Test the forMethods() method.
     *
     * @throws ReflectionException
     *
     * @return void
     */
    public function testForMethods(): void
    {
        $attributes = $this->attributes->forMethods(AttributedClass::class, AttributeClass::class);

        self::assertCount(9, $attributes);
        $this->testsForStaticMethod($attributes[0], $attributes[1], $attributes[2]);
        $this->testsForMethod($attributes[3], $attributes[4], $attributes[5]);
        $this->testsForMethod($attributes[6], $attributes[7], $attributes[8]);
    }

    /**
     * Test the forFunction() method.
     *
     * @throws ReflectionException
     *
     * @return void
     */
    public function testForFunction(): void
    {
        #[AttributeClass(1)]
        #[AttributeClass(2)]
        #[AttributeClassChildClass(3, 'three')]
        function testFunction(
            #[AttributeClass(1)]
            #[AttributeClass(2)]
            #[AttributeClassChildClass(3, 'three')]
            string $param
        ): void {
        }

        $attributes = $this->attributes->forFunction('\Valkyrja\Tests\Unit\Attribute\testFunction', AttributeClass::class);

        $this->baseTests(...$attributes);
        $this->valueTests(
            self::VALUE1,
            self::VALUE2,
            self::VALUE3,
            self::THREE,
            ...$attributes
        );

        $attributes = $this->attributes->forFunctionParameters('\Valkyrja\Tests\Unit\Attribute\testFunction', AttributeClass::class);

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
     *
     * @return void
     */
    public function testForClosure(): void
    {
        $attributes = $this->attributes->forClosure(
            #[AttributeClass(self::VALUE4)]
            #[AttributeClass(self::VALUE5)]
            #[AttributeClassChildClass(self::VALUE6, self::SIX)]
            static function (): void {
            },
            AttributeClass::class
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
            #[AttributeClass(self::VALUE7)]
            #[AttributeClass(self::VALUE8)]
            #[AttributeClassChildClass(self::VALUE9, self::NINE)]
            static function (): void {
            },
            AttributeClass::class
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
            #[AttributeClass(self::VALUE4)]
            #[AttributeClass(self::VALUE5)]
            #[AttributeClassChildClass(self::VALUE6, self::SIX)]
            static function (
                #[AttributeClass(AttributesTest::VALUE1)]
                #[AttributeClass(AttributesTest::VALUE2)]
                #[AttributeClassChildClass(AttributesTest::VALUE3, AttributesTest::THREE)]
                string $param
            ): void {
            },
            AttributeClass::class
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
     * @param AttributeClass|AttributeClassChildClass ...$attributes The attributes
     *
     * @return void
     */
    protected function testsForConst(AttributeClass|AttributeClassChildClass ...$attributes): void
    {
        $this->baseTests(...$attributes);
        $this->valueTests(
            self::VALUE4,
            self::VALUE5,
            self::VALUE6,
            self::SIX,
            ...$attributes
        );
        $this->setTests($attributes[2], false, self::CONST_NAME);
    }

    /**
     * Tests for the protectedConst member.
     *
     * @param AttributeClass|AttributeClassChildClass ...$attributes The attributes
     *
     * @return void
     */
    protected function testsForProtectedConst(AttributeClass|AttributeClassChildClass ...$attributes): void
    {
        $this->baseTests(...$attributes);
        $this->valueTests(
            self::VALUE7,
            self::VALUE8,
            self::VALUE9,
            self::NINE,
            ...$attributes
        );
        $this->setTests($attributes[2], false, self::PROTECTED_CONST_NAME);
    }

    /**
     * Tests for the staticProperty member.
     *
     * @param AttributeClass|AttributeClassChildClass ...$attributes The attributes
     *
     * @return void
     */
    protected function testsForStaticProperty(AttributeClass|AttributeClassChildClass ...$attributes): void
    {
        $this->baseTests(...$attributes);
        $this->valueTests(
            self::VALUE10,
            self::VALUE11,
            self::VALUE12,
            self::TWELVE,
            ...$attributes
        );
        $this->setTests($attributes[2], true, self::STATIC_PROPERTY_NAME);
    }

    /**
     * Tests for the property member.
     *
     * @param AttributeClass|AttributeClassChildClass ...$attributes The attributes
     *
     * @return void
     */
    protected function testsForProperty(AttributeClass|AttributeClassChildClass ...$attributes): void
    {
        $this->baseTests(...$attributes);
        $this->valueTests(
            self::VALUE13,
            self::VALUE14,
            self::VALUE15,
            self::FIFTEEN,
            ...$attributes
        );
        $this->setTests($attributes[2], false, self::PROPERTY_NAME);
    }

    /**
     * Tests for the staticMember() member.
     *
     * @param AttributeClass|AttributeClassChildClass ...$attributes The attributes
     *
     * @return void
     */
    protected function testsForStaticMethod(AttributeClass|AttributeClassChildClass ...$attributes): void
    {
        $this->baseTests(...$attributes);
        $this->valueTests(
            self::VALUE16,
            self::VALUE17,
            self::VALUE18,
            self::EIGHTEEN,
            ...$attributes
        );
        $this->setTests($attributes[2], true, self::STATIC_METHOD_NAME);
    }

    /**
     * Tests for the method() member.
     *
     * @param AttributeClass|AttributeClassChildClass ...$attributes The attributes
     *
     * @return void
     */
    protected function testsForMethod(AttributeClass|AttributeClassChildClass ...$attributes): void
    {
        $this->baseTests(...$attributes);
        $this->valueTests(
            self::VALUE19,
            self::VALUE20,
            self::VALUE21,
            self::TWENTY_ONE,
            ...$attributes
        );
        $this->setTests($attributes[2], false, self::METHOD_NAME);
    }

    /**
     * Base tests for all items.
     *
     * @param AttributeClass ...$attributes The attributes
     *
     * @return void
     */
    protected function baseTests(AttributeClass ...$attributes): void
    {
        self::assertCount(3, $attributes);
        self::assertInstanceOf(AttributeClass::class, $attributes[0]);
        self::assertInstanceOf(AttributeClass::class, $attributes[1]);
        self::assertInstanceOf(AttributeClassChildClass::class, $attributes[2]);
        self::assertNotNull($attributes[0]->getReflection());
        self::assertNotNull($attributes[1]->getReflection());
        self::assertNotNull($attributes[2]->getReflection());
    }

    /**
     * Test values.
     *
     * @param int                      $value1     The value in the first attribute
     * @param int                      $value2     The value in the second attribute
     * @param int                      $value3     The first value in the third attribute
     * @param string                   $value4     The second value in the third attribute
     * @param AttributeClass           $attribute1 The first attribute
     * @param AttributeClass           $attribute2 The second attribute
     * @param AttributeClassChildClass $attribute3 The third attribute
     *
     * @return void
     */
    protected function valueTests(
        int $value1,
        int $value2,
        int $value3,
        string $value4,
        AttributeClass $attribute1,
        AttributeClass $attribute2,
        AttributeClassChildClass $attribute3
    ): void {
        self::assertSame($value1, $attribute1->counter);
        self::assertSame($value2, $attribute2->counter);
        self::assertSame($value3, $attribute3->counter);
        self::assertSame($value4, $attribute3->test);
    }

    /**
     * Tests related to setters.
     *
     * @param AttributeClassChildClass $attribute The attribute
     * @param bool                     $isStatic  Whether the member is static
     * @param string                   $name      The name of the member
     *
     * @return void
     */
    protected function setTests(AttributeClassChildClass $attribute, bool $isStatic, string $name): void
    {
        match ($name) {
            self::CONST_NAME, self::PROTECTED_CONST_NAME => static function () use ($name, $attribute): void {
                $dispatch = $attribute->getDispatch();

                self::assertInstanceOf(ConstantDispatch::class, $dispatch);
                /** @var ConstantDispatch $dispatch */
                self::assertSame($name, $dispatch->getConstant());
                self::assertSame(AttributedClass::class, $dispatch->getClass());
            },
            self::STATIC_PROPERTY_NAME, self::PROPERTY_NAME => static function () use ($name, $attribute, $isStatic): void {
                $dispatch = $attribute->getDispatch();

                self::assertInstanceOf(PropertyDispatch::class, $dispatch);
                /** @var PropertyDispatch $dispatch */
                self::assertSame($isStatic, $dispatch->isStatic());
                self::assertSame($name, $dispatch->getProperty());
                self::assertSame(AttributedClass::class, $dispatch->getClass());
            },
            self::STATIC_METHOD_NAME, self::METHOD_NAME => static function () use ($name, $attribute, $isStatic): void {
                $dispatch = $attribute->getDispatch();

                self::assertInstanceOf(MethodDispatch::class, $dispatch);
                /** @var MethodDispatch $dispatch */
                self::assertSame($isStatic, $dispatch->isStatic());
                self::assertSame($name, $dispatch->getMethod());
                self::assertSame(AttributedClass::class, $dispatch->getClass());
            },
        };
    }
}
