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

namespace Valkyrja\Tests\Unit\Events\Annotations;

use Valkyrja\Event\Annotations\Listener;
use Valkyrja\Tests\Unit\TestCase;

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
     * @var Listener
     */
    protected Listener $class;

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

        $this->class = new Listener();
    }

    /**
     * Test the getEvent method.
     *
     * @return void
     */
    public function testGetEvent(): void
    {
        $this->class->setEvent($this->value);

        self::assertSame($this->value, $this->class->getEvent());
    }

    /**
     * Test the setEvent method.
     *
     * @return void
     */
    public function testSetEvent(): void
    {
        self::assertSame($this->class, $this->class->setEvent($this->value));
    }
}
