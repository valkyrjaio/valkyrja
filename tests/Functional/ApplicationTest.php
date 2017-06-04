<?php

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
use Valkyrja\Annotations\AnnotationsImpl;
use Valkyrja\Config\Env;
use Valkyrja\Config\EnvTest;
use Valkyrja\Filesystem\FlyFilesystem;
use Valkyrja\Valkyrja;
use Valkyrja\Client\Client;
use Valkyrja\Config\Commands\ConfigCache;
use Valkyrja\Console\ConsoleImpl;
use Valkyrja\Console\KernelImpl as ConsoleKernel;
use Valkyrja\Container\ContainerImpl;
use Valkyrja\Dispatcher\DispatcherImpl;
use Valkyrja\Events\EventsImpl;
use Valkyrja\Exceptions\InvalidContainerImplementation;
use Valkyrja\Exceptions\InvalidDispatcherImplementation;
use Valkyrja\Exceptions\InvalidEventsImplementation;
use Valkyrja\Http\Exceptions\HttpException;
use Valkyrja\Http\Exceptions\HttpRedirectException;
use Valkyrja\Http\JsonResponseImpl;
use Valkyrja\Http\KernelImpl;
use Valkyrja\Http\RedirectResponseImpl;
use Valkyrja\Http\RequestImpl;
use Valkyrja\Http\ResponseImpl;
use Valkyrja\Http\ResponseBuilderImpl;
use Valkyrja\Logger\MonologLogger;
use Valkyrja\Path\PathGeneratorImpl;
use Valkyrja\Path\PathParserImpl;
use Valkyrja\Routing\RouterImpl;
use Valkyrja\Session\NativeSession;
use Valkyrja\Tests\Unit\Container\InvalidContainerClass;
use Valkyrja\Tests\Unit\Dispatcher\InvalidDispatcherClass;
use Valkyrja\Tests\Unit\Events\InvalidEventsClass;
use Valkyrja\Tests\Unit\Support\ProviderClass;
use Valkyrja\View\PhpView;

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
        $this->assertEquals(true, $this->app->container() instanceof ContainerImpl);
    }

    /**
     * Test the dispatcher() helper method.
     *
     * @return void
     */
    public function testDispatcher(): void
    {
        $this->assertEquals(true, $this->app->dispatcher() instanceof DispatcherImpl);
    }

    /**
     * Test the events() helper method.
     *
     * @return void
     */
    public function testEvents(): void
    {
        $this->assertEquals(true, $this->app->events() instanceof EventsImpl);
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
        $this->assertEquals(true, is_array($this->app->config()));
    }

    /**
     * Test the addConfig() helper method.
     *
     * @return void
     */
    public function testAddConfig(): void
    {
        $this->app->addConfig(['new' => []]);

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
        $this->app::setEnv();

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
        $this->assertEquals(true, $this->app->annotations() instanceof AnnotationsImpl);
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
        $this->assertEquals(true, $this->app->console() instanceof ConsoleImpl);
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
        $this->assertEquals(true, $this->app->filesystem() instanceof FlyFilesystem);
    }

    /**
     * Test the kernel() helper method.
     *
     * @return void
     */
    public function testKernel(): void
    {
        $this->assertEquals(true, $this->app->kernel() instanceof KernelImpl);
    }

    /**
     * Test the pathGenerator() helper method.
     *
     * @return void
     */
    public function testPathGenerator(): void
    {
        $this->assertEquals(true, $this->app->pathGenerator() instanceof PathGeneratorImpl);
    }

    /**
     * Test the pathParser() helper method.
     *
     * @return void
     */
    public function testPathParser(): void
    {
        $this->assertEquals(true, $this->app->pathParser() instanceof PathParserImpl);
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
        $this->assertEquals(true, $this->app->request() instanceof RequestImpl);
    }

    /**
     * Test the router() helper method.
     *
     * @return void
     */
    public function testRouter(): void
    {
        $this->assertEquals(true, $this->app->router() instanceof RouterImpl);
    }

    /**
     * Test the response() helper method.
     *
     * @return void
     */
    public function testResponse(): void
    {
        $this->assertEquals(true, $this->app->response() instanceof ResponseImpl);
    }

    /**
     * Test the response() helper method with arguments.
     *
     * @return void
     */
    public function testResponseWithArgs(): void
    {
        $this->assertEquals(true, $this->app->response('test') instanceof ResponseImpl);
    }

    /**
     * Test the json() helper method.
     *
     * @return void
     */
    public function testJson(): void
    {
        $this->assertEquals(true, $this->app->json() instanceof JsonResponseImpl);
    }

    /**
     * Test the json() helper method with arguments.
     *
     * @return void
     */
    public function testJsonWithArgs(): void
    {
        $this->assertEquals(true, $this->app->json(['test' => 'value']) instanceof JsonResponseImpl);
    }

    /**
     * Test the redirect() helper method.
     *
     * @return void
     */
    public function testRedirect(): void
    {
        $this->assertEquals(true, $this->app->redirect() instanceof RedirectResponseImpl);
    }

    /**
     * Test the redirect() helper method with arguments.
     *
     * @return void
     */
    public function testRedirectWithArgs(): void
    {
        $this->assertEquals(true, $this->app->redirect('/') instanceof RedirectResponseImpl);
    }

    /**
     * Test the redirectRoute() helper method.
     *
     * @return void
     */
    public function testRedirectRoute(): void
    {
        $this->assertEquals(true, $this->app->redirectRoute('welcome') instanceof RedirectResponseImpl);
    }

    /**
     * Test the responseBuilder() helper method.
     *
     * @return void
     */
    public function testResponseBuilder(): void
    {
        $this->assertEquals(true, $this->app->responseBuilder() instanceof ResponseBuilderImpl);
    }

    /**
     * Test the session() helper method.
     *
     * @return void
     */
    public function testSession(): void
    {
        $this->assertEquals(true, $this->app->session() instanceof NativeSession);
    }

    /**
     * Test the view() helper method.
     *
     * @return void
     */
    public function testView(): void
    {
        $this->assertEquals(true, $this->app->view() instanceof PhpView);
    }

    /**
     * Test the application setup being called a second time without forcing.
     *
     * @return void
     */
    public function testSetupTwice(): void
    {
        $config = $this->app->config();

        // Set debug to true
        $config['app']['debug'] = true;
        // Try to re-setup the application without forcing
        $this->app->setup($config);

        // It shouldn't have used the new config settings and kept the old
        // so debug should still be false
        $this->assertEquals(false, $this->app->config()['app']['debug']);
    }

    /**
     * Test the application setup with debug on.
     *
     * @return void
     */
    public function testDebugOn(): void
    {
        $config = $this->app->config();

        $config['app']['debug'] = true;
        $this->app->setup($config, true);

        $this->assertEquals(true, $this->app->config()['app']['debug']);
    }

    /**
     * Test an invalid dispatcher class.
     *
     * @return void
     */
    public function testInvalidDispatcher(): void
    {
        try {
            $config = $this->app->config();

            $config['app']['dispatcher'] = InvalidDispatcherClass::class;
            $this->app->setup($config, true);
        } catch (Exception $exception) {
            $this->assertInstanceOf(InvalidDispatcherImplementation::class, $exception);
        }

        $this->app->setup(null, true);
    }

    /**
     * Test an invalid container class.
     *
     * @return void
     */
    public function testInvalidContainer(): void
    {
        try {
            $config = $this->app->config();

            $config['app']['container'] = InvalidContainerClass::class;
            $this->app->setup($config, true);
        } catch (Exception $exception) {
            $this->assertInstanceOf(InvalidContainerImplementation::class, $exception);
        }

        $this->app->setup(null, true);
    }

    /**
     * Test an invalid container class.
     *
     * @return void
     */
    public function testInvalidEvents(): void
    {
        try {
            $config = $this->app->config();

            $config['app']['events'] = InvalidEventsClass::class;
            $this->app->setup($config, true);
        } catch (Exception $exception) {
            $this->assertInstanceOf(InvalidEventsImplementation::class, $exception);
        }

        $this->app->setup(null, true);
    }

    /**
     * Test resetting the application with proper config.
     *
     * @return void
     */
    public function testResetApplication(): void
    {
        $this->assertEquals(null, $this->app->setup(null, true) ?? null);
    }

    /**
     * Test resetting the application with a config provider.
     *
     * @return void
     */
    public function testApplicationSetupWithConfigProvider(): void
    {
        $config              = $this->app->config();
        $config['providers'] = [
            ProviderClass::class,
        ];

        $this->app->setup($config, true);

        $this->assertEquals(ProviderClass::class, $this->app->config()['providers'][0]);

        $this->app->setup(null, true);
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

        // Set some config differently
        $config                 = $this->app->config();
        $config['app']['debug'] = true;

        // Resetup the app with the new config and force
        $this->app->setup($config, true);

        // Because the app will use the config cache the forced changes to the config made above shouldn't
        //take effect and the value for app.debug should still be false.
        $this->assertEquals(false, $this->app->config()['app']['debug']);

        // Delete the config cache file to avoid headaches later
        unlink($this->app->config()['cacheFilePath']);

        // Reset the application to normal operations
        $this->app->setup(null, true);
    }
}
