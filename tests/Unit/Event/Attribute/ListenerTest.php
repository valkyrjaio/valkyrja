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

namespace Valkyrja\Tests\Unit\Event\Attribute;

use Valkyrja\Event\Attribute\Listener;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the listener attribute.
 *
 * @author Melech Mizrachi
 */
class ListenerTest extends TestCase
{
    /**
     * The value to test with.
     *
     * @var class-string
     */
    protected const string VALUE = Listener::class;

    /**
     * The name to test with.
     *
     * @var non-empty-string
     */
    protected const string NAME = 'uniqueName';

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

        $this->class = new Listener(self::VALUE, self::NAME);
    }

    /**
     * Test the getEventId and setEventId methods.
     *
     * @return void
     */
    public function testEventId(): void
    {
        self::assertSame(self::VALUE, $this->class->getEventId());

        $newValue = TestCase::class;
        $new      = $this->class->withEventId($newValue);

        self::assertSame(self::VALUE, $this->class->getEventId());
        self::assertSame($newValue, $new->getEventId());
        self::assertNotSame($new, $this->class);
    }
}
