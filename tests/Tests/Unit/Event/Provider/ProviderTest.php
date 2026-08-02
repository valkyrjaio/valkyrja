<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Event\Provider;

use Valkyrja\Tests\Fixtures\Event\Provider\ProviderFixture;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class ProviderTest extends TestCase
{
    public function testGetListenerClasses(): void
    {
        self::assertEmpty(new ProviderFixture()->getListenerClasses());
    }

    public function testGetListeners(): void
    {
        self::assertEmpty(new ProviderFixture()->getListeners());
    }
}
