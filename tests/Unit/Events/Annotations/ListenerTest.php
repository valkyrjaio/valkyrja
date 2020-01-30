<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Tests\Unit\Events\Annotations;

use PHPUnit\Framework\TestCase;
use Valkyrja\Event\Annotations\Listener;

/**
 * Test the listener model.
 *
 * @author Melech Mizrachi
 */
class ListenerTest extends TestCase
{
    /**
     * The class to test with.
     *
     * @var \Valkyrja\Event\Annotations\Listener
     */
    protected $class;

    /**
     * The value to test with.
     *
     * @var string
     */
    protected $value = 'test';

    /**
     * Setup the test.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->class = new Listener();
    }

    /**
     * Test the getEvent method's default value.
     *
     * @return void
     */
    public function testGetEventDefault(): void
    {
        $this->assertEquals(null, $this->class->getEvent());
    }

    /**
     * Test the getEvent method.
     *
     * @return void
     */
    public function testGetEvent(): void
    {
        $this->class->setEvent($this->value);

        $this->assertEquals($this->value, $this->class->getEvent());
    }

    /**
     * Test the setEvent method with null value.
     *
     * @return void
     */
    public function testSetEventNull(): void
    {
        $this->assertEquals(null, $this->class->setEvent(null) ?? null);
    }

    /**
     * Test the setEvent method.
     *
     * @return void
     */
    public function testSetEvent(): void
    {
        $this->assertEquals(null, $this->class->setEvent($this->value) ?? null);
    }
}
