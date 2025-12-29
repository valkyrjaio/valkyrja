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

namespace Valkyrja\Tests\Unit\View\Provider;

use Valkyrja\Tests\Unit\TestCase;
use Valkyrja\View\Provider\ComponentProvider;
use Valkyrja\View\Provider\ServiceProvider;

/**
 * Test the Component service.
 *
 * @author Melech Mizrachi
 */
class ComponentProviderTest extends TestCase
{
    public function testGetContainerProvider(): void
    {
        self::assertContains(ServiceProvider::class, ComponentProvider::getContainerProviders());
    }
}
