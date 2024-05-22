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

use Exception;
use Valkyrja\Annotation\Annotations;
use Valkyrja\Application\Valkyrja;
use Valkyrja\Client\Contract\Client;
use Valkyrja\Config\Config;
use Valkyrja\Console\Console;
use Valkyrja\Console\Input\Input;
use Valkyrja\Console\Kernel\Kernel as ConsoleKernel;
use Valkyrja\Console\Output\Output;
use Valkyrja\Container\Container;
use Valkyrja\Event\Dispatcher as Events;
use Valkyrja\Filesystem\Filesystem;
use Valkyrja\Http\Exception\HttpException;
use Valkyrja\Http\Exception\HttpRedirectException;
use Valkyrja\Http\Factory\ResponseFactory;
use Valkyrja\Http\Request\ServerRequest;
use Valkyrja\Http\Response\JsonResponse;
use Valkyrja\Http\Response\RedirectResponse;
use Valkyrja\Http\Response\Response;
use Valkyrja\HttpKernel\Kernel;
use Valkyrja\Log\Logger;
use Valkyrja\Routing\Dispatchers\Router;
use Valkyrja\Routing\Exceptions\InvalidRouteName;
use Valkyrja\Routing\Route;
use Valkyrja\Session\Session;
use Valkyrja\Support\Directory;
use Valkyrja\View\View;

use function is_string;
use function Valkyrja\abort;
use function Valkyrja\app;
use function Valkyrja\appPath;
use function Valkyrja\basePath;
use function Valkyrja\cachePath;
use function Valkyrja\configPath;
use function Valkyrja\env;
use function Valkyrja\json;
use function Valkyrja\logger;
use function Valkyrja\publicPath;
use function Valkyrja\redirect;
use function Valkyrja\redirectRoute;
use function Valkyrja\redirectTo;
use function Valkyrja\resourcesPath;
use function Valkyrja\responseFactory;
use function Valkyrja\routeUrl;
use function Valkyrja\storagePath;
use function Valkyrja\testsPath;
use function Valkyrja\vendorPath;
use function Valkyrja\view;

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
        self::assertTrue(app() instanceof Valkyrja);
    }

    /**
     * Test the container() helper method.
     *
     * @return void
     */
    public function testContainer(): void
    {
        self::assertTrue(\Valkyrja\container() instanceof Container);
    }

    /**
     * Test the events() helper method.
     *
     * @return void
     */
    public function testEvents(): void
    {
        self::assertTrue(\Valkyrja\events() instanceof Events);
    }

    /**
     * Test the env() helper method.
     *
     * @return void
     */
    public function testEnv(): void
    {
        self::assertTrue(is_string(env()));
    }

    /**
     * Test the config() helper method.
     *
     * @return void
     */
    public function testConfig(): void
    {
        self::assertTrue(\Valkyrja\config() instanceof Config);
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
            self::assertSame(HttpException::class, $exception::class);
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
            self::assertSame(HttpRedirectException::class, $exception::class);
        }
    }

    /**
     * Test the annotations() helper method.
     *
     * @return void
     */
    public function testAnnotations(): void
    {
        self::assertTrue(\Valkyrja\annotator() instanceof Annotations);
    }

    /**
     * Test the client() helper method.
     *
     * @return void
     */
    public function testClient(): void
    {
        self::assertTrue(\Valkyrja\client() instanceof Client);
    }

    /**
     * Test the console() helper method.
     *
     * @return void
     */
    public function testConsole(): void
    {
        self::assertTrue(\Valkyrja\console() instanceof Console);
    }

    /**
     * Test the consoleKernel() helper method.
     *
     * @return void
     */
    public function testConsoleKernel(): void
    {
        self::assertTrue(\Valkyrja\consoleKernel() instanceof ConsoleKernel);
    }

    /**
     * Test the filesystem() helper method.
     *
     * @return void
     */
    public function testFilesystem(): void
    {
        self::assertTrue(\Valkyrja\filesystem() instanceof Filesystem);
    }

    /**
     * Test the input() helper method.
     *
     * @return void
     */
    public function testInput(): void
    {
        self::assertTrue(\Valkyrja\input() instanceof Input);
    }

    /**
     * Test the kernel() helper method.
     *
     * @return void
     */
    public function testKernel(): void
    {
        self::assertTrue(\Valkyrja\kernel() instanceof Kernel);
    }

    /**
     * Test the logger() helper method.
     *
     * @return void
     */
    public function testLogger(): void
    {
        self::assertTrue(logger() instanceof Logger);
    }

    /**
     * Test the output() helper method.
     *
     * @return void
     */
    public function testOutput(): void
    {
        self::assertTrue(\Valkyrja\output() instanceof Output);
    }

    /**
     * Test the request() helper method.
     *
     * @return void
     */
    public function testRequest(): void
    {
        self::assertTrue(\Valkyrja\request() instanceof ServerRequest);
    }

    /**
     * Test the router() helper method.
     *
     * @return void
     */
    public function testRouter(): void
    {
        self::assertTrue(\Valkyrja\router() instanceof Router);
    }

    /**
     * Test the route() helper method.
     *
     * @throws InvalidRouteName
     *
     * @return void
     */
    public function testRoute(): void
    {
        self::assertTrue(\Valkyrja\route('welcome') instanceof Route);
    }

    /**
     * Test the routeUrl() helper method.
     *
     * @return void
     */
    public function testRouteUrl(): void
    {
        self::assertSame('/', routeUrl('welcome'));
    }

    /**
     * Test the response() helper method.
     *
     * @return void
     */
    public function testResponse(): void
    {
        self::assertTrue(\Valkyrja\response() instanceof Response);
    }

    /**
     * Test the response() helper method with arguments.
     *
     * @return void
     */
    public function testResponseWithArgs(): void
    {
        self::assertTrue(\Valkyrja\response('test') instanceof Response);
    }

    /**
     * Test the json() helper method.
     *
     * @return void
     */
    public function testJson(): void
    {
        self::assertTrue(json() instanceof JsonResponse);
    }

    /**
     * Test the json() helper method with arguments.
     *
     * @return void
     */
    public function testJsonWithArgs(): void
    {
        self::assertTrue(json(['test' => 'value']) instanceof JsonResponse);
    }

    /**
     * Test the redirect() helper method.
     *
     * @return void
     */
    public function testRedirect(): void
    {
        self::assertTrue(redirect() instanceof RedirectResponse);
    }

    /**
     * Test the redirect() helper method with arguments.
     *
     * @return void
     */
    public function testRedirectWithArgs(): void
    {
        self::assertTrue(redirect('/') instanceof RedirectResponse);
    }

    /**
     * Test the redirectRoute() helper method.
     *
     * @return void
     */
    public function testRedirectRoute(): void
    {
        self::assertTrue(redirectRoute('welcome') instanceof RedirectResponse);
    }

    /**
     * Test the responseBuilder() helper method.
     *
     * @return void
     */
    public function testResponseBuilder(): void
    {
        self::assertTrue(responseFactory() instanceof ResponseFactory);
    }

    /**
     * Test the session() helper method.
     *
     * @return void
     */
    public function testSession(): void
    {
        self::assertTrue(\Valkyrja\session() instanceof Session);
    }

    /**
     * Test the view() helper method.
     *
     * @return void
     */
    public function testView(): void
    {
        self::assertTrue(view() instanceof View);
    }

    /**
     * Test the basePath directory helper method.
     *
     * @return void
     */
    public function testBasePath(): void
    {
        self::assertSame(Directory::$BASE_PATH, basePath());
    }

    /**
     * Test the basePath directory helper method with a sub path.
     *
     * @return void
     */
    public function testBasePathSubPath(): void
    {
        $expected = Directory::$BASE_PATH . $this->subPath;

        self::assertSame($expected, basePath($this->subPath));
    }

    /**
     * Test the appPath directory helper method.
     *
     * @return void
     */
    public function testAppPath(): void
    {
        $expected = Directory::$BASE_PATH . Directory::DIRECTORY_SEPARATOR . Directory::$APP_PATH;

        self::assertSame($expected, appPath());
    }

    /**
     * Test the appPath directory helper method with a sub path.
     *
     * @return void
     */
    public function testAppPathSubPath(): void
    {
        $expected = Directory::$BASE_PATH . Directory::DIRECTORY_SEPARATOR . Directory::$APP_PATH . $this->subPath;

        self::assertSame($expected, appPath($this->subPath));
    }

    /**
     * Test the cachePath directory helper method.
     *
     * @return void
     */
    public function testCachePath(): void
    {
        $expected = Directory::$BASE_PATH
            . Directory::DIRECTORY_SEPARATOR
            . Directory::$STORAGE_PATH
            . Directory::DIRECTORY_SEPARATOR
            . Directory::$FRAMEWORK_STORAGE_PATH
            . Directory::DIRECTORY_SEPARATOR
            . Directory::$CACHE_PATH;

        self::assertSame($expected, cachePath());
    }

    /**
     * Test the cachePath directory helper method with a sub path.
     *
     * @return void
     */
    public function testCachePathSubPath(): void
    {
        $expected = Directory::$BASE_PATH
            . Directory::DIRECTORY_SEPARATOR
            . Directory::$STORAGE_PATH
            . Directory::DIRECTORY_SEPARATOR
            . Directory::$FRAMEWORK_STORAGE_PATH
            . Directory::DIRECTORY_SEPARATOR
            . Directory::$CACHE_PATH
            . $this->subPath;

        self::assertSame($expected, cachePath($this->subPath));
    }

    /**
     * Test the configPath directory helper method.
     *
     * @return void
     */
    public function testConfigPath(): void
    {
        $expected = Directory::$BASE_PATH . Directory::DIRECTORY_SEPARATOR . Directory::$CONFIG_PATH;

        self::assertSame($expected, configPath());
    }

    /**
     * Test the configPath directory helper method with a sub path.
     *
     * @return void
     */
    public function testConfigPathSubPath(): void
    {
        $expected = Directory::$BASE_PATH . Directory::DIRECTORY_SEPARATOR . Directory::$CONFIG_PATH . $this->subPath;

        self::assertSame($expected, configPath($this->subPath));
    }

    /**
     * Test the publicPath directory helper method.
     *
     * @return void
     */
    public function testPublicPath(): void
    {
        $expected = Directory::$BASE_PATH . Directory::DIRECTORY_SEPARATOR . Directory::$PUBLIC_PATH;

        self::assertSame($expected, publicPath());
    }

    /**
     * Test the publicPath directory helper method with a sub path.
     *
     * @return void
     */
    public function testPublicPathSubPath(): void
    {
        $expected = Directory::$BASE_PATH . Directory::DIRECTORY_SEPARATOR . Directory::$PUBLIC_PATH . $this->subPath;

        self::assertSame($expected, publicPath($this->subPath));
    }

    /**
     * Test the resourcesPath directory helper method.
     *
     * @return void
     */
    public function testResourcesPath(): void
    {
        $expected = Directory::$BASE_PATH . Directory::DIRECTORY_SEPARATOR . Directory::$RESOURCES_PATH;

        self::assertSame($expected, resourcesPath());
    }

    /**
     * Test the resourcesPath directory helper method with a sub path.
     *
     * @return void
     */
    public function testResourcesPathSubPath(): void
    {
        $expected
            = Directory::$BASE_PATH . Directory::DIRECTORY_SEPARATOR . Directory::$RESOURCES_PATH . $this->subPath;

        self::assertSame($expected, resourcesPath($this->subPath));
    }

    /**
     * Test the storagePath directory helper method.
     *
     * @return void
     */
    public function testStoragePath(): void
    {
        $expected = Directory::$BASE_PATH . Directory::DIRECTORY_SEPARATOR . Directory::$STORAGE_PATH;

        self::assertSame($expected, storagePath());
    }

    /**
     * Test the storagePath directory helper method with a sub path.
     *
     * @return void
     */
    public function testStoragePathSubPath(): void
    {
        $expected = Directory::$BASE_PATH . Directory::DIRECTORY_SEPARATOR . Directory::$STORAGE_PATH . $this->subPath;

        self::assertSame($expected, storagePath($this->subPath));
    }

    /**
     * Test the testsPath directory helper method.
     *
     * @return void
     */
    public function testTestsPath(): void
    {
        $expected = Directory::$BASE_PATH . Directory::DIRECTORY_SEPARATOR . Directory::$TESTS_PATH;

        self::assertSame($expected, testsPath());
    }

    /**
     * Test the testsPath directory helper method with a sub path.
     *
     * @return void
     */
    public function testTestsPathSubPath(): void
    {
        $expected = Directory::$BASE_PATH . Directory::DIRECTORY_SEPARATOR . Directory::$TESTS_PATH . $this->subPath;

        self::assertSame($expected, testsPath($this->subPath));
    }

    /**
     * Test the vendorPath directory helper method.
     *
     * @return void
     */
    public function testVendorPath(): void
    {
        $expected = Directory::$BASE_PATH . Directory::DIRECTORY_SEPARATOR . Directory::$VENDOR_PATH;

        self::assertSame($expected, vendorPath());
    }

    /**
     * Test the vendorPath directory helper method with a sub path.
     *
     * @return void
     */
    public function testVendorPathSubPath(): void
    {
        $expected = Directory::$BASE_PATH . Directory::DIRECTORY_SEPARATOR . Directory::$VENDOR_PATH . $this->subPath;

        self::assertSame($expected, vendorPath($this->subPath));
    }
}
