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

namespace Valkyrja\Tests\Unit\Enum;

use Exception;
use InvalidArgumentException;
use Valkyrja\Tests\Unit\TestCase;
use Valkyrja\Type\Types\Enum;

use function array_diff;
use function method_exists;

/**
 * Test the Enum abstract class.
 *
 * @author Melech Mizrachi
 */
class EnumTest extends TestCase
{
    /**
     * Ensure the set value method exists in the Enum abstract class.
     *
     * @return void
     */
    public function testSetValueExists(): void
    {
        self::assertTrue(method_exists(Enum::class, 'setValue'));
    }

    /**
     * Test the set value method.
     *
     * @return void
     */
    public function testSetValue(): void
    {
        $enum = $this->getEnum();
        $enum->setValue(EnumClass::FOO);

        self::assertSame(EnumClass::FOO, $enum->getValue());
    }

    /**
     * Test the set value method with an invalid value.
     *
     * @return void
     */
    public function testSetValueInvalid(): void
    {
        try {
            new EnumClass('invalid value');
        } catch (Exception $exception) {
            self::assertSame(InvalidArgumentException::class, $exception::class);
        }
    }

    /**
     * Ensure the get value method exists in the Enum abstract class.
     *
     * @return void
     */
    public function testGetValueExists(): void
    {
        self::assertTrue(method_exists(Enum::class, 'getValue'));
    }

    /**
     * Ensure the get value method functions correctly.
     *
     * @return void
     */
    public function testGetValue(): void
    {
        self::assertSame(EnumClass::BAR, $this->getEnum()->getValue());
    }

    /**
     * Ensure the is valid method exists in the Enum abstract class.
     *
     * @return void
     */
    public function testIsValidExists(): void
    {
        self::assertTrue(method_exists(Enum::class, 'isValid'));
    }

    /**
     * Test the is valid method with a valid value.
     *
     * @return void
     */
    public function testIsValid(): void
    {
        self::assertTrue($this->getEnum()->isValid(EnumClass::FOO));
    }

    /**
     * Test the is valid method with an invalid enum value.
     *
     * @return void
     */
    public function testNotValid(): void
    {
        self::assertFalse($this->getEnum()->isValid('invalid value'));
    }

    /**
     * Ensure the valid values method exists in the Enum abstract class.
     *
     * @return void
     */
    public function testValidValuesExists(): void
    {
        self::assertTrue(method_exists(Enum::class, 'getValidValues'));
    }

    /**
     * Test the valid values of an enum.
     *
     * @return void
     */
    public function testValidValues(): void
    {
        self::assertSame(
            [
                EnumClass::FOO => EnumClass::FOO,
                EnumClass::BAR => EnumClass::BAR,
            ],
            $this->getEnum()->getValidValues()
        );
    }

    /**
     * Test the valid values through use of the reflection class.
     *
     * @return void
     */
    public function testValidValuesReflection(): void
    {
        self::assertSame(
            [],
            array_diff(
                [
                    EnumClass::FOO => EnumClass::FOO,
                    EnumClass::BAR => EnumClass::BAR,
                ],
                $this->getEnumEmpty()->getValidValues()
            )
        );
    }

    /**
     * Test setting an invalid enum value after creating an enum with a valid value.
     *
     * @return void
     */
    public function testSetInvalidEnumValue(): void
    {
        $enum = new EnumClass(EnumClass::FOO);

        try {
            $enum->setValue('invalid');
        } catch (Exception $exception) {
            self::assertTrue($exception instanceof InvalidArgumentException);
        }
    }

    /**
     * Ensure the to string method exists in the Enum abstract class.
     *
     * @return void
     */
    public function testToStringExists(): void
    {
        self::assertTrue(method_exists(Enum::class, '__toString'));
    }

    /**
     * Test the enum __toString capability.
     *
     * @return void
     */
    public function testToString(): void
    {
        self::assertSame('foo', (string) $this->getEnum());
    }

    /**
     * Get an enum to test with.
     *
     * @return Enum
     */
    protected function getEnum(): Enum
    {
        return new EnumClass(EnumClass::BAR);
    }

    /**
     * Get an enum with no default values set to test with.
     *
     * @return Enum
     */
    protected function getEnumEmpty(): Enum
    {
        return new EnumClassEmpty(EnumClassEmpty::FOO);
    }
}
