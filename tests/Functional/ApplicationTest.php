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

use Valkyrja\Annotation\Contract\Annotations;
use Valkyrja\Application\Config\Valkyrja as ValkyrjaConfig;
use Valkyrja\Application\Contract\Application;
use Valkyrja\Application\Valkyrja;
use Valkyrja\Client\Contract\Client;
use Valkyrja\Config\Config\Config;
use Valkyrja\Console\Command\OptimizeCacheCommand;
use Valkyrja\Console\Contract\Console;
use Valkyrja\Console\Kernel\Contract\Kernel as ConsoleKernel;
use Valkyrja\Container\Container;
use Valkyrja\Dispatcher\Dispatcher;
use Valkyrja\Event\Dispatcher as Events;
use Valkyrja\Filesystem\Contract\Filesystem;
use Valkyrja\Http\Message\Factory\Contract\ResponseFactory;
use Valkyrja\Http\Routing\Contract\Router;
use Valkyrja\Http\Server\Contract\RequestHandler;
use Valkyrja\Log\Contract\Logger;
use Valkyrja\Path\Generator\Contract\Generator;
use Valkyrja\Path\Parser\Contract\Parser;
use Valkyrja\Session\Contract\Session;
use Valkyrja\Tests\Classes\Config\ProviderClass;
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
     * Test the Application construct.
     *
     * @return void
     */
    public function testConstruct(): void
    {
        self::assertTrue($this->app instanceof Valkyrja);
    }

    /**
     * Test the app() static helper method.
     *
     * @return void
     */
    public function testApp(): void
    {
        self::assertInstanceOf(Valkyrja::class, Valkyrja::app());
    }

    /**
     * Test the container() helper method.
     *
     * @return void
     */
    public function testContainer(): void
    {
        self::assertInstanceOf(Container::class, $this->app->container());
    }

    /**
     * Test the dispatcher() helper method.
     *
     * @return void
     */
    public function testDispatcher(): void
    {
        self::assertInstanceOf(Dispatcher::class, $this->app->dispatcher());
    }

    /**
     * Test the events() helper method.
     *
     * @return void
     */
    public function testEvents(): void
    {
        self::assertInstanceOf(Events::class, $this->app->events());
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
        $this->app->addConfig($config, 'new');

        self::assertSame($config, $this->app->getConfig()->new ?? null);
    }

    /**
     * Test the env() helper method.
     *
     * @return void
     */
    public function testEnv(): void
    {
        self::assertIsString($this->app::getEnv());
    }

    /**
     * Test the env() helper method.
     *
     * @return void
     */
    public function testEnvValue(): void
    {
        self::assertTrue($this->app::getEnvValue('CONSOLE_SHOULD_RUN_QUIETLY'));
    }

    /**
     * Test the getEnv() helper method.
     *
     * @return void
     */
    public function testGetEnv(): void
    {
        self::assertSame(EnvClass::class, $this->app::getEnv());
    }

    /**
     * Test the getEnv() helper method.
     *
     * @return void
     */
    public function testSetEnv(): void
    {
        $this->app::setEnv(EnvClass::class);
        self::assertSame(EnvClass::class, $this->app::getEnv());
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
        self::assertSame($this->app->getConfig()->app->debug, $this->app->getDebugMode());
    }

    /**
     * Test the annotations() helper method.
     *
     * @return void
     */
    public function testAnnotations(): void
    {
        self::assertInstanceOf(Annotations::class, $this->app->container()->getSingleton(Annotations::class));
    }

    /**
     * Test the client() helper method.
     *
     * @return void
     */
    public function testClient(): void
    {
        self::assertInstanceOf(Client::class, $this->app->container()->getSingleton(Client::class));
    }

    /**
     * Test the console() helper method.
     *
     * @return void
     */
    public function testConsole(): void
    {
        self::assertInstanceOf(Console::class, $this->app->container()->getSingleton(Console::class));
    }

    /**
     * Test the consoleKernel() helper method.
     *
     * @return void
     */
    public function testConsoleKernel(): void
    {
        self::assertInstanceOf(ConsoleKernel::class, $this->app->container()->getSingleton(ConsoleKernel::class));
    }

    /**
     * Test the filesystem() helper method.
     *
     * @return void
     */
    public function testFilesystem(): void
    {
        self::assertInstanceOf(Filesystem::class, $this->app->container()->getSingleton(Filesystem::class));
    }

    /**
     * Test the kernel() helper method.
     *
     * @return void
     */
    public function testKernel(): void
    {
        self::assertInstanceOf(RequestHandler::class, $this->app->container()->getSingleton(RequestHandler::class));
    }

    /**
     * Test the pathGenerator() helper method.
     *
     * @return void
     */
    public function testPathGenerator(): void
    {
        self::assertInstanceOf(Generator::class, $this->app->container()->getSingleton(Generator::class));
    }

    /**
     * Test the pathParser() helper method.
     *
     * @return void
     */
    public function testPathParser(): void
    {
        self::assertInstanceOf(Parser::class, $this->app->container()->getSingleton(Parser::class));
    }

    /**
     * Test the logger() helper method.
     *
     * @return void
     */
    public function testLogger(): void
    {
        self::assertInstanceOf(Logger::class, $this->app->container()->getSingleton(Logger::class));
    }

    /**
     * Test the router() helper method.
     *
     * @return void
     */
    public function testRouter(): void
    {
        self::assertInstanceOf(Router::class, $this->app->container()->getSingleton(Router::class));
    }

    /**
     * Test the responseBuilder() helper method.
     *
     * @return void
     */
    public function testResponseBuilder(): void
    {
        self::assertInstanceOf(ResponseFactory::class, $this->app->container()->getSingleton(ResponseFactory::class));
    }

    /**
     * Test the session() helper method.
     *
     * @return void
     */
    public function testSession(): void
    {
        self::assertInstanceOf(Session::class, $this->app->container()->getSingleton(Session::class));
    }

    /**
     * Test the view() helper method.
     *
     * @return void
     */
    public function testView(): void
    {
        self::assertInstanceOf(View::class, $this->app->container()->getSingleton(View::class));
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
        $config = new ConfigClass(env: EnvClass::class);

        $config->app->debug = true;
        $this->app          = $this->app->setConfig($config);

        self::assertTrue($this->app->getDebugMode());

        restore_error_handler();
        restore_exception_handler();
    }

    /**
     * Test resetting the application with a config provider.
     *
     * @return void
     */
    public function testApplicationSetupWithConfigProvider(): void
    {
        $oldConfig                 = $this->app->getConfig();
        $config                    = new ValkyrjaConfig(env: EnvClass::class);
        $config->config->providers = [
            ProviderClass::class,
        ];

        $this->app = $this->app->setConfig($config);

        self::assertSame(ProviderClass::class, $this->app->getConfig()->config->providers[0]);

        $this->app = $this->app->setConfig($oldConfig);
    }

    /**
     * Test resetting the application with a config provider.
     *
     * @return void
     */
    public function testApplicationSetupWithCachedConfig(): void
    {
        /** @var Console $console */
        $console = $this->app->container()->getSingleton(Console::class);
        // Get the config cache command
        $configCacheCommand = $console->matchCommand(OptimizeCacheCommand::COMMAND);
        // Run the config cache command
        $console->dispatchCommand($configCacheCommand);

        // Resetup the app with the new config and force
        $this->app->setup(ConfigClass::class);

        // Because the app will use the config cache the forced changes to the config made above shouldn't
        // take effect and the value for app.debug should still be false.
        self::assertFalse($this->app->getDebugMode());

        usleep(100);

        $cacheFilePath = $this->app->getConfig()->config->cacheFilePath;

        if (is_file($cacheFilePath)) {
            // Delete the config cache file to avoid headaches later
            unlink($cacheFilePath);
        }
    }
}
