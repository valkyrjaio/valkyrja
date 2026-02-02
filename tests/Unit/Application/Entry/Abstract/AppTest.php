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

namespace Valkyrja\Tests\Unit\Application\Entry\Abstract;

use Override;
use stdClass;
use Throwable;
use Valkyrja\Application\Cli\Command\CacheCommand;
use Valkyrja\Application\Cli\Command\ClearCacheCommand;
use Valkyrja\Application\Data\Data;
use Valkyrja\Application\Entry\Abstract\App;
use Valkyrja\Application\Env\Env;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Application\Provider\Provider;
use Valkyrja\Application\Throwable\Exception\RuntimeException;
use Valkyrja\Attribute\Collector\Contract\CollectorContract as AttributeCollectorContract;
use Valkyrja\Cli\Interaction\Data\Config as CliInteractionConfig;
use Valkyrja\Cli\Interaction\Output\Factory\Contract\OutputFactoryContract;
use Valkyrja\Cli\Middleware\Handler\Contract\ExitedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\InputReceivedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\RouteDispatchedHandlerContract as CliRouteDispatchedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\RouteMatchedHandlerContract as CliRouteMatchedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\RouteNotMatchedHandlerContract as CliRouteNotMatchedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\ThrowableCaughtHandlerContract as CliThrowableCaughtHandlerContract;
use Valkyrja\Cli\Routing\Collection\Contract\CollectionContract as CliRoutingCollection;
use Valkyrja\Cli\Routing\Collector\Contract\CollectorContract as CliRoutingCollector;
use Valkyrja\Cli\Routing\Data\Data as CliData;
use Valkyrja\Cli\Routing\Dispatcher\Contract\RouterContract as CliRoutingRouter;
use Valkyrja\Cli\Server\Command\HelpCommand;
use Valkyrja\Cli\Server\Command\ListBashCommand;
use Valkyrja\Cli\Server\Command\ListCommand as CliListCommand;
use Valkyrja\Cli\Server\Command\VersionCommand;
use Valkyrja\Cli\Server\Handler\Contract\InputHandlerContract;
use Valkyrja\Cli\Server\Middleware\ThrowableCaught\LogThrowableCaughtMiddleware as CliLogThrowableCaughtMiddleware;
use Valkyrja\Container\Data\Data as ContainerData;
use Valkyrja\Dispatch\Dispatcher\Contract\DispatcherContract;
use Valkyrja\Event\Collection\Contract\CollectionContract as EventCollection;
use Valkyrja\Event\Collector\Contract\CollectorContract as EventCollector;
use Valkyrja\Event\Data\Data as EventData;
use Valkyrja\Event\Dispatcher\Contract\DispatcherContract as EventDispatcher;
use Valkyrja\Http\Message\Response\Factory\Contract\ResponseFactoryContract;
use Valkyrja\Http\Middleware\Handler\Contract\RequestReceivedHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\RouteDispatchedHandlerContract as HttpRouteDispatchedHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\RouteMatchedHandlerContract as HttpRouteMatchedHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\RouteNotMatchedHandlerContract as HttpRouteNotMatchedHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\SendingResponseHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\TerminatedHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\ThrowableCaughtHandlerContract as HttpThrowableCaughtHandler;
use Valkyrja\Http\Routing\Cli\Command\ListCommand as HttpListCommand;
use Valkyrja\Http\Routing\Collection\Contract\CollectionContract as HttpRoutingCollection;
use Valkyrja\Http\Routing\Collector\Contract\CollectorContract;
use Valkyrja\Http\Routing\Data\Data as HttpData;
use Valkyrja\Http\Routing\Dispatcher\Contract\RouterContract as HttpRoutingRouter;
use Valkyrja\Http\Routing\Factory\Contract\ResponseFactoryContract as HttpRoutingResponseFactory;
use Valkyrja\Http\Routing\Matcher\Contract\MatcherContract;
use Valkyrja\Http\Routing\Processor\Contract\ProcessorContract;
use Valkyrja\Http\Routing\Url\Contract\UrlContract;
use Valkyrja\Http\Server\Handler\Contract\RequestHandlerContract;
use Valkyrja\Http\Server\Middleware\CacheResponseMiddleware;
use Valkyrja\Http\Server\Middleware\RouteMatched\RequestStructMiddleware;
use Valkyrja\Http\Server\Middleware\RouteMatched\ResponseStructMiddleware;
use Valkyrja\Http\Server\Middleware\RouteNotMatched\ViewRouteNotMatchedMiddleware;
use Valkyrja\Http\Server\Middleware\ThrowableCaught\LogThrowableCaughtMiddleware;
use Valkyrja\Http\Server\Middleware\ThrowableCaught\ViewThrowableCaughtMiddleware;
use Valkyrja\Reflection\Reflector\Contract\ReflectorContract;
use Valkyrja\Support\Directory\Directory;
use Valkyrja\Support\Time\Microtime;
use Valkyrja\Tests\EnvClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\View\Provider\ComponentProvider as ViewComponentProvider;
use Valkyrja\View\Renderer\Contract\RendererContract;
use Valkyrja\View\Template\Contract\TemplateContract;

use function defined;

use const LOCK_EX;

/**
 * Test the App service.
 */
class AppTest extends TestCase
{
    #[Override]
    protected function tearDown(): void
    {
        @unlink(EnvClass::APP_DIR . '/cache/container.php');
        @unlink(EnvClass::APP_DIR . '/cache/routes.php');
    }

    /**
     * Test the appStart method.
     */
    public function testAppStart(): void
    {
        Microtime::freeze();

        $time = Microtime::get();

        App::appStart();

        self::assertTrue(defined('APP_START'));
        self::assertSame(APP_START, $time);

        Microtime::unfreeze();
    }

    /**
     * Test the directory method.
     */
    public function testDirectory(): void
    {
        $path = __DIR__;

        App::directory($path);

        self::assertSame($path, Directory::$BASE_PATH);
    }

    /**
     * Test the app method.
     */
    public function testApp(): void
    {
        App::directory(EnvClass::APP_DIR);

        $env = new class extends EnvClass {
            /** @var non-empty-string */
            public const string APP_CACHE_FILE_PATH = '/storage/AppTestConfigTest.php';
        };

        $application = App::app($env);

        $container = $application->getContainer();

        self::assertSame($env, $container->getSingleton(Env::class));
        self::assertSame($env::APP_TIMEZONE, date_default_timezone_get());
    }

    /**
     * Test the app method.
     */
    public function testAppWithCache(): void
    {
        App::directory(EnvClass::APP_DIR);

        $env  = new class extends EnvClass {
            /** @var non-empty-string */
            public const string APP_CACHE_FILE_PATH = '/storage/AppTestDataTest.php';
        };
        $data = new Data();
        /** @var non-empty-string $filepath */
        $filepath = EnvClass::APP_DIR . $env::APP_CACHE_FILE_PATH;

        file_put_contents($filepath, serialize($data), LOCK_EX);

        $application = App::app($env);

        $container = $application->getContainer();

        self::assertTrue($container->has(ContainerData::class));
        self::assertTrue($container->has(EventData::class));
        self::assertTrue($container->has(CliData::class));
        self::assertFalse($container->has(HttpData::class));
        self::assertSame($env, $container->getSingleton(Env::class));
        self::assertSame($env::APP_TIMEZONE, date_default_timezone_get());

        @unlink($filepath);
    }

    /**
     * Test the app method.
     */
    public function testAppWithEmptyCache(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Error occurred when retrieving cache file contents');

        App::directory(EnvClass::APP_DIR);

        $env = new class extends EnvClass {
            /** @var non-empty-string */
            public const string APP_CACHE_FILE_PATH = '/storage/AppTestDataTest.php';
        };
        /** @var non-empty-string $filepath */
        $filepath = EnvClass::APP_DIR . $env::APP_CACHE_FILE_PATH;

        file_put_contents($filepath, '', LOCK_EX);

        App::app($env);
    }

    /**
     * Test the app method.
     *
     * @throws Throwable
     */
    public function testAppWithBadCache(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Invalid cache');

        App::directory(EnvClass::APP_DIR);

        $env = new class extends EnvClass {
            /** @var non-empty-string */
            public const string APP_CACHE_FILE_PATH = '/storage/AppTestDataTest.php';
        };
        /** @var non-empty-string $filepath */
        $filepath = EnvClass::APP_DIR . $env::APP_CACHE_FILE_PATH;

        file_put_contents($filepath, serialize(new stdClass()), LOCK_EX);

        try {
            App::app($env);
        } catch (Throwable $e) {
            @unlink($filepath);

            throw $e;
        }
    }

    /**
     * Ensure that default components are added when using config with empty APP_COMPONENTS.
     */
    public function testEnsureDefaultComponents(): void
    {
        $env       = new class extends Env {
            /** @var class-string<Provider>[] */
            public const array APP_COMPONENTS = [];
        };
        $application = App::app($env);

        $container = $application->getContainer();

        self::assertSame($container, $application->getContainer());
        self::assertTrue($container->has(Env::class));
        self::assertTrue($container->has(ApplicationContract::class));
        self::assertTrue($container->has(AttributeCollectorContract::class));
        self::assertTrue($container->has(CliInteractionConfig::class));
        self::assertTrue($container->has(OutputFactoryContract::class));
        self::assertTrue($container->has(InputReceivedHandlerContract::class));
        self::assertTrue($container->has(CliThrowableCaughtHandlerContract::class));
        self::assertTrue($container->has(CliRouteMatchedHandlerContract::class));
        self::assertTrue($container->has(CliRouteNotMatchedHandlerContract::class));
        self::assertTrue($container->has(CliRouteDispatchedHandlerContract::class));
        self::assertTrue($container->has(ExitedHandlerContract::class));
        self::assertTrue($container->has(CliRoutingCollector::class));
        self::assertTrue($container->has(CliRoutingRouter::class));
        self::assertTrue($container->has(CliRoutingCollection::class));
        self::assertTrue($container->has(InputHandlerContract::class));
        self::assertTrue($container->has(CliLogThrowableCaughtMiddleware::class));
        self::assertTrue($container->has(DispatcherContract::class));
        self::assertTrue($container->has(EventCollector::class));
        self::assertTrue($container->has(EventDispatcher::class));
        self::assertTrue($container->has(EventCollection::class));
        self::assertTrue($container->has(ResponseFactoryContract::class));
        self::assertTrue($container->has(RequestReceivedHandlerContract::class));
        self::assertTrue($container->has(HttpThrowableCaughtHandler::class));
        self::assertTrue($container->has(HttpRouteMatchedHandlerContract::class));
        self::assertTrue($container->has(HttpRouteNotMatchedHandlerContract::class));
        self::assertTrue($container->has(HttpRouteDispatchedHandlerContract::class));
        self::assertTrue($container->has(SendingResponseHandlerContract::class));
        self::assertTrue($container->has(TerminatedHandlerContract::class));
        self::assertTrue($container->has(CacheResponseMiddleware::class));
        self::assertTrue($container->has(HttpRoutingRouter::class));
        self::assertTrue($container->has(HttpRoutingCollection::class));
        self::assertTrue($container->has(MatcherContract::class));
        self::assertTrue($container->has(UrlContract::class));
        self::assertTrue($container->has(CollectorContract::class));
        self::assertTrue($container->has(ProcessorContract::class));
        self::assertTrue($container->has(HttpRoutingResponseFactory::class));
        self::assertTrue($container->has(RequestStructMiddleware::class));
        self::assertTrue($container->has(ResponseStructMiddleware::class));
        self::assertTrue($container->has(ViewRouteNotMatchedMiddleware::class));
        self::assertTrue($container->has(RequestHandlerContract::class));
        self::assertTrue($container->has(LogThrowableCaughtMiddleware::class));
        self::assertTrue($container->has(ViewThrowableCaughtMiddleware::class));
        self::assertTrue($container->has(ReflectorContract::class));

        $commands = $application->getCliControllers();

        self::assertContains(CacheCommand::class, $commands);
        self::assertContains(ClearCacheCommand::class, $commands);
        self::assertContains(HelpCommand::class, $commands);
        self::assertContains(ListBashCommand::class, $commands);
        self::assertContains(CliListCommand::class, $commands);
        self::assertContains(VersionCommand::class, $commands);
        self::assertContains(HttpListCommand::class, $commands);

        self::assertEmpty($application->getEventListeners());
        self::assertEmpty($application->getHttpControllers());
    }

    /**
     * Testing custom components capability.
     */
    public function testCustomComponents(): void
    {
        $env       = new class extends Env {
            /** @var class-string<Provider>[] */
            public const array APP_COMPONENTS = [];
            /** @var class-string<Provider>[] */
            public const array APP_CUSTOM_COMPONENTS = [];
        };
        $application = App::app($env);

        $container = $application->getContainer();

        self::assertSame($container, $application->getContainer());
        self::assertFalse($container->has(TemplateContract::class));

        $env2       = new class extends Env {
            /** @var class-string<Provider>[] */
            public const array APP_COMPONENTS = [];
            /** @var class-string<Provider>[] */
            public const array APP_CUSTOM_COMPONENTS = [
                ViewComponentProvider::class,
            ];
        };
        $application2 = App::app($env2);

        $container2 = $application2->getContainer();

        self::assertSame($container2, $application2->getContainer());
        self::assertTrue($container2->has(RendererContract::class));
    }
}
