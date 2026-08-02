<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Broadcast\Data;

use Valkyrja\Broadcast\Data\BroadcastLogConfig;
use Valkyrja\Broadcast\Data\Contract\BroadcastLogConfigContract;
use Valkyrja\Log\Logger\Contract\LoggerContract;
use Valkyrja\Log\Logger\PsrLogger;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class BroadcastLogConfigTest extends TestCase
{
    public function testImplementsContract(): void
    {
        self::assertInstanceOf(BroadcastLogConfigContract::class, new BroadcastLogConfig());
    }

    public function testDefaults(): void
    {
        self::assertSame(LoggerContract::class, new BroadcastLogConfig()->logLogger);
    }

    public function testCustomValuesAreStored(): void
    {
        self::assertSame(PsrLogger::class, new BroadcastLogConfig(logLogger: PsrLogger::class)->logLogger);
    }
}
