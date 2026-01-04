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

namespace Valkyrja\Tests\Functional;

use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Container\Manager\Container;
use Valkyrja\Filesystem\Manager\Contract\FilesystemContract;
use Valkyrja\Http\Client\Manager\Contract\ClientContract;
use Valkyrja\Http\Message\Factory\Contract\ResponseFactoryContract;
use Valkyrja\Http\Routing\Dispatcher\Contract\RouterContract;
use Valkyrja\Http\Server\Handler\Contract\RequestHandlerContract;
use Valkyrja\Log\Logger\Contract\LoggerContract;
use Valkyrja\Session\Manager\Contract\SessionContract;
use Valkyrja\Tests\EnvClass;
use Valkyrja\View\Renderer\Contract\RendererContract;

/**
 * Test the functionality of the Application.
 */
class ApplicationTest extends TestCase
{
    /**
     * Test the container() helper method.
     */
    public function testContainer(): void
    {
        self::assertInstanceOf(Container::class, $this->app->getContainer());
    }

    /**
     * Test the version() helper method.
     */
    public function testVersion(): void
    {
        self::assertSame(ApplicationContract::VERSION, $this->app->getVersion());
    }

    /**
     * Test the getEnv() helper method.
     */
    public function testGetEnv(): void
    {
        self::assertSame($this->env, $this->app->getEnv());
    }

    /**
     * Test the getEnv() helper method.
     */
    public function testSetEnv(): void
    {
        $this->app->setEnv($env = new EnvClass());
        self::assertSame($env, $this->app->getEnv());
    }

    /**
     * Test the environment() helper method.
     */
    public function testEnvironment(): void
    {
        self::assertSame($this->app->getEnv()::APP_ENV, $this->app->getEnvironment());
    }

    /**
     * Test the debug() helper method.
     */
    public function testDebug(): void
    {
        self::assertSame($this->app->getEnv()::APP_DEBUG_MODE, $this->app->getDebugMode());
    }

    /**
     * Test the client() helper method.
     */
    public function testClient(): void
    {
        self::assertInstanceOf(ClientContract::class, $this->app->getContainer()->getSingleton(ClientContract::class));
    }

    /**
     * Test the filesystem() helper method.
     */
    public function testFilesystem(): void
    {
        self::assertInstanceOf(FilesystemContract::class, $this->app->getContainer()->getSingleton(FilesystemContract::class));
    }

    /**
     * Test the kernel() helper method.
     */
    public function testKernel(): void
    {
        self::assertInstanceOf(RequestHandlerContract::class, $this->app->getContainer()->getSingleton(RequestHandlerContract::class));
    }

    /**
     * Test the logger() helper method.
     */
    public function testLogger(): void
    {
        self::assertInstanceOf(LoggerContract::class, $this->app->getContainer()->getSingleton(LoggerContract::class));
    }

    /**
     * Test the router() helper method.
     */
    public function testRouter(): void
    {
        self::assertInstanceOf(RouterContract::class, $this->app->getContainer()->getSingleton(RouterContract::class));
    }

    /**
     * Test the responseBuilder() helper method.
     */
    public function testResponseBuilder(): void
    {
        self::assertInstanceOf(ResponseFactoryContract::class, $this->app->getContainer()->getSingleton(ResponseFactoryContract::class));
    }

    /**
     * Test the session() helper method.
     */
    public function testSession(): void
    {
        self::assertInstanceOf(SessionContract::class, $this->app->getContainer()->getSingleton(SessionContract::class));
    }

    /**
     * Test the view() helper method.
     */
    public function testRenderer(): void
    {
        self::assertInstanceOf(RendererContract::class, $this->app->getContainer()->getSingleton(RendererContract::class));
    }
}
