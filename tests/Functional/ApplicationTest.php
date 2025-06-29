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

use Valkyrja\Application\Command\CacheCommand;
use Valkyrja\Application\Config;
use Valkyrja\Application\Contract\Application;
use Valkyrja\Cli\Interaction\Input\Input;
use Valkyrja\Cli\Routing\Contract\Router as CliRouter;
use Valkyrja\Container\Container;
use Valkyrja\Filesystem\Contract\Filesystem;
use Valkyrja\Http\Client\Contract\Client;
use Valkyrja\Http\Message\Factory\Contract\ResponseFactory;
use Valkyrja\Http\Routing\Contract\Router;
use Valkyrja\Http\Server\Contract\RequestHandler;
use Valkyrja\Log\Contract\Logger;
use Valkyrja\Session\Contract\Session;
use Valkyrja\Tests\ConfigClass;
use Valkyrja\Tests\EnvClass;
use Valkyrja\View\Contract\View;

use function unlink;
use function usleep;

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
     * Test the config() helper method.
     *
     * @return void
     */
    public function testConfig(): void
    {
        self::assertInstanceOf(ConfigClass::class, $this->app->getConfig());
    }

    /**
     * Test the addConfig() helper method.
     *
     * @return void
     */
    public function testAddConfig(): void
    {
        $config = new Config();
        $this->app->addConfig('new', $config);

        self::assertSame($config, $this->app->getConfig()->new ?? null);
    }

    /**
     * Test the env() helper method.
     *
     * @return void
     */
    public function testEnv(): void
    {
        self::assertIsString($this->app->getEnv());
    }

    /**
     * Test the getEnv() helper method.
     *
     * @return void
     */
    public function testGetEnv(): void
    {
        self::assertSame(EnvClass::class, $this->app->getEnv());
    }

    /**
     * Test the getEnv() helper method.
     *
     * @return void
     */
    public function testSetEnv(): void
    {
        $this->app->setEnv(EnvClass::class);
        self::assertSame(EnvClass::class, $this->app->getEnv());
    }

    /**
     * Test the environment() helper method.
     *
     * @return void
     */
    public function testEnvironment(): void
    {
        self::assertSame($this->app->getConfig()->app->env, $this->app->getEnvironment());
    }

    /**
     * Test the debug() helper method.
     *
     * @return void
     */
    public function testDebug(): void
    {
        self::assertSame($this->app->getConfig()->app->debugMode, $this->app->getDebugMode());
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
    public function testView(): void
    {
        self::assertInstanceOf(View::class, $this->app->getContainer()->getSingleton(View::class));
    }

    /**
     * Test the application setup being called a second time without forcing.
     *
     * @return void
     */
    public function testSetupTwice(): void
    {
        // Try to re-setup the application without forcing
        $this->app->setup(ConfigClass::class);

        // It shouldn't have used the new config settings and kept the old
        // so debug should still be false
        self::assertFalse($this->app->getDebugMode());
    }

    /**
     * Test the application setup with debug on.
     *
     * @return void
     */
    public function testDebugOn(): void
    {
        $config = clone $this->app->getConfig();

        $config->app->debugMode = true;

        $this->app = $this->app->setConfig($config);

        self::assertTrue($this->app->getDebugMode());
    }

    /**
     * Test resetting the application with a config provider.
     *
     * @return void
     */
    public function testApplicationSetupWithCachedConfig(): void
    {
        /** @var CliRouter $cliRouter */
        $cliRouter = $this->app->getContainer()->getSingleton(CliRouter::class);
        // Run the config cache command
        $cliRouter->dispatch(new Input(commandName: CacheCommand::NAME));

        // Resetup the app with the new config and force
        $this->app->setup(ConfigClass::class);

        // Because the app will use the config cache the forced changes to the config made above shouldn't
        // take effect and the value for app.debug should still be false.
        self::assertFalse($this->app->getDebugMode());

        usleep(100);

        $cacheFilePath = $this->app->getConfig()->app->cacheFilePath;

        if (is_file($cacheFilePath)) {
            // Delete the config cache file to avoid headaches later
            unlink($cacheFilePath);
        }
    }
}
