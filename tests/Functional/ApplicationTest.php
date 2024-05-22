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
use Valkyrja\Http\ResponseFactory;
use Valkyrja\HttpKernel\Contract\Kernel;
use Valkyrja\Log\Logger;
use Valkyrja\Path\Generator\Contract\Generator;
use Valkyrja\Path\Parser\Contract\Parser;
use Valkyrja\Routing\Router;
use Valkyrja\Session\Session;
use Valkyrja\Tests\Classes\Config\ProviderClass;
use Valkyrja\Tests\Config;
use Valkyrja\Tests\Env;
use Valkyrja\View\View;

use function is_string;
use function unlink;

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
        self::assertTrue(Valkyrja::app() instanceof Valkyrja);
    }

    /**
     * Test the container() helper method.
     *
     * @return void
     */
    public function testContainer(): void
    {
        self::assertTrue($this->app->container() instanceof Container);
    }

    /**
     * Test the dispatcher() helper method.
     *
     * @return void
     */
    public function testDispatcher(): void
    {
        self::assertTrue($this->app->dispatcher() instanceof Dispatcher);
    }

    /**
     * Test the events() helper method.
     *
     * @return void
     */
    public function testEvents(): void
    {
        self::assertTrue($this->app->events() instanceof Events);
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
        self::assertTrue($this->app->config() instanceof Config);
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
        self::assertTrue(is_string($this->app::env()));
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
        self::assertTrue($this->app[Annotations::class] instanceof Annotations);
    }

    /**
     * Test the client() helper method.
     *
     * @return void
     */
    public function testClient(): void
    {
        self::assertTrue($this->app[Client::class] instanceof Client);
    }

    /**
     * Test the console() helper method.
     *
     * @return void
     */
    public function testConsole(): void
    {
        self::assertTrue($this->app[Console::class] instanceof Console);
    }

    /**
     * Test the consoleKernel() helper method.
     *
     * @return void
     */
    public function testConsoleKernel(): void
    {
        self::assertTrue($this->app[ConsoleKernel::class] instanceof ConsoleKernel);
    }

    /**
     * Test the filesystem() helper method.
     *
     * @return void
     */
    public function testFilesystem(): void
    {
        self::assertTrue($this->app[Filesystem::class] instanceof Filesystem);
    }

    /**
     * Test the kernel() helper method.
     *
     * @return void
     */
    public function testKernel(): void
    {
        self::assertTrue($this->app[Kernel::class] instanceof Kernel);
    }

    /**
     * Test the pathGenerator() helper method.
     *
     * @return void
     */
    public function testPathGenerator(): void
    {
        self::assertTrue($this->app[Generator::class] instanceof Generator);
    }

    /**
     * Test the pathParser() helper method.
     *
     * @return void
     */
    public function testPathParser(): void
    {
        self::assertTrue($this->app[Parser::class] instanceof Parser);
    }

    /**
     * Test the logger() helper method.
     *
     * @return void
     */
    public function testLogger(): void
    {
        self::assertTrue($this->app[Logger::class] instanceof Logger);
    }

    /**
     * Test the router() helper method.
     *
     * @return void
     */
    public function testRouter(): void
    {
        self::assertTrue($this->app[Router::class] instanceof Router);
    }

    /**
     * Test the responseBuilder() helper method.
     *
     * @return void
     */
    public function testResponseBuilder(): void
    {
        self::assertTrue($this->app[ResponseFactory::class] instanceof ResponseFactory);
    }

    /**
     * Test the session() helper method.
     *
     * @return void
     */
    public function testSession(): void
    {
        self::assertTrue($this->app[Session::class] instanceof Session);
    }

    /**
     * Test the view() helper method.
     *
     * @return void
     */
    public function testView(): void
    {
        self::assertTrue($this->app[View::class] instanceof View);
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

        // Delete the config cache file to avoid headaches later
        unlink($this->app->config(ConfigKey::CONFIG_CACHE_FILE_PATH));

        // Reset the application to normal operations
        $this->app = $this->app->withConfig(new Config());
    }
}
