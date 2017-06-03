<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Tests\Unit\Support;

use PHPUnit\Framework\TestCase;
use Valkyrja\Application;

/**
 * Test the ServiceProvider support class.
 *
 * @author Melech Mizrachi
 */
class ServiceProviderTest extends TestCase
{
    /**
     * Test the service provider.
     *
     * @return void
     */
    public function testServiceProvider(): void
    {
        /** @var Application $app */
        $app = $this->createMock(Application::class);

        $this->assertEquals(null, ProviderClass::publish($app) ?? null);
    }
}
