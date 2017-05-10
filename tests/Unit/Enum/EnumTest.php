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
        $this->assertEquals(true, method_exists(Enum::class, 'setValue'));
    }

    /**
     * Test the set value method
     *
     * @return void
     */
    public function testSetValue(): void
    {
        $enum = new EnumClass(EnumClass::BAR);
        $enum->setValue(EnumClass::FOO);

        $this->assertEquals(EnumClass::FOO, $enum->getValue());
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
     * Ensure the is valid method exists in the Enum abstract class.
     *
     * @return void
     */
    public function testIsValidExists(): void
    {
        $this->assertEquals(true, method_exists(Enum::class, 'isValid'));
    }

    /**
     * Ensure the valid values method exists in the Enum abstract class.
     *
     * @return void
     */
    public function testValidValuesExists(): void
    {
        $this->assertEquals(true, method_exists(Enum::class, 'validValues'));
    }

    /**
     * Ensure the json serialize method exists in the Enum abstract class.
     *
     * @return void
     */
    public function testJsonSerializeExists(): void
    {
        $this->assertEquals(true, method_exists(Enum::class, 'jsonSerialize'));
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
}
