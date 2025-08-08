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

namespace Valkyrja\Tests\Unit\Cli\Interaction\Message;

use Valkyrja\Cli\Interaction\Message\Progress;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the Progress class.
 *
 * @author Melech Mizrachi
 */
class ProgressTest extends TestCase
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
