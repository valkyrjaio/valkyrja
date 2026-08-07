<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Event\Attribute;

use Valkyrja\Event\Attribute\ListenerHandler;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the listener handler attribute.
 */
final class ListenerHandlerTest extends TestCase
{
    public function testHandler(): void
    {
        $handler = static fn (): null => null;

        $listenerHandler = new ListenerHandler($handler);

        self::assertSame($handler, $listenerHandler->handler);
    }
}
