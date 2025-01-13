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
use Valkyrja\Attribute\Attributes;
use Valkyrja\Tests\Classes\Attribute\Attribute;
use Valkyrja\Tests\Classes\Attribute\AttributeChild;
use Valkyrja\Tests\Classes\Attribute\AttributedClass;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the Attributes service.
 *
 * @author Melech Mizrachi
 */
class AttributesTest extends TestCase
{
    public const VALUE1  = 1;
    public const VALUE2  = 2;
    public const VALUE3  = 3;
    public const VALUE4  = 4;
    public const VALUE5  = 5;
    public const VALUE6  = 6;
    public const VALUE7  = 7;
    public const VALUE8  = 8;
    public const VALUE9  = 9;
    public const VALUE10 = 10;
    public const VALUE11 = 11;
    public const VALUE12 = 12;
    public const VALUE13 = 13;
    public const VALUE14 = 14;
    public const VALUE15 = 15;
    public const VALUE16 = 16;
    public const VALUE17 = 17;
    public const VALUE18 = 18;
    public const VALUE19 = 19;
    public const VALUE20 = 20;
    public const VALUE21 = 21;

    public const THREE      = 'three';
    public const SIX        = 'six';
    public const NINE       = 'nine';
    public const TWELVE     = 'twelve';
    public const FIFTEEN    = 'fifteen';
    public const EIGHTEEN   = 'eighteen';
    public const TWENTY_ONE = 'twenty one';

    protected const CONST_NAME                  = 'CONST';
    protected const PROTECTED_CONST_NAME        = 'PROTECTED_CONST';
    protected const STATIC_PROPERTY_NAME        = 'staticProperty';
    protected const PROPERTY_NAME               = 'property';
    protected const STATIC_METHOD_NAME          = 'staticMethod';
    protected const METHOD_NAME                 = 'method';
    protected const METHOD_WITH_PARAMETERS_NAME = 'methodWithParameter';

    /**
     * The attributes service.
     *
     * @var Attributes
     */
    protected Attributes $attributes;

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

        $this->attributes = new Attributes();
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
        $attributes = $this->attributes->forClass(AttributedClass::class, Attribute::class);

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
        $attributes = $this->attributes->forClassMembers(AttributedClass::class, Attribute::class);

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
        $attributes = $this->attributes->forClassAndMembers(AttributedClass::class, Attribute::class);

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
            Attribute::class
        );

        $this->testsForConst(...$attributes);

        $attributes = $this->attributes->forConstant(
            AttributedClass::class,
            self::PROTECTED_CONST_NAME,
            Attribute::class
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
        $attributes = $this->attributes->forConstants(AttributedClass::class, Attribute::class);

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
            Attribute::class
        );

        $this->testsForStaticProperty(...$attributes);

        $attributes = $this->attributes->forProperty(
            AttributedClass::class,
            self::PROPERTY_NAME,
            Attribute::class
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
        $attributes = $this->attributes->forProperties(AttributedClass::class, Attribute::class);

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
            Attribute::class
        );

        $this->testsForStaticMethod(...$attributes);

        $attributes = $this->attributes->forMethod(
            AttributedClass::class,
            self::METHOD_NAME,
            Attribute::class
        );

        $this->testsForMethod(...$attributes);

        $attributes = $this->attributes->forMethodParameters(
            AttributedClass::class,
            self::METHOD_WITH_PARAMETERS_NAME,
            Attribute::class
        );

        $this->testsForMethod(...$attributes);
        self::assertTrue($attributes[2]->isOptional);
        self::assertSame('fire', $attributes[2]->default);
        self::assertSame('parameter', $attributes[2]->name);

        $attributes = $this->attributes->forMethodParameter(
            AttributedClass::class,
            self::METHOD_WITH_PARAMETERS_NAME,
            'parameter',
            Attribute::class
        );

        $this->testsForMethod(...$attributes);
        self::assertTrue($attributes[2]->isOptional);
        self::assertSame('fire', $attributes[2]->default);
        self::assertSame('parameter', $attributes[2]->name);

        $attributesEmpty = $this->attributes->forMethodParameter(
            AttributedClass::class,
            self::METHOD_WITH_PARAMETERS_NAME,
            'nonExistent',
            Attribute::class
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
        $attributes = $this->attributes->forMethods(AttributedClass::class, Attribute::class);

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
        #[Attribute(AttributesTest::VALUE1)]
        #[Attribute(AttributesTest::VALUE2)]
        #[AttributeChild(AttributesTest::VALUE3, AttributesTest::THREE)]
        function testFunction(
            #[Attribute(AttributesTest::VALUE1)]
            #[Attribute(AttributesTest::VALUE2)]
            #[AttributeChild(AttributesTest::VALUE3, AttributesTest::THREE)]
            string $param
        ): void {
        }

        $attributes = $this->attributes->forFunction('\Valkyrja\Tests\Unit\Attribute\testFunction', Attribute::class);

        $this->baseTests(...$attributes);
        $this->valueTests(
            self::VALUE1,
            self::VALUE2,
            self::VALUE3,
            self::THREE,
            ...$attributes
        );

        $attributes = $this->attributes->forFunctionParameters('\Valkyrja\Tests\Unit\Attribute\testFunction', Attribute::class);

        $this->baseTests(...$attributes);
        $this->valueTests(
            self::VALUE1,
            self::VALUE2,
            self::VALUE3,
            self::THREE,
            ...$attributes
        );
        self::assertFalse($attributes[2]->isOptional);
        self::assertNull($attributes[2]->default);
        self::assertSame('param', $attributes[2]->name);
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
            #[Attribute(self::VALUE4)]
            #[Attribute(self::VALUE5)]
            #[AttributeChild(self::VALUE6, self::SIX)]
            static function (): void {
            },
            Attribute::class
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
            #[Attribute(self::VALUE7)]
            #[Attribute(self::VALUE8)]
            #[AttributeChild(self::VALUE9, self::NINE)]
            static function (): void {
            },
            Attribute::class
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
            #[Attribute(self::VALUE4)]
            #[Attribute(self::VALUE5)]
            #[AttributeChild(self::VALUE6, self::SIX)]
            static function (
                #[Attribute(AttributesTest::VALUE1)]
                #[Attribute(AttributesTest::VALUE2)]
                #[AttributeChild(AttributesTest::VALUE3, AttributesTest::THREE)]
                string $param
            ): void {
            },
            Attribute::class
        );

        $this->baseTests(...$attributes);
        $this->valueTests(
            self::VALUE1,
            self::VALUE2,
            self::VALUE3,
            self::THREE,
            ...$attributes
        );
        self::assertFalse($attributes[2]->isOptional);
        self::assertNull($attributes[2]->default);
        self::assertSame('param', $attributes[2]->name);
    }

    /**
     * Tests for the const member.
     *
     * @param Attribute|AttributeChild ...$attributes The attributes
     *
     * @return void
     */
    protected function testsForConst(Attribute|AttributeChild ...$attributes): void
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
     * @param Attribute|AttributeChild ...$attributes The attributes
     *
     * @return void
     */
    protected function testsForProtectedConst(Attribute|AttributeChild ...$attributes): void
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
     * @param Attribute|AttributeChild ...$attributes The attributes
     *
     * @return void
     */
    protected function testsForStaticProperty(Attribute|AttributeChild ...$attributes): void
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
     * @param Attribute|AttributeChild ...$attributes The attributes
     *
     * @return void
     */
    protected function testsForProperty(Attribute|AttributeChild ...$attributes): void
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
     * @param Attribute|AttributeChild ...$attributes The attributes
     *
     * @return void
     */
    protected function testsForStaticMethod(Attribute|AttributeChild ...$attributes): void
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
     * @param Attribute|AttributeChild ...$attributes The attributes
     *
     * @return void
     */
    protected function testsForMethod(Attribute|AttributeChild ...$attributes): void
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
     * @param Attribute ...$attributes The attributes
     *
     * @return void
     */
    protected function baseTests(Attribute ...$attributes): void
    {
        self::assertCount(3, $attributes);
        self::assertInstanceOf(Attribute::class, $attributes[0]);
        self::assertInstanceOf(Attribute::class, $attributes[1]);
        self::assertInstanceOf(AttributeChild::class, $attributes[2]);
        self::assertNotNull($attributes[0]->getReflection());
        self::assertNotNull($attributes[1]->getReflection());
        self::assertNotNull($attributes[2]->getReflection());
    }

    /**
     * Test values.
     *
     * @param int            $value1     The value in the first attribute
     * @param int            $value2     The value in the second attribute
     * @param int            $value3     The first value in the third attribute
     * @param string         $value4     The second value in the third attribute
     * @param Attribute      $attribute1 The first attribute
     * @param Attribute      $attribute2 The second attribute
     * @param AttributeChild $attribute3 The third attribute
     *
     * @return void
     */
    protected function valueTests(
        int $value1,
        int $value2,
        int $value3,
        string $value4,
        Attribute $attribute1,
        Attribute $attribute2,
        AttributeChild $attribute3
    ): void {
        self::assertSame($value1, $attribute1->counter);
        self::assertSame($value2, $attribute2->counter);
        self::assertSame($value3, $attribute3->counter);
        self::assertSame($value4, $attribute3->test);
    }

    /**
     * Tests related to setters.
     *
     * @param AttributeChild $attribute The attribute
     * @param bool           $isStatic  Whether the member is static
     * @param string         $name      The name of the member
     *
     * @return void
     */
    protected function setTests(AttributeChild $attribute, bool $isStatic, string $name): void
    {
        self::assertSame(AttributedClass::class, $attribute->class);

        match ($name) {
            self::CONST_NAME, self::PROTECTED_CONST_NAME => static function () use ($name, $attribute): void {
                self::assertSame($name, $attribute->constant);
                self::assertNull($attribute->property);
                self::assertNull($attribute->method);
            },
            self::STATIC_PROPERTY_NAME, self::PROPERTY_NAME => static function () use ($name, $attribute, $isStatic): void {
                self::assertSame($isStatic, $attribute->static);
                self::assertSame($name, $attribute->property);
                self::assertNull($attribute->constant);
                self::assertNull($attribute->method);
            },
            self::STATIC_METHOD_NAME, self::METHOD_NAME => static function () use ($name, $attribute, $isStatic): void {
                self::assertSame($isStatic, $attribute->static);
                self::assertSame($name, $attribute->method);
                self::assertNull($attribute->constant);
                self::assertNull($attribute->property);
            },
        };
    }
}
