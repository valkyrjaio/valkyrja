<?php

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
use Valkyrja\Enum\Enum;

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
        assertEquals(true, method_exists(Enum::class, 'setValue'));
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

        assertEquals(EnumClass::FOO, $enum->getValue());
    }

    /**
     * Get an enum to test with.
     *
     * @return \Valkyrja\Enum\Enum
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
            assertEquals(InvalidArgumentException::class, get_class($exception));
        }
    }

    /**
     * Ensure the get value method exists in the Enum abstract class.
     *
     * @return void
     */
    public function testGetValueExists(): void
    {
        assertEquals(true, method_exists(Enum::class, 'getValue'));
    }

    /**
     * Ensure the get value method functions correctly.
     *
     * @return void
     */
    public function testGetValue(): void
    {
        assertEquals(EnumClass::BAR, $this->getEnum()->getValue());
    }

    /**
     * Ensure the is valid method exists in the Enum abstract class.
     *
     * @return void
     */
    public function testIsValidExists(): void
    {
        assertEquals(true, method_exists(Enum::class, 'isValid'));
    }

    /**
     * Test the is valid method with a valid value.
     *
     * @return void
     */
    public function testIsValid(): void
    {
        assertEquals(true, $this->getEnum()->isValid(EnumClass::FOO));
    }

    /**
     * Test the is valid method with an invalid enum value.
     *
     * @return void
     */
    public function testNotValid(): void
    {
        assertEquals(false, $this->getEnum()->isValid('invalid value'));
    }

    /**
     * Ensure the valid values method exists in the Enum abstract class.
     *
     * @return void
     */
    public function testValidValuesExists(): void
    {
        assertEquals(true, method_exists(Enum::class, 'validValues'));
    }

    /**
     * Test the valid values of an enum.
     *
     * @return void
     */
    public function testValidValues(): void
    {
        assertEquals(
            [
                EnumClass::FOO,
                EnumClass::BAR,
            ],
            $this->getEnum()->validValues()
        );
    }

    /**
     * Ensure the json serialize method exists in the Enum abstract class.
     *
     * @return void
     */
    public function testJsonSerializeExists(): void
    {
        assertEquals(true, method_exists(Enum::class, 'jsonSerialize'));
    }

    /**
     * Test json serialization of the enum.
     *
     * @return void
     */
    public function testJsonSerialize(): void
    {
        $jsonSerialized = json_encode($this->getEnum());
        $serialized     = '"foo"';

        assertEquals($serialized, $jsonSerialized);
    }

    /**
     * Ensure the to string method exists in the Enum abstract class.
     *
     * @return void
     */
    public function testToStringExists(): void
    {
        assertEquals(true, method_exists(Enum::class, '__toString'));
    }

    /**
     * Test the enum __toString capability.
     *
     * @return void
     */
    public function testToString(): void
    {
        assertEquals('foo', $this->getEnum());
    }
}
