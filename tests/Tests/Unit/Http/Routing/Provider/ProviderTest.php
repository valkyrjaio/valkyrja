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

namespace Valkyrja\Tests\Unit\Http\Routing\Provider;

use Valkyrja\Tests\Classes\Http\Routing\Provider\ProviderClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class ProviderTest extends TestCase
{
    public function testGetRoutes(): void
    {
        self::assertEmpty(ProviderClass::getRoutes());
    }

    public function testGetControllerClasses(): void
    {
        self::assertEmpty(ProviderClass::getControllerClasses());
    }
}
