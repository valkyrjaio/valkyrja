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
use Valkyrja\Application\Contract\Application;
use Valkyrja\Application\Valkyrja;
use Valkyrja\Client\Contract\Client;
use Valkyrja\Config\Command\ConfigCache;
use Valkyrja\Config\Constant\ConfigKey;
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
use Valkyrja\Tests\Config;
use Valkyrja\Tests\Env;
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
        self::assertSame(Application::VERSION, $this->app->version());
    }

    /**
     * Test the config() helper method.
     *
     * @return void
     */
    public function testConfig(): void
    {
        self::assertInstanceOf(Config::class, $this->app->config());
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

        self::assertSame($config, $this->app->config()['new'] ?? null);
    }

    /**
     * Test the env() helper method.
     *
     * @return void
     */
    public function testEnv(): void
    {
        self::assertIsString($this->app::env());
    }

    /**
     * Test the env() helper method.
     *
     * @return void
     */
    public function testEnvValue(): void
    {
        self::assertTrue($this->app::env('CONSOLE_QUIET'));
    }

    /**
     * Test the getEnv() helper method.
     *
     * @return void
     */
    public function testGetEnv(): void
    {
        self::assertSame(Env::class, $this->app::getEnv());
    }

    /**
     * Test the getEnv() helper method.
     *
     * @return void
     */
    public function testSetEnv(): void
    {
        $this->app::setEnv(Env::class);
        self::assertSame(Env::class, $this->app::getEnv());
    }

    /**
     * Test the environment() helper method.
     *
     * @return void
     */
    public function testEnvironment(): void
    {
        self::assertSame($this->app->config()['app']['env'], $this->app->environment());
    }

    /**
     * Test the debug() helper method.
     *
     * @return void
     */
    public function testDebug(): void
    {
        self::assertSame($this->app->config()['app']['debug'], $this->app->debug());
    }

    /**
     * Test the annotations() helper method.
     *
     * @return void
     */
    public function testAnnotations(): void
    {
        self::assertInstanceOf(Annotations::class, $this->app[Annotations::class]);
    }

    /**
     * Test the client() helper method.
     *
     * @return void
     */
    public function testClient(): void
    {
        self::assertInstanceOf(Client::class, $this->app[Client::class]);
    }

    /**
     * Test the console() helper method.
     *
     * @return void
     */
    public function testConsole(): void
    {
        self::assertInstanceOf(Console::class, $this->app[Console::class]);
    }

    /**
     * Test the consoleKernel() helper method.
     *
     * @return void
     */
    public function testConsoleKernel(): void
    {
        self::assertInstanceOf(ConsoleKernel::class, $this->app[ConsoleKernel::class]);
    }

    /**
     * Test the filesystem() helper method.
     *
     * @return void
     */
    public function testFilesystem(): void
    {
        self::assertInstanceOf(Filesystem::class, $this->app[Filesystem::class]);
    }

    /**
     * Test the kernel() helper method.
     *
     * @return void
     */
    public function testKernel(): void
    {
        self::assertInstanceOf(RequestHandler::class, $this->app[RequestHandler::class]);
    }

    /**
     * Test the pathGenerator() helper method.
     *
     * @return void
     */
    public function testPathGenerator(): void
    {
        self::assertInstanceOf(Generator::class, $this->app[Generator::class]);
    }

    /**
     * Test the pathParser() helper method.
     *
     * @return void
     */
    public function testPathParser(): void
    {
        self::assertInstanceOf(Parser::class, $this->app[Parser::class]);
    }

    /**
     * Test the logger() helper method.
     *
     * @return void
     */
    public function testLogger(): void
    {
        self::assertInstanceOf(Logger::class, $this->app[Logger::class]);
    }

    /**
     * Test the router() helper method.
     *
     * @return void
     */
    public function testRouter(): void
    {
        self::assertInstanceOf(Router::class, $this->app[Router::class]);
    }

    /**
     * Test the responseBuilder() helper method.
     *
     * @return void
     */
    public function testResponseBuilder(): void
    {
        self::assertInstanceOf(ResponseFactory::class, $this->app[ResponseFactory::class]);
    }

    /**
     * Test the session() helper method.
     *
     * @return void
     */
    public function testSession(): void
    {
        self::assertInstanceOf(Session::class, $this->app[Session::class]);
    }

    /**
     * Test the view() helper method.
     *
     * @return void
     */
    public function testView(): void
    {
        self::assertInstanceOf(View::class, $this->app[View::class]);
    }

    /**
     * Test the application setup being called a second time without forcing.
     *
     * @return void
     */
    public function testSetupTwice(): void
    {
        // Try to re-setup the application without forcing
        $this->app->setup(Config::class);

        // It shouldn't have used the new config settings and kept the old
        // so debug should still be false
        self::assertFalse($this->app->debug());
    }

    /**
     * Test the application setup with debug on.
     *
     * @return void
     */
    public function testDebugOn(): void
    {
        $config = new Config();

        $config->app->debug = true;
        $this->app          = $this->app->withConfig($config);

        self::assertTrue($this->app->debug());
    }

    /**
     * Test resetting the application with a config provider.
     *
     * @return void
     */
    public function testApplicationSetupWithConfigProvider(): void
    {
        $config            = new Config();
        $config->providers = [
            ProviderClass::class,
        ];

        $this->app = $this->app->withConfig($config);

        self::assertSame(ProviderClass::class, $this->app->config()['providers'][0]);

        $this->app = $this->app->withConfig(new Config());
    }

    /**
     * Test resetting the application with a config provider.
     *
     * @return void
     */
    public function testApplicationSetupWithCachedConfig(): void
    {
        /** @var Console $console */
        $console = $this->app[Console::class];
        // Get the config cache command
        $configCacheCommand = $console->matchCommand(ConfigCache::COMMAND);
        // Run the config cache command
        $console->dispatchCommand($configCacheCommand);

        // Resetup the app with the new config and force
        $this->app->setup(Config::class);

        // Because the app will use the config cache the forced changes to the config made above shouldn't
        // take effect and the value for app.debug should still be false.
        self::assertFalse($this->app->debug());

        usleep(10);

        // Delete the config cache file to avoid headaches later
        unlink($this->app->config(ConfigKey::CONFIG_CACHE_FILE_PATH));

        // Reset the application to normal operations
        $this->app = $this->app->withConfig(new Config());
    }
}
