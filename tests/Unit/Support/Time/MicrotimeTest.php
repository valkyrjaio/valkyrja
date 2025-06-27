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

namespace Valkyrja\Tests\Unit\Support\Time;

use Valkyrja\Support\Microtime;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the Microtime support class.
 *
 * @author Melech Mizrachi
 */
class MicrotimeTest extends TestCase
{
    public function testFreezeWithCurrentTime(): void
    {
        Microtime::freeze();
        $time = Microtime::get();

        usleep(100);

        self::assertSame($time, Microtime::get());

        Microtime::unfreeze();
    }

    public function testFreezeWithCustomTime(): void
    {
        Microtime::freeze(5);
        $time = Microtime::get();

        usleep(100);

        self::assertSame($time, Microtime::get());

        Microtime::unfreeze();
    }
}
