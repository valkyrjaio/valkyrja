<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Event\Data;

use Valkyrja\Event\Data\EventData;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the Data service.
 */
final class DataTest extends TestCase
{
    public function testDefault(): void
    {
        $data = new EventData();

        self::assertEmpty($data->events);
        self::assertEmpty($data->listeners);
    }
}
