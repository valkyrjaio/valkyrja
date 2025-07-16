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

namespace Valkyrja\Tests\Unit\Session;

use Valkyrja\Session\Component;
use Valkyrja\Session\Provider\ServiceProvider;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the Component service.
 *
 * @author Melech Mizrachi
 */
class ComponentTest extends TestCase
{
    public function testGetContainerProvider(): void
    {
        self::assertContains(ServiceProvider::class, Component::getContainerProviders());
    }
}
