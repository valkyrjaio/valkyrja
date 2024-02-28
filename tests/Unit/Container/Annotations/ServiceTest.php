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

namespace Valkyrja\Tests\Unit\Container\Annotations;

use Valkyrja\Container\Annotations\Service;
use Valkyrja\Tests\Unit\TestCase;

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
     * @var Service
     */
    protected Service $class;

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
    protected function setUp(): void
    {
        parent::setUp();

        $this->class = new Service();
    }

    /**
     * Test the isSingleton method.
     *
     * @return void
     */
    public function testIsSingleton(): void
    {
        $this->class->setSingleton(true);

        self::assertTrue($this->class->isSingleton());
    }

    /**
     * Test the setSingleton method.
     *
     * @return void
     */
    public function testSetSingleton(): void
    {
        self::assertSame($this->class, $this->class->setSingleton(true));
    }

    /**
     * Test the getDefaults method's default value.
     *
     * @return void
     */
    public function testGetDefaultsDefault(): void
    {
        self::assertNull($this->class->getDefaults());
    }

    /**
     * Test the getDefaults method.
     *
     * @return void
     */
    public function testGetDefaults(): void
    {
        $this->class->setDefaults([$this->value]);

        self::assertSame([$this->value], $this->class->getDefaults());
    }

    /**
     * Test the setDefaults method with null value.
     *
     * @return void
     */
    public function testSetDefaultsNull(): void
    {
        self::assertSame($this->class, $this->class->setDefaults(null));
    }

    /**
     * Test the setDefaults method.
     *
     * @return void
     */
    public function testSetDefaults(): void
    {
        self::assertSame($this->class, $this->class->setDefaults([$this->value]));
    }
}
