<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Cli\Interaction\Message;

use Valkyrja\Cli\Interaction\Message\Progress;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the Progress class.
 */
final class ProgressTest extends TestCase
{
    public function testIsComplete(): void
    {
        $message = new Progress(text: 'text');

        self::assertFalse($message->isComplete());

        $message2 = $message->withIsComplete(true);

        self::assertNotSame($message, $message2);
        self::assertTrue($message2->isComplete());
    }

    public function testPercentage(): void
    {
        $message = new Progress(text: 'text');

        self::assertSame(0, $message->getPercentage());

        $message2 = $message->withPercentage(50);

        self::assertNotSame($message, $message2);
        self::assertSame(50, $message2->getPercentage());
    }
}
