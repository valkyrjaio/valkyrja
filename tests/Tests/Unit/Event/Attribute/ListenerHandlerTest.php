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
use Valkyrja\Event\Attribute\ListenerHandler;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the listener handler attribute.
 */
final class ListenerHandlerTest extends TestCase
{
    public function testHandler(): void
    {
        $handler = static fn () => null;

        $listenerHandler = new ListenerHandler($handler);

        self::assertSame($handler, $listenerHandler->handler);
    }
}
