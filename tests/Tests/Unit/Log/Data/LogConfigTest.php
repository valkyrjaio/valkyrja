<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Log\Data;

use Valkyrja\Log\Data\Contract\LogConfigContract;
use Valkyrja\Log\Data\LogConfig;
use Valkyrja\Log\Logger\NullLogger;
use Valkyrja\Log\Logger\PsrLogger;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class LogConfigTest extends TestCase
{
    public function testImplementsContract(): void
    {
        self::assertInstanceOf(LogConfigContract::class, new LogConfig());
    }

    public function testDefaults(): void
    {
        self::assertSame(PsrLogger::class, new LogConfig()->defaultLogger);
    }

    public function testCustomValuesAreStored(): void
    {
        self::assertSame(NullLogger::class, new LogConfig(defaultLogger: NullLogger::class)->defaultLogger);
    }
}
