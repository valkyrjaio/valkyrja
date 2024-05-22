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

namespace Valkyrja\Tests\Unit\Event;

use Valkyrja\Event\Model\Listener;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the listener model.
 *
 * @author Melech Mizrachi
 */
class ListenerTest extends TestCase
{
    /**
     * The value to test with.
     *
     * @var string
     */
    protected const VALUE = self::class;

    /**
     * The class to test with.
     */
    protected Listener $class;

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
     * Test the getEventId and setEventId methods.
     *
     * @return void
     */
    public function testEventId(): void
    {
        $set = $this->class->setEventId(self::VALUE);

        self::assertSame(self::VALUE, $this->class->getEventId());
        // Assertion to ensure the interface doesn't change unexpectedly
        self::assertTrue($set instanceof Listener);
    }
}
