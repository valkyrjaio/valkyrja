<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Http\Message\Uri\Data;

use Valkyrja\Http\Message\Uri\Data\HostPortAccumulator;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class HostPortAccumulatorTest extends TestCase
{
    public function testDefaults(): void
    {
        $accumulator = new HostPortAccumulator();

        self::assertSame('', $accumulator->host);
        self::assertSame(0, $accumulator->port);
    }

    public function testCustomValuesAreStored(): void
    {
        $accumulator = new HostPortAccumulator('example.com', 8080);

        self::assertSame('example.com', $accumulator->host);
        self::assertSame(8080, $accumulator->port);
    }

    public function testPropertiesAreMutable(): void
    {
        $accumulator = new HostPortAccumulator();

        $accumulator->host = 'localhost';
        $accumulator->port = 443;

        self::assertSame('localhost', $accumulator->host);
        self::assertSame(443, $accumulator->port);
    }
}
