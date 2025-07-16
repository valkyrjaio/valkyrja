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

namespace Valkyrja\Tests\Unit\Event\Data;

use Valkyrja\Dispatcher\Data\MethodDispatch;
use Valkyrja\Event\Data\Listener;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the Listener service.
 *
 * @author Melech Mizrachi
 */
class ListenerTest extends TestCase
{
    public function testEventId(): void
    {
        $class    = self::class;
        $name     = 'test';
        $listener = new Listener(eventId: $class, name: $name);

        self::assertSame($class, $listener->getEventId());

        $class2    = Listener::class;
        $listener2 = $listener->withEventId($class2);

        self::assertNotSame($listener, $listener2);
        self::assertSame($class2, $listener2->getEventId());
    }

    public function testName(): void
    {
        $class    = self::class;
        $name     = 'test';
        $listener = new Listener(eventId: $class, name: $name);

        self::assertSame($name, $listener->getName());

        $name2     = 'test2';
        $listener2 = $listener->withName($name2);

        self::assertNotSame($listener, $listener2);
        self::assertSame($name2, $listener2->getName());
    }

    public function testDispatch(): void
    {
        $class    = self::class;
        $name     = 'test';
        $dispatch = new MethodDispatch(self::class, 'test');
        $listener = new Listener(eventId: $class, name: $name, dispatch: $dispatch);

        self::assertSame($name, $listener->getName());

        $dispatch2 = new MethodDispatch(self::class, 'test2');
        $listener2 = $listener->withDispatch($dispatch2);

        self::assertNotSame($listener, $listener2);
        self::assertSame($dispatch2, $listener2->getDispatch());
    }
}
