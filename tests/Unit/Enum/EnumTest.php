<?php
declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Tests\Unit\Enum;

use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Valkyrja\Support\Enum\Enum;

use function array_diff;
use function get_class;
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
        $this->assertEquals(true, method_exists(Enum::class, 'setValue'));
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

        $this->assertEquals(EnumClass::FOO, $enum->getValue());
    }

    /**
     * Get an enum to test with.
     *
     * @return \Valkyrja\Support\Enum\Enum
     */
    protected function getEnum(): Enum
    {
        return new EnumClass(EnumClass::BAR);
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
            $this->assertEquals(InvalidArgumentException::class, get_class($exception));
        }
    }

    /**
     * Ensure the get value method exists in the Enum abstract class.
     *
     * @return void
     */
    public function testGetValueExists(): void
    {
        $this->assertEquals(true, method_exists(Enum::class, 'getValue'));
    }

    /**
     * Ensure the get value method functions correctly.
     *
     * @return void
     */
    public function testGetValue(): void
    {
        $this->assertEquals(EnumClass::BAR, $this->getEnum()->getValue());
    }

    /**
     * Ensure the is valid method exists in the Enum abstract class.
     *
     * @return void
     */
    public function testIsValidExists(): void
    {
        $this->assertEquals(true, method_exists(Enum::class, 'isValid'));
    }

    /**
     * Test the is valid method with a valid value.
     *
     * @return void
     */
    public function testIsValid(): void
    {
        $this->assertEquals(true, $this->getEnum()->isValid(EnumClass::FOO));
    }

    /**
     * Test the is valid method with an invalid enum value.
     *
     * @return void
     */
    public function testNotValid(): void
    {
        $this->assertEquals(false, $this->getEnum()->isValid('invalid value'));
    }

    /**
     * Ensure the valid values method exists in the Enum abstract class.
     *
     * @return void
     */
    public function testValidValuesExists(): void
    {
        $this->assertEquals(true, method_exists(Enum::class, 'getValidValues'));
    }

    /**
     * Test the valid values of an enum.
     *
     * @return void
     */
    public function testValidValues(): void
    {
        $this->assertEquals(
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
        $this->assertEquals(
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
     * Get an enum with no default values set to test with.
     *
     * @return \Valkyrja\Support\Enum\Enum
     */
    protected function getEnumEmpty(): Enum
    {
        return new EnumClassEmpty(EnumClassEmpty::FOO);
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
            $this->assertEquals(true, $exception instanceof InvalidArgumentException);
        }
    }

    /**
     * Ensure the to string method exists in the Enum abstract class.
     *
     * @return void
     */
    public function testToStringExists(): void
    {
        $this->assertEquals(true, method_exists(Enum::class, '__toString'));
    }

    /**
     * Test the enum __toString capability.
     *
     * @return void
     */
    public function testToString(): void
    {
        $this->assertEquals('foo', (string) $this->getEnum());
    }
}
