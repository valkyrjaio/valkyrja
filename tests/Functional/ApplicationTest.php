<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Tests\Functional;

use TypeError;
use Valkyrja\Annotation\Annotator;
use Valkyrja\Application\Applications\Valkyrja;
use Valkyrja\Client\Client;
use Valkyrja\Config\Commands\ConfigCache;
use Valkyrja\Config\Enums\ConfigKey;
use Valkyrja\Console\Console;
use Valkyrja\Console\Kernel as ConsoleKernel;
use Valkyrja\Container\Dispatchers\Container;
use Valkyrja\Dispatcher\Dispatchers\Dispatcher;
use Valkyrja\Env\Env;
use Valkyrja\Event\Dispatchers\Events;
use Valkyrja\Filesystem\Filesystem;
use Valkyrja\Http\JsonResponse;
use Valkyrja\Http\RedirectResponse;
use Valkyrja\Http\Request;
use Valkyrja\Http\Response;
use Valkyrja\Http\ResponseFactory;
use Valkyrja\HttpKernel\Kernel;
use Valkyrja\Log\Logger;
use Valkyrja\Path\PathGenerator;
use Valkyrja\Path\PathParser;
use Valkyrja\Routing\Router;
use Valkyrja\Session\Session;
use Valkyrja\Tests\Config;
use Valkyrja\Tests\EnvTest;
use Valkyrja\Tests\Unit\Container\InvalidContainerClass;
use Valkyrja\Tests\Unit\Dispatcher\InvalidDispatcherClass;
use Valkyrja\Tests\Unit\Events\InvalidEventsClass;
use Valkyrja\Tests\Unit\Support\ProviderClass;
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
        $this->assertEquals(true, $this->app instanceof Valkyrja);
    }

    /**
     * Test the app() static helper method.
     *
     * @return void
     */
    public function testApp(): void
    {
        $this->assertEquals(true, Valkyrja::app() instanceof Valkyrja);
    }

    /**
     * Test the container() helper method.
     *
     * @return void
     */
    public function testContainer(): void
    {
        $this->assertEquals(true, $this->app->container() instanceof Container);
    }

    /**
     * Test the dispatcher() helper method.
     *
     * @return void
     */
    public function testDispatcher(): void
    {
        $this->assertEquals(true, $this->app->dispatcher() instanceof Dispatcher);
    }

    /**
     * Test the events() helper method.
     *
     * @return void
     */
    public function testEvents(): void
    {
        $this->assertEquals(true, $this->app->events() instanceof Events);
    }

    /**
     * Test the version() helper method.
     *
     * @return void
     */
    public function testVersion(): void
    {
        $this->assertEquals(Valkyrja::VERSION, $this->app->version());
    }

    /**
     * Test the config() helper method.
     *
     * @return void
     */
    public function testConfig(): void
    {
        $this->assertEquals(true, ($this->app->config() instanceof Config));
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

        $this->assertEquals(true, isset($this->app->config()['new']));
    }

    /**
     * Test the env() helper method.
     *
     * @return void
     */
    public function testEnv(): void
    {
        $this->assertEquals(true, is_string($this->app::env()));
    }

    /**
     * Test the env() helper method.
     *
     * @return void
     */
    public function testEnvValue(): void
    {
        $this->assertEquals(true, $this->app::env('CONSOLE_QUIET'));
    }

    /**
     * Test the getEnv() helper method.
     *
     * @return void
     */
    public function testGetEnv(): void
    {
        $this->assertEquals(EnvTest::class, $this->app::getEnv());
    }

    /**
     * Test the getEnv() helper method.
     *
     * @return void
     */
    public function testSetEnv(): void
    {
        $this->app::setEnv(Env::class);

        $this->assertEquals(Env::class, $this->app::getEnv());

        $this->app::setEnv(EnvTest::class);
    }

    /**
     * Test the environment() helper method.
     *
     * @return void
     */
    public function testEnvironment(): void
    {
        $this->assertEquals($this->app->config()['app']['env'], $this->app->environment());
    }

    /**
     * Test the debug() helper method.
     *
     * @return void
     */
    public function testDebug(): void
    {
        $this->assertEquals($this->app->config()['app']['debug'], $this->app->debug());
    }

    /**
     * Test the annotations() helper method.
     *
     * @return void
     */
    public function testAnnotations(): void
    {
        $this->assertEquals(true, $this->app[Annotator::class] instanceof Annotator);
    }

    /**
     * Test the client() helper method.
     *
     * @return void
     */
    public function testClient(): void
    {
        $this->assertEquals(true, $this->app[Client::class] instanceof Client);
    }

    /**
     * Test the console() helper method.
     *
     * @return void
     */
    public function testConsole(): void
    {
        $this->assertEquals(true, $this->app[Console::class] instanceof Console);
    }

    /**
     * Test the consoleKernel() helper method.
     *
     * @return void
     */
    public function testConsoleKernel(): void
    {
        $this->assertEquals(true, $this->app[ConsoleKernel::class] instanceof ConsoleKernel);
    }

    /**
     * Test the filesystem() helper method.
     *
     * @return void
     */
    public function testFilesystem(): void
    {
        $this->assertEquals(true, $this->app[Filesystem::class] instanceof Filesystem);
    }

    /**
     * Test the kernel() helper method.
     *
     * @return void
     */
    public function testKernel(): void
    {
        $this->assertEquals(true, $this->app[Kernel::class] instanceof Kernel);
    }

    /**
     * Test the pathGenerator() helper method.
     *
     * @return void
     */
    public function testPathGenerator(): void
    {
        $this->assertEquals(true, $this->app[PathGenerator::class] instanceof PathGenerator);
    }

    /**
     * Test the pathParser() helper method.
     *
     * @return void
     */
    public function testPathParser(): void
    {
        $this->assertEquals(true, $this->app[PathParser::class] instanceof PathParser);
    }

    /**
     * Test the logger() helper method.
     *
     * @return void
     */
    public function testLogger(): void
    {
        $this->assertEquals(true, $this->app[Logger::class] instanceof Logger);
    }

    /**
     * Test the request() helper method.
     *
     * @return void
     */
    public function testRequest(): void
    {
        $this->assertEquals(true, $this->app[Request::class] instanceof Request);
    }

    /**
     * Test the router() helper method.
     *
     * @return void
     */
    public function testRouter(): void
    {
        $this->assertEquals(true, $this->app[Router::class] instanceof Router);
    }

    /**
     * Test the response() helper method.
     *
     * @return void
     */
    public function testResponse(): void
    {
        $this->assertEquals(true, $this->app[Response::class] instanceof Response);
    }

    /**
     * Test the json() helper method.
     *
     * @return void
     */
    public function testJson(): void
    {
        $this->assertEquals(true, $this->app[JsonResponse::class] instanceof JsonResponse);
    }

    /**
     * Test the redirect() helper method.
     *
     * @return void
     */
    public function testRedirect(): void
    {
        $this->assertEquals(true, $this->app[RedirectResponse::class] instanceof RedirectResponse);
    }

    /**
     * Test the responseBuilder() helper method.
     *
     * @return void
     */
    public function testResponseBuilder(): void
    {
        $this->assertEquals(true, $this->app[ResponseFactory::class] instanceof ResponseFactory);
    }

    /**
     * Test the session() helper method.
     *
     * @return void
     */
    public function testSession(): void
    {
        $this->assertEquals(true, $this->app[Session::class] instanceof Session);
    }

    /**
     * Test the view() helper method.
     *
     * @return void
     */
    public function testView(): void
    {
        $this->assertEquals(true, $this->app[View::class] instanceof View);
    }

    /**
     * Test the application setup being called a second time without forcing.
     *
     * @return void
     */
    public function testSetupTwice(): void
    {
        // Try to re-setup the application without forcing
        $this->app->setup(ConfigTest::class);

        // It shouldn't have used the new config settings and kept the old
        // so debug should still be false
        $this->assertEquals(false, $this->app->debug());
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

        $this->assertEquals(true, $this->app->debug());
    }

    /**
     * Test an invalid dispatcher class.
     *
     * @return void
     */
    public function testInvalidDispatcher(): void
    {
        try {
            $config = new Config();

            $config->app->dispatcher = InvalidDispatcherClass::class;
            $this->app               = $this->app->withConfig($config);
        } catch (TypeError $exception) {
            $this->assertInstanceOf(TypeError::class, $exception);
        }

        $this->app = $this->app->withConfig(new Config());
    }

    /**
     * Test an invalid container class.
     *
     * @return void
     */
    public function testInvalidContainer(): void
    {
        try {
            $config = new Config();

            $config->app->container = InvalidContainerClass::class;
            $this->app              = $this->app->withConfig($config);
        } catch (TypeError $exception) {
            $this->assertInstanceOf(TypeError::class, $exception);
        }

        $this->app = $this->app->withConfig(new Config());
    }

    /**
     * Test an invalid container class.
     *
     * @return void
     */
    public function testInvalidEvents(): void
    {
        try {
            $config = new Config();

            $config->app->events = InvalidEventsClass::class;
            $this->app           = $this->app->withConfig($config);
        } catch (TypeError $exception) {
            $this->assertInstanceOf(TypeError::class, $exception);
        }

        $this->app = $this->app->withConfig(new Config());
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

        $this->assertEquals(ProviderClass::class, $this->app->config()['providers'][0]);

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
        $this->app->setup(ConfigTest::class);

        // Because the app will use the config cache the forced changes to the config made above shouldn't
        // take effect and the value for app.debug should still be false.
        $this->assertEquals(false, $this->app->debug());

        // Delete the config cache file to avoid headaches later
        unlink($this->app->config(ConfigKey::CONFIG_CACHE_FILE_PATH));

        // Reset the application to normal operations
        $this->app = $this->app->withConfig(new Config());
    }
}
