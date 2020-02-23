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
use Valkyrja\Annotation\Annotators\Annotator;
use Valkyrja\Application\Applications\Valkyrja;
use Valkyrja\Client\Client;
use Valkyrja\Console\Dispatchers\Console;
use Valkyrja\Console\Inputs\Input;
use Valkyrja\Console\Kernels\Kernel as ConsoleKernel;
use Valkyrja\Console\Outputs\Output;
use Valkyrja\Container\Dispatchers\Container;
use Valkyrja\Event\Dispatchers\NativeEvents;
use Valkyrja\Filesystem\FlyFilesystem;
use Valkyrja\Http\Exceptions\HttpException;
use Valkyrja\Http\Exceptions\HttpRedirectException;
use Valkyrja\Http\Factories\ResponseFactory;
use Valkyrja\Http\Kernels\Kernel;
use Valkyrja\Http\Requests\Request;
use Valkyrja\Http\Responses\JsonResponse;
use Valkyrja\Http\Responses\RedirectResponse;
use Valkyrja\Http\Responses\Response;
use Valkyrja\Logger\MonologLogger;
use Valkyrja\Routing\Dispatchers\Router;
use Valkyrja\Routing\Route;
use Valkyrja\Session\NativeSession;
use Valkyrja\Support\Directory;
use Valkyrja\View\PhpView;

use function get_class;
use function is_array;
use function is_string;

use const DIRECTORY_SEPARATOR;

/**
 * Test the functionality of the helper functions.
 *
 * @author Melech Mizrachi
 */
class HelpersTest extends TestCase
{
    /**
     * The sub path.
     *
     * @var string
     */
    protected string $subPath = '/sub/path';

    /**
     * Test the app() static helper method.
     *
     * @return void
     */
    public function testApp(): void
    {
        $this->assertEquals(true, app() instanceof Valkyrja);
    }

    /**
     * Test the container() helper method.
     *
     * @return void
     */
    public function testContainer(): void
    {
        $this->assertEquals(true, container() instanceof Container);
    }

    /**
     * Test the events() helper method.
     *
     * @return void
     */
    public function testEvents(): void
    {
        $this->assertEquals(true, events() instanceof NativeEvents);
    }

    /**
     * Test the env() helper method.
     *
     * @return void
     */
    public function testEnv(): void
    {
        $this->assertEquals(true, is_string(env()));
    }

    /**
     * Test the config() helper method.
     *
     * @return void
     */
    public function testConfig(): void
    {
        $this->assertEquals(true, is_array(config()));
    }

    /**
     * Test the abort() helper method.
     *
     * @return void
     */
    public function testAbort(): void
    {
        try {
            abort();
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
            redirectTo();
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
        $this->assertEquals(true, annotations() instanceof Annotator);
    }

    /**
     * Test the client() helper method.
     *
     * @return void
     */
    public function testClient(): void
    {
        $this->assertEquals(true, client() instanceof Client);
    }

    /**
     * Test the console() helper method.
     *
     * @return void
     */
    public function testConsole(): void
    {
        $this->assertEquals(true, console() instanceof Console);
    }

    /**
     * Test the consoleKernel() helper method.
     *
     * @return void
     */
    public function testConsoleKernel(): void
    {
        $this->assertEquals(true, consoleKernel() instanceof ConsoleKernel);
    }

    /**
     * Test the filesystem() helper method.
     *
     * @return void
     */
    public function testFilesystem(): void
    {
        $this->assertEquals(true, filesystem() instanceof FlyFilesystem);
    }

    /**
     * Test the input() helper method.
     *
     * @return void
     */
    public function testInput(): void
    {
        $this->assertEquals(true, input() instanceof Input);
    }

    /**
     * Test the kernel() helper method.
     *
     * @return void
     */
    public function testKernel(): void
    {
        $this->assertEquals(true, kernel() instanceof Kernel);
    }

    /**
     * Test the logger() helper method.
     *
     * @return void
     */
    public function testLogger(): void
    {
        $this->assertEquals(true, logger() instanceof MonologLogger);
    }

    /**
     * Test the output() helper method.
     *
     * @return void
     */
    public function testOutput(): void
    {
        $this->assertEquals(true, output() instanceof Output);
    }

    /**
     * Test the request() helper method.
     *
     * @return void
     */
    public function testRequest(): void
    {
        $this->assertEquals(true, request() instanceof Request);
    }

    /**
     * Test the router() helper method.
     *
     * @return void
     */
    public function testRouter(): void
    {
        $this->assertEquals(true, router() instanceof Router);
    }

    /**
     * Test the route() helper method.
     *
     * @return void
     */
    public function testRoute(): void
    {
        $this->assertEquals(true, route('welcome') instanceof Route);
    }

    /**
     * Test the routeUrl() helper method.
     *
     * @return void
     */
    public function testRouteUrl(): void
    {
        $this->assertEquals('/', routeUrl('welcome'));
    }

    /**
     * Test the response() helper method.
     *
     * @return void
     */
    public function testResponse(): void
    {
        $this->assertEquals(true, response() instanceof Response);
    }

    /**
     * Test the response() helper method with arguments.
     *
     * @return void
     */
    public function testResponseWithArgs(): void
    {
        $this->assertEquals(true, response('test') instanceof Response);
    }

    /**
     * Test the json() helper method.
     *
     * @return void
     */
    public function testJson(): void
    {
        $this->assertEquals(true, json() instanceof JsonResponse);
    }

    /**
     * Test the json() helper method with arguments.
     *
     * @return void
     */
    public function testJsonWithArgs(): void
    {
        $this->assertEquals(true, json(['test' => 'value']) instanceof JsonResponse);
    }

    /**
     * Test the redirect() helper method.
     *
     * @return void
     */
    public function testRedirect(): void
    {
        $this->assertEquals(true, redirect() instanceof RedirectResponse);
    }

    /**
     * Test the redirect() helper method with arguments.
     *
     * @return void
     */
    public function testRedirectWithArgs(): void
    {
        $this->assertEquals(true, redirect('/') instanceof RedirectResponse);
    }

    /**
     * Test the redirectRoute() helper method.
     *
     * @return void
     */
    public function testRedirectRoute(): void
    {
        $this->assertEquals(true, redirectRoute('welcome') instanceof RedirectResponse);
    }

    /**
     * Test the responseBuilder() helper method.
     *
     * @return void
     */
    public function testResponseBuilder(): void
    {
        $this->assertEquals(true, responseBuilder() instanceof ResponseFactory);
    }

    /**
     * Test the session() helper method.
     *
     * @return void
     */
    public function testSession(): void
    {
        $this->assertEquals(true, session() instanceof NativeSession);
    }

    /**
     * Test the view() helper method.
     *
     * @return void
     */
    public function testView(): void
    {
        $this->assertEquals(true, view() instanceof PhpView);
    }

    /**
     * Test the basePath directory helper method.
     *
     * @return void
     */
    public function testBasePath(): void
    {
        $this->assertEquals(Directory::$BASE_PATH, basePath());
    }

    /**
     * Test the basePath directory helper method with a sub path.
     *
     * @return void
     */
    public function testBasePathSubPath(): void
    {
        $expected = Directory::$BASE_PATH . $this->subPath;

        $this->assertEquals($expected, basePath($this->subPath));
    }

    /**
     * Test the appPath directory helper method.
     *
     * @return void
     */
    public function testAppPath(): void
    {
        $expected = Directory::$BASE_PATH . DIRECTORY_SEPARATOR . Directory::$APP_PATH;

        $this->assertEquals($expected, appPath());
    }

    /**
     * Test the appPath directory helper method with a sub path.
     *
     * @return void
     */
    public function testAppPathSubPath(): void
    {
        $expected = Directory::$BASE_PATH . DIRECTORY_SEPARATOR . Directory::$APP_PATH . $this->subPath;

        $this->assertEquals($expected, appPath($this->subPath));
    }

    /**
     * Test the cachePath directory helper method.
     *
     * @return void
     */
    public function testCachePath(): void
    {
        $expected = Directory::$BASE_PATH
            . DIRECTORY_SEPARATOR
            . Directory::$STORAGE_PATH
            . DIRECTORY_SEPARATOR
            . Directory::$FRAMEWORK_STORAGE_PATH
            . DIRECTORY_SEPARATOR
            . Directory::$CACHE_PATH;

        $this->assertEquals($expected, cachePath());
    }

    /**
     * Test the cachePath directory helper method with a sub path.
     *
     * @return void
     */
    public function testCachePathSubPath(): void
    {
        $expected = Directory::$BASE_PATH
            . DIRECTORY_SEPARATOR
            . Directory::$STORAGE_PATH
            . DIRECTORY_SEPARATOR
            . Directory::$FRAMEWORK_STORAGE_PATH
            . DIRECTORY_SEPARATOR
            . Directory::$CACHE_PATH
            . $this->subPath;

        $this->assertEquals($expected, cachePath($this->subPath));
    }

    /**
     * Test the configPath directory helper method.
     *
     * @return void
     */
    public function testConfigPath(): void
    {
        $expected = Directory::$BASE_PATH . DIRECTORY_SEPARATOR . Directory::$CONFIG_PATH;

        $this->assertEquals($expected, configPath());
    }

    /**
     * Test the configPath directory helper method with a sub path.
     *
     * @return void
     */
    public function testConfigPathSubPath(): void
    {
        $expected = Directory::$BASE_PATH . DIRECTORY_SEPARATOR . Directory::$CONFIG_PATH . $this->subPath;

        $this->assertEquals($expected, configPath($this->subPath));
    }

    /**
     * Test the publicPath directory helper method.
     *
     * @return void
     */
    public function testPublicPath(): void
    {
        $expected = Directory::$BASE_PATH . DIRECTORY_SEPARATOR . Directory::$PUBLIC_PATH;

        $this->assertEquals($expected, publicPath());
    }

    /**
     * Test the publicPath directory helper method with a sub path.
     *
     * @return void
     */
    public function testPublicPathSubPath(): void
    {
        $expected = Directory::$BASE_PATH . DIRECTORY_SEPARATOR . Directory::$PUBLIC_PATH . $this->subPath;

        $this->assertEquals($expected, publicPath($this->subPath));
    }

    /**
     * Test the resourcesPath directory helper method.
     *
     * @return void
     */
    public function testResourcesPath(): void
    {
        $expected = Directory::$BASE_PATH . DIRECTORY_SEPARATOR . Directory::$RESOURCES_PATH;

        $this->assertEquals($expected, resourcesPath());
    }

    /**
     * Test the resourcesPath directory helper method with a sub path.
     *
     * @return void
     */
    public function testResourcesPathSubPath(): void
    {
        $expected = Directory::$BASE_PATH . DIRECTORY_SEPARATOR . Directory::$RESOURCES_PATH . $this->subPath;

        $this->assertEquals($expected, resourcesPath($this->subPath));
    }

    /**
     * Test the storagePath directory helper method.
     *
     * @return void
     */
    public function testStoragePath(): void
    {
        $expected = Directory::$BASE_PATH . DIRECTORY_SEPARATOR . Directory::$STORAGE_PATH;

        $this->assertEquals($expected, storagePath());
    }

    /**
     * Test the storagePath directory helper method with a sub path.
     *
     * @return void
     */
    public function testStoragePathSubPath(): void
    {
        $expected = Directory::$BASE_PATH . DIRECTORY_SEPARATOR . Directory::$STORAGE_PATH . $this->subPath;

        $this->assertEquals($expected, storagePath($this->subPath));
    }

    /**
     * Test the testsPath directory helper method.
     *
     * @return void
     */
    public function testTestsPath(): void
    {
        $expected = Directory::$BASE_PATH . DIRECTORY_SEPARATOR . Directory::$TESTS_PATH;

        $this->assertEquals($expected, testsPath());
    }

    /**
     * Test the testsPath directory helper method with a sub path.
     *
     * @return void
     */
    public function testTestsPathSubPath(): void
    {
        $expected = Directory::$BASE_PATH . DIRECTORY_SEPARATOR . Directory::$TESTS_PATH . $this->subPath;

        $this->assertEquals($expected, testsPath($this->subPath));
    }

    /**
     * Test the vendorPath directory helper method.
     *
     * @return void
     */
    public function testVendorPath(): void
    {
        $expected = Directory::$BASE_PATH . DIRECTORY_SEPARATOR . Directory::$VENDOR_PATH;

        $this->assertEquals($expected, vendorPath());
    }

    /**
     * Test the vendorPath directory helper method with a sub path.
     *
     * @return void
     */
    public function testVendorPathSubPath(): void
    {
        $expected = Directory::$BASE_PATH . DIRECTORY_SEPARATOR . Directory::$VENDOR_PATH . $this->subPath;

        $this->assertEquals($expected, vendorPath($this->subPath));
    }
}
