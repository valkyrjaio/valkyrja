<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
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
