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
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use Valkyrja\Application\Entry\Abstract\App;
use Valkyrja\Application\Env\Env;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Application\Provider\Provider;
use Valkyrja\Attribute\Collector\Contract\CollectorContract as AttributeCollectorContract;
use Valkyrja\Cli\Interaction\Data\Config;
use Valkyrja\Cli\Interaction\Output\Factory\Contract\OutputFactoryContract;
use Valkyrja\Cli\Middleware\Handler\Contract\ExitedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\InputReceivedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\RouteDispatchedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\RouteMatchedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\RouteNotMatchedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\ThrowableCaughtHandlerContract;
use Valkyrja\Cli\Routing\Collection\Contract\CollectionContract;
use Valkyrja\Cli\Routing\Collector\Contract\CollectorContract as CliRoutingCollector;
use Valkyrja\Cli\Routing\Data\Data;
use Valkyrja\Cli\Routing\Dispatcher\Contract\RouterContract;
use Valkyrja\Cli\Server\Command\HelpCommand;
use Valkyrja\Cli\Server\Command\ListBashCommand;
use Valkyrja\Cli\Server\Command\ListCommand;
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
use Valkyrja\View\Provider\ComponentProvider;
use Valkyrja\View\Renderer\Contract\RendererContract;
use Valkyrja\View\Template\Contract\TemplateContract;

use function defined;

/**
 * Test the App service.
 */
final class AppTest extends TestCase
{
    #[Override]
    protected function tearDown(): void
    {
        @unlink(EnvClass::APP_DIR . '/data/container.php');
        @unlink(EnvClass::APP_DIR . '/data/routes.php');
    }

    /**
     * Test the appStart method.
     */
    #[RunInSeparateProcess]
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

        self::assertSame($path, Directory::$basePath);
    }

    /**
     * Test the app method.
     */
    public function testApp(): void
    {
        App::directory(EnvClass::APP_DIR);

        $env = new class extends EnvClass {
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
        };

        $application = App::app($env);

        $container = $application->getContainer();

        self::assertTrue($container->has(ContainerData::class));
        self::assertFalse($container->has(EventData::class));
        self::assertFalse($container->has(Data::class));
        self::assertFalse($container->has(HttpData::class));
        self::assertSame($env, $container->getSingleton(Env::class));
        self::assertSame($env::APP_TIMEZONE, date_default_timezone_get());
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
        self::assertTrue($container->has(Config::class));
        self::assertTrue($container->has(OutputFactoryContract::class));
        self::assertTrue($container->has(InputReceivedHandlerContract::class));
        self::assertTrue($container->has(ThrowableCaughtHandlerContract::class));
        self::assertTrue($container->has(RouteMatchedHandlerContract::class));
        self::assertTrue($container->has(RouteNotMatchedHandlerContract::class));
        self::assertTrue($container->has(RouteDispatchedHandlerContract::class));
        self::assertTrue($container->has(ExitedHandlerContract::class));
        self::assertTrue($container->has(CliRoutingCollector::class));
        self::assertTrue($container->has(RouterContract::class));
        self::assertTrue($container->has(CollectionContract::class));
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

        self::assertContains(HelpCommand::class, $commands);
        self::assertContains(ListBashCommand::class, $commands);
        self::assertContains(ListCommand::class, $commands);
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
                ComponentProvider::class,
            ];
        };
        $application2 = App::app($env2);

        $container2 = $application2->getContainer();

        self::assertSame($container2, $application2->getContainer());
        self::assertTrue($container2->has(RendererContract::class));
    }
}
