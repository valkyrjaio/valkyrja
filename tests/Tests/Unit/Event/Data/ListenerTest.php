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

use Valkyrja\Event\Data\Listener;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the Listener service.
 */
final class ListenerTest extends TestCase
{
    public function testEventId(): void
    {
        $class    = self::class;
        $name     = 'test';
        $listener = new Listener(eventId: $class, name: $name, handler: static fn () => null);

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
        $listener = new Listener(eventId: $class, name: $name, handler: static fn () => null);

        self::assertSame($name, $listener->getName());

        $name2     = 'test2';
        $listener2 = $listener->withName($name2);

        self::assertNotSame($listener, $listener2);
        self::assertSame($name2, $listener2->getName());
    }

    public function testHandler(): void
    {
        $class    = self::class;
        $name     = 'test';
        $handler  = static fn () => null;
        $listener = new Listener(eventId: $class, name: $name, handler: $handler);

        self::assertSame($name, $listener->getName());
        self::assertSame($handler, $listener->getHandler());

        $handler2  = static fn () => 'string';
        $listener2 = $listener->withHandler($handler2);

        self::assertNotSame($listener, $listener2);
        self::assertSame($handler2, $listener2->getHandler());
    }
}
