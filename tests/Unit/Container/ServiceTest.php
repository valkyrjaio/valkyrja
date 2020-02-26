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

namespace Valkyrja\Tests\Unit\Container;

use PHPUnit\Framework\TestCase;
use Valkyrja\Container\Models\Service;

/**
 * Test the service model.
 *
 * @author Melech Mizrachi
 */
class ServiceTest extends TestCase
{
    /**
     * The class to test with.
     *
     * @var \Valkyrja\Container\Service
     */
    protected $class;

    /**
     * The value to test with.
     *
     * @var string
     */
    protected string $value = 'test';

    /**
     * Setup the test.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->class = new Service();
    }

    /**
     * Test the isSingleton method's default value.
     *
     * @return void
     */
    public function testIsSingletonDefault(): void
    {
        $this->assertEquals(null, $this->class->isSingleton());
    }

    /**
     * Test the isSingleton method.
     *
     * @return void
     */
    public function testIsSingleton(): void
    {
        $this->class->setSingleton(true);

        $this->assertEquals(true, $this->class->isSingleton());
    }

    /**
     * Test the setSingleton method.
     *
     * @return void
     */
    public function testSetSingleton(): void
    {
        $set = $this->class->setSingleton(true);

        $this->assertEquals(true, $set instanceof Service);
    }

    /**
     * Test the getDefaults method's default value.
     *
     * @return void
     */
    public function testGetDefaultsDefault(): void
    {
        $this->assertEquals(null, $this->class->getDefaults());
    }

    /**
     * Test the getDefaults method.
     *
     * @return void
     */
    public function testGetDefaults(): void
    {
        $this->class->setDefaults([$this->value]);

        $this->assertEquals([$this->value], $this->class->getDefaults());
    }

    /**
     * Test the setDefaults method with null value.
     *
     * @return void
     */
    public function testSetDefaultsNull(): void
    {
        $set = $this->class->setDefaults(null);

        $this->assertEquals(true, $set instanceof Service);
    }

    /**
     * Test the setDefaults method.
     *
     * @return void
     */
    public function testSetDefaults(): void
    {
        $set = $this->class->setDefaults([$this->value]);

        $this->assertEquals(true, $set instanceof Service);
    }
}
