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

use Valkyrja\Application\Contract\Application;
use Valkyrja\Container\Container;
use Valkyrja\Filesystem\Manager\Contract\Filesystem;
use Valkyrja\Http\Client\Contract\Client;
use Valkyrja\Http\Message\Factory\Contract\ResponseFactory;
use Valkyrja\Http\Routing\Contract\Router;
use Valkyrja\Http\Server\Contract\RequestHandler;
use Valkyrja\Log\Logger\Contract\Logger;
use Valkyrja\Session\Manager\Contract\Session;
use Valkyrja\Tests\EnvClass;
use Valkyrja\View\Renderer\Contract\Renderer;

/**
 * Test the functionality of the Application.
 *
 * @author Melech Mizrachi
 */
class ApplicationTest extends TestCase
{
    /**
     * Test the container() helper method.
     *
     * @return void
     */
    public function testContainer(): void
    {
        self::assertInstanceOf(Container::class, $this->app->getContainer());
    }

    /**
     * Test the version() helper method.
     *
     * @return void
     */
    public function testVersion(): void
    {
        self::assertSame(Application::VERSION, $this->app->getVersion());
    }

    /**
     * Test the getEnv() helper method.
     *
     * @return void
     */
    public function testGetEnv(): void
    {
        self::assertSame($this->env, $this->app->getEnv());
    }

    /**
     * Test the getEnv() helper method.
     *
     * @return void
     */
    public function testSetEnv(): void
    {
        $this->app->setEnv($env = new EnvClass());
        self::assertSame($env, $this->app->getEnv());
    }

    /**
     * Test the environment() helper method.
     *
     * @return void
     */
    public function testEnvironment(): void
    {
        self::assertSame($this->app->getEnv()::APP_ENV, $this->app->getEnvironment());
    }

    /**
     * Test the debug() helper method.
     *
     * @return void
     */
    public function testDebug(): void
    {
        self::assertSame($this->app->getEnv()::APP_DEBUG_MODE, $this->app->getDebugMode());
    }

    /**
     * Test the client() helper method.
     *
     * @return void
     */
    public function testClient(): void
    {
        self::assertInstanceOf(Client::class, $this->app->getContainer()->getSingleton(Client::class));
    }

    /**
     * Test the filesystem() helper method.
     *
     * @return void
     */
    public function testFilesystem(): void
    {
        self::assertInstanceOf(Filesystem::class, $this->app->getContainer()->getSingleton(Filesystem::class));
    }

    /**
     * Test the kernel() helper method.
     *
     * @return void
     */
    public function testKernel(): void
    {
        self::assertInstanceOf(RequestHandler::class, $this->app->getContainer()->getSingleton(RequestHandler::class));
    }

    /**
     * Test the logger() helper method.
     *
     * @return void
     */
    public function testLogger(): void
    {
        self::assertInstanceOf(Logger::class, $this->app->getContainer()->getSingleton(Logger::class));
    }

    /**
     * Test the router() helper method.
     *
     * @return void
     */
    public function testRouter(): void
    {
        self::assertInstanceOf(Router::class, $this->app->getContainer()->getSingleton(Router::class));
    }

    /**
     * Test the responseBuilder() helper method.
     *
     * @return void
     */
    public function testResponseBuilder(): void
    {
        self::assertInstanceOf(ResponseFactory::class, $this->app->getContainer()->getSingleton(ResponseFactory::class));
    }

    /**
     * Test the session() helper method.
     *
     * @return void
     */
    public function testSession(): void
    {
        self::assertInstanceOf(Session::class, $this->app->getContainer()->getSingleton(Session::class));
    }

    /**
     * Test the view() helper method.
     *
     * @return void
     */
    public function testRenderer(): void
    {
        self::assertInstanceOf(Renderer::class, $this->app->getContainer()->getSingleton(Renderer::class));
    }
}
