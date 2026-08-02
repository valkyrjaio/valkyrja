<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Cli\Routing\Provider;

use Valkyrja\Tests\Fixtures\Cli\Routing\Provider\ProviderFixture;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class ProviderTest extends TestCase
{
    public function testGetRoutes(): void
    {
        self::assertEmpty(new ProviderFixture()->getRoutes());
    }

    public function testGetControllerClasses(): void
    {
        self::assertEmpty(new ProviderFixture()->getControllerClasses());
    }
}
