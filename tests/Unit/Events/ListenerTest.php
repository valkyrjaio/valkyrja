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

namespace Valkyrja\Tests\Unit\Events;

use PHPUnit\Framework\TestCase;
use Valkyrja\Event\Models\Listener;

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
     * @var \Valkyrja\Event\Listener
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

        $this->class = new Listener();
    }

    /**
     * Test the getEvent method's default value.
     *
     * @return void
     */
    public function testGetEventDefault(): void
    {
        self::assertEquals(null, $this->class->getEvent());
    }

    /**
     * Test the getEvent method.
     *
     * @return void
     */
    public function testGetEvent(): void
    {
        $this->class->setEvent($this->value);

        self::assertEquals($this->value, $this->class->getEvent());
    }

    /**
     * Test the setEvent method with null value.
     *
     * @return void
     */
    public function testSetEventNull(): void
    {
        $set = $this->class->setEvent(null);

        self::assertEquals(true, $set instanceof Listener);
    }

    /**
     * Test the setEvent method.
     *
     * @return void
     */
    public function testSetEvent(): void
    {
        $set = $this->class->setEvent($this->value);

        self::assertEquals(true, $set instanceof Listener);
    }
}
