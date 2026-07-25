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

namespace Valkyrja\Tests\Unit\Application\Entry\OpenSwoole;

use OpenSwoole\Http\Request;
use OpenSwoole\Http\Response;
use OpenSwoole\Http\Server;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Valkyrja\Application\Data\HttpConfig;
use Valkyrja\Application\Entry\OpenSwoole\OpenSwooleHttp;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Container\Data\ContainerData;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Tests\Fixtures\Application\Entry\OpenSwoole\OpenSwooleHttpFixture;
use Valkyrja\Tests\Unit\Abstract\TestCase;

use function extension_loaded;

/**
 * Test the OpenSwooleHttp entry point.
 *
 * OpenSwoole permits a single server per process, so each test runs in an
 * isolated process.
 */
#[RunTestsInSeparateProcesses]
final class OpenSwooleHttpTest extends TestCase
{
    protected function setUp(): void
    {
        if (! extension_loaded('openswoole')) {
            self::markTestSkipped('The openswoole extension is not loaded.');
        }

        OpenSwooleHttpFixture::reset();

        $container = self::createStub(ContainerContract::class);
        $container->method('getData')->willReturn(new ContainerData());

        $app = self::createStub(ApplicationContract::class);
        $app->method('getContainer')->willReturn($container);

        OpenSwooleHttpFixture::$app = $app;
    }

    public function testRunBuildsServerRegistersHandlersAndStarts(): void
    {
        OpenSwooleHttpFixture::run(new HttpConfig());

        self::assertTrue(OpenSwooleHttpFixture::$serverStarted);
    }

    public function testGetSwooleServerReturnsServer(): void
    {
        self::assertInstanceOf(Server::class, OpenSwooleHttp::getSwooleServer());
    }

    public function testGetRequestHandlerDispatchesToHandle(): void
    {
        $handler = OpenSwooleHttpFixture::getRequestHandler(OpenSwooleHttpFixture::$app, new ContainerData());

        $handler(new Request(), new Response());

        self::assertSame(1, OpenSwooleHttpFixture::$handleCallCount);
    }

    public function testOnStartDoesNotThrow(): void
    {
        $this->expectNotToPerformAssertions();

        OpenSwooleHttp::onStart(OpenSwooleHttp::getSwooleServer());
    }
}
