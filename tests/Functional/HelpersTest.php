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
use Valkyrja\Annotations\Annotations;
use Valkyrja\Application;
use Valkyrja\Console\Console;
use Valkyrja\Console\Kernel as ConsoleKernel;
use Valkyrja\Container\Container;
use Valkyrja\Events\Events;
use Valkyrja\Http\Client;
use Valkyrja\Http\Exceptions\HttpException;
use Valkyrja\Http\Exceptions\HttpRedirectException;
use Valkyrja\Http\JsonResponse;
use Valkyrja\Http\Kernel;
use Valkyrja\Http\RedirectResponse;
use Valkyrja\Http\Request;
use Valkyrja\Http\Response;
use Valkyrja\Http\ResponseBuilder;
use Valkyrja\Logger\Logger;
use Valkyrja\Routing\Router;
use Valkyrja\Session\Session;
use Valkyrja\View\View;

/**
 * Test the functionality of the helper functions.
 *
 * @author Melech Mizrachi
 */
class HelpersTest extends TestCase
{
    /**
     * Test the app() static helper method.
     *
     * @return void
     */
    public function testApp(): void
    {
        $this->assertEquals(true, app() instanceof Application);
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
        $this->assertEquals(true, events() instanceof Events);
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
        $this->assertEquals(true, annotations() instanceof Annotations);
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
        $this->assertEquals(true, logger() instanceof Logger);
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
        $this->assertEquals(true, redirectRoute('home.welcome') instanceof RedirectResponse);
    }

    /**
     * Test the responseBuilder() helper method.
     *
     * @return void
     */
    public function testResponseBuilder(): void
    {
        $this->assertEquals(true, responseBuilder() instanceof ResponseBuilder);
    }

    /**
     * Test the session() helper method.
     *
     * @return void
     */
    public function testSession(): void
    {
        $this->assertEquals(true, session() instanceof Session);
    }

    /**
     * Test the view() helper method.
     *
     * @return void
     */
    public function testView(): void
    {
        $this->assertEquals(true, view() instanceof View);
    }
}
