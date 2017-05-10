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
    public function testSetValue(): void
    {
        $this->assertEquals(true, method_exists(Enum::class, 'setValue'));
    }

    /**
     * Ensure the get value method exists in the Enum abstract class.
     *
     * @return void
     */
    public function testGetValue(): void
    {
        $this->assertEquals(true, method_exists(Enum::class, 'getValue'));
    }

    /**
     * Ensure the is valid method exists in the Enum abstract class.
     *
     * @return void
     */
    public function testIsValid(): void
    {
        $this->assertEquals(true, method_exists(Enum::class, 'isValid'));
    }

    /**
     * Ensure the valid values method exists in the Enum abstract class.
     *
     * @return void
     */
    public function testValidValues(): void
    {
        $this->assertEquals(true, method_exists(Enum::class, 'validValues'));
    }

    /**
     * Ensure the json serialize method exists in the Enum abstract class.
     *
     * @return void
     */
    public function testJsonSerialize(): void
    {
        $this->assertEquals(true, method_exists(Enum::class, 'jsonSerialize'));
    }

    /**
     * Ensure the to string method exists in the Enum abstract class.
     *
     * @return void
     */
    public function testToString(): void
    {
        $this->assertEquals(true, method_exists(Enum::class, '__toString'));
    }
}
