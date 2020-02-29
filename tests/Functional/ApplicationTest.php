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

use Exception;
use TypeError;
use Valkyrja\Annotation\Annotators\Annotator;
use Valkyrja\Application\Applications\Valkyrja;
use Valkyrja\Client\Client;
use Valkyrja\Config\Commands\ConfigCache;
use Valkyrja\Config\Config;
use Valkyrja\Config\Enums\ConfigKey;
use Valkyrja\Console\Dispatchers\Console;
use Valkyrja\Console\Kernels\Kernel as ConsoleKernel;
use Valkyrja\Container\Dispatchers\Container;
use Valkyrja\Dispatcher\Dispatchers\Dispatcher;
use Valkyrja\Env\Env;
use Valkyrja\Env\EnvTest;
use Valkyrja\Event\Dispatchers\Events;
use Valkyrja\Filesystem\Filesystems\Filesystem;
use Valkyrja\Http\Exceptions\HttpException;
use Valkyrja\Http\Exceptions\HttpRedirectException;
use Valkyrja\Http\Factories\ResponseFactory;
use Valkyrja\Http\Kernels\Kernel;
use Valkyrja\Http\Requests\Request;
use Valkyrja\Http\Responses\JsonResponse;
use Valkyrja\Http\Responses\RedirectResponse;
use Valkyrja\Http\Responses\Response;
use Valkyrja\Logging\Loggers\MonologLogger;
use Valkyrja\Path\Generators\PathGenerator;
use Valkyrja\Path\Parsers\PathParser;
use Valkyrja\Routing\Dispatchers\Router;
use Valkyrja\Session\Sessions\Session;
use Valkyrja\Tests\Unit\Container\InvalidContainerClass;
use Valkyrja\Tests\Unit\Dispatcher\InvalidDispatcherClass;
use Valkyrja\Tests\Unit\Events\InvalidEventsClass;
use Valkyrja\Tests\Unit\Support\ProviderClass;
use Valkyrja\View\Views\View;

use function get_class;
use function is_string;

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

        $this->assertEquals(true, isset($this->app->config()->new));
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
        $this->assertEquals($this->app->config()->app->env, $this->app->environment());
    }

    /**
     * Test the debug() helper method.
     *
     * @return void
     */
    public function testDebug(): void
    {
        $this->assertEquals($this->app->config()->app->debug, $this->app->debug());
    }

    /**
     * Test the isCompiled() helper method.
     *
     * @return void
     */
    public function testIsCompiled(): void
    {
        $this->assertEquals(false, $this->app->isCompiled());
    }

    /**
     * Test the setCompiled() helper method.
     *
     * @return void
     */
    public function testSetCompiled(): void
    {
        $this->assertEquals(null, $this->app->setCompiled() ?? null);
    }

    /**
     * Test the abort() helper method.
     *
     * @return void
     */
    public function testAbort(): void
    {
        try {
            $this->app->abort();
        } catch (Exception $exception) {
            $this->assertEquals(HttpException::class, get_class($exception));
        }
    }

    /**
     * Test the redirectTo() helper method.
     *
     * @return void
     */
    public function testRedirectTo(): void
    {
        try {
            $this->app->redirectTo();
        } catch (Exception $exception) {
            $this->assertEquals(HttpRedirectException::class, get_class($exception));
        }
    }

    /**
     * Test the annotations() helper method.
     *
     * @return void
     */
    public function testAnnotations(): void
    {
        $this->assertEquals(true, $this->app->annotator() instanceof Annotator);
    }

    /**
     * Test the client() helper method.
     *
     * @return void
     */
    public function testClient(): void
    {
        $this->assertEquals(true, $this->app->client() instanceof Client);
    }

    /**
     * Test the console() helper method.
     *
     * @return void
     */
    public function testConsole(): void
    {
        $this->assertEquals(true, $this->app->console() instanceof Console);
    }

    /**
     * Test the consoleKernel() helper method.
     *
     * @return void
     */
    public function testConsoleKernel(): void
    {
        $this->assertEquals(true, $this->app->consoleKernel() instanceof ConsoleKernel);
    }

    /**
     * Test the filesystem() helper method.
     *
     * @return void
     */
    public function testFilesystem(): void
    {
        $this->assertEquals(true, $this->app->filesystem() instanceof Filesystem);
    }

    /**
     * Test the kernel() helper method.
     *
     * @return void
     */
    public function testKernel(): void
    {
        $this->assertEquals(true, $this->app->kernel() instanceof Kernel);
    }

    /**
     * Test the pathGenerator() helper method.
     *
     * @return void
     */
    public function testPathGenerator(): void
    {
        $this->assertEquals(true, $this->app->pathGenerator() instanceof PathGenerator);
    }

    /**
     * Test the pathParser() helper method.
     *
     * @return void
     */
    public function testPathParser(): void
    {
        $this->assertEquals(true, $this->app->pathParser() instanceof PathParser);
    }

    /**
     * Test the logger() helper method.
     *
     * @return void
     */
    public function testLogger(): void
    {
        $this->assertEquals(true, $this->app->logger() instanceof MonologLogger);
    }

    /**
     * Test the request() helper method.
     *
     * @return void
     */
    public function testRequest(): void
    {
        $this->assertEquals(true, $this->app->request() instanceof Request);
    }

    /**
     * Test the router() helper method.
     *
     * @return void
     */
    public function testRouter(): void
    {
        $this->assertEquals(true, $this->app->router() instanceof Router);
    }

    /**
     * Test the response() helper method.
     *
     * @return void
     */
    public function testResponse(): void
    {
        $this->assertEquals(true, $this->app->response() instanceof Response);
    }

    /**
     * Test the response() helper method with arguments.
     *
     * @return void
     */
    public function testResponseWithArgs(): void
    {
        $this->assertEquals(true, $this->app->response('test') instanceof Response);
    }

    /**
     * Test the json() helper method.
     *
     * @return void
     */
    public function testJson(): void
    {
        $this->assertEquals(true, $this->app->json() instanceof JsonResponse);
    }

    /**
     * Test the json() helper method with arguments.
     *
     * @return void
     */
    public function testJsonWithArgs(): void
    {
        $this->assertEquals(true, $this->app->json(['test' => 'value']) instanceof JsonResponse);
    }

    /**
     * Test the redirect() helper method.
     *
     * @return void
     */
    public function testRedirect(): void
    {
        $this->assertEquals(true, $this->app->redirect() instanceof RedirectResponse);
    }

    /**
     * Test the redirect() helper method with arguments.
     *
     * @return void
     */
    public function testRedirectWithArgs(): void
    {
        $this->assertEquals(true, $this->app->redirect('/') instanceof RedirectResponse);
    }

    /**
     * Test the redirectRoute() helper method.
     *
     * @return void
     */
    public function testRedirectRoute(): void
    {
        $this->assertEquals(true, $this->app->redirectRoute('welcome') instanceof RedirectResponse);
    }

    /**
     * Test the responseBuilder() helper method.
     *
     * @return void
     */
    public function testResponseBuilder(): void
    {
        $this->assertEquals(true, $this->app->responseFactory() instanceof ResponseFactory);
    }

    /**
     * Test the session() helper method.
     *
     * @return void
     */
    public function testSession(): void
    {
        $this->assertEquals(true, $this->app->session() instanceof Session);
    }

    /**
     * Test the view() helper method.
     *
     * @return void
     */
    public function testView(): void
    {
        $this->assertEquals(true, $this->app->view() instanceof View);
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
        $this->app = $this->app->withConfig($config);

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
            $this->app = $this->app->withConfig($config);
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
            $this->app = $this->app->withConfig($config);
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
            $this->app = $this->app->withConfig($config);
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

        $this->assertEquals(ProviderClass::class, $this->app->config()->providers[0]);

        $this->app = $this->app->withConfig(new Config());
    }

    /**
     * Test resetting the application with a config provider.
     *
     * @return void
     */
    public function testApplicationSetupWithCachedConfig(): void
    {
        // Get the config cache command
        $configCacheCommand = $this->app->console()->matchCommand(ConfigCache::COMMAND);
        // Run the config cache command
        $this->app->console()->dispatchCommand($configCacheCommand);

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
