<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Http\Routing\Data;

use Valkyrja\Http\Routing\Data\HttpRoutingData;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the Data service.
 */
final class DataTest extends TestCase
{
    public function testDefault(): void
    {
        $data = new HttpRoutingData();

        self::assertEmpty($data->routes);
        self::assertEmpty($data->paths);
        self::assertEmpty($data->regexes);
    }
}
