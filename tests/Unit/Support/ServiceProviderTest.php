<?php

namespace Valkyrja\Tests\Unit\Support;

use PHPUnit\Framework\TestCase;
use Valkyrja\Contracts\Application;

/**
 * Test the ServiceProvider support class.
 *
 * @author Melech Mizrachi
 */
class ServiceProviderTest extends TestCase
{
    /**
     * The class to test with.
     *
     * @var \Valkyrja\Tests\Unit\Support\ServiceProviderClass
     */
    protected $class;

    /**
     * Test the service provider.
     *
     * @return void
     */
    public function testServiceProvider(): void
    {
        /** @var Application $app */
        $app = $this->createMock(Application::class);

        $this->class = new ServiceProviderClass($app);

        $this->assertEquals(null, $this->class->publish() ?? null);
    }
}
