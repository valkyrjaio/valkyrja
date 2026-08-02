<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Container\Data;

use Valkyrja\Container\Data\ContainerData;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the Data service.
 */
final class DataTest extends TestCase
{
    public function testDefault(): void
    {
        $data = new ContainerData();

        self::assertEmpty($data->aliases);
        self::assertEmpty($data->callbacks);
        self::assertEmpty($data->services);
        self::assertEmpty($data->singletons);
    }
}
