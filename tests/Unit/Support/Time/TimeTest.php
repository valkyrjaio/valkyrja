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

use Valkyrja\Support\Time;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the Time support class.
 *
 * @author Melech Mizrachi
 */
class TimeTest extends TestCase
{
    public function testFreezeWithCurrentTime(): void
    {
        Time::freeze();
        $time = Time::get();

        sleep(1);

        self::assertSame($time, Time::get());

        Time::unfreeze();
    }

    public function testFreezeWithCustomTime(): void
    {
        Time::freeze(5);
        $time = Time::get();

        sleep(1);

        self::assertSame($time, Time::get());

        Time::unfreeze();
    }
}
