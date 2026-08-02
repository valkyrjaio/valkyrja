<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Support\Time;

use Valkyrja\Support\Time\Time;
use Valkyrja\Tests\Unit\Abstract\TestCase;

use function sleep;

/**
 * Test the Time support class.
 */
final class TimeTest extends TestCase
{
    public function testFreezeWithCurrentTime(): void
    {
        $time = Time::get();

        Time::freeze($time);

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
