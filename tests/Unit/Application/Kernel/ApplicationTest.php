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

namespace Valkyrja\Tests\Unit\Application\Kernel;

use Valkyrja\Application\Cli\Command\CacheCommand;
use Valkyrja\Application\Cli\Command\ClearCacheCommand;
use Valkyrja\Application\Data\Config;
use Valkyrja\Application\Data\Data;
use Valkyrja\Application\Env\Env;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Application\Kernel\Valkyrja;
use Valkyrja\Application\Provider\ComponentProvider;
use Valkyrja\Application\Provider\Provider;
use Valkyrja\Application\Throwable\Exception\RuntimeException;
use Valkyrja\Attribute\Collector\Contract\CollectorContract as AttributeCollectorContract;
use Valkyrja\Cli\Interaction\Data\Config as CliInteractionConfig;
use Valkyrja\Cli\Interaction\Factory\Contract\OutputFactoryContract;
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
use Valkyrja\Container\Collector\Contract\CollectorContract as ContainerCollector;
use Valkyrja\Container\Data\Data as ContainerData;
use Valkyrja\Container\Manager\Container;
use Valkyrja\Dispatch\Dispatcher\Contract\DispatcherContract;
use Valkyrja\Event\Collection\Contract\CollectionContract as EventCollection;
use Valkyrja\Event\Collector\Contract\CollectorContract as EventCollector;
use Valkyrja\Event\Data\Data as EventData;
use Valkyrja\Event\Dispatcher\Contract\DispatcherContract as EventDispatcher;
use Valkyrja\Http\Message\Factory\Contract\ResponseFactoryContract;
use Valkyrja\Http\Middleware\Cache\CacheResponseMiddleware;
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
use Valkyrja\Http\Routing\Middleware\RequestStructMiddleware;
use Valkyrja\Http\Routing\Middleware\ResponseStructMiddleware;
use Valkyrja\Http\Routing\Middleware\ViewRouteNotMatchedMiddleware;
use Valkyrja\Http\Routing\Processor\Contract\ProcessorContract;
use Valkyrja\Http\Routing\Url\Contract\UrlContract;
use Valkyrja\Http\Server\Handler\Contract\RequestHandlerContract;
use Valkyrja\Http\Server\Middleware\LogThrowableCaughtMiddleware;
use Valkyrja\Http\Server\Middleware\ViewThrowableCaughtMiddleware;
use Valkyrja\Reflection\Reflector\Contract\ReflectorContract;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\View\Provider\ComponentProvider as ViewComponentProvider;
use Valkyrja\View\Renderer\Contract\RendererContract;
use Valkyrja\View\Template\Contract\TemplateContract;

/**
 * Test the Application service.
 */
class ApplicationTest extends TestCase
{
    /**
     * Test the application with a config object.
     */
    public function testApplicationWithConfig(): void
    {
        $env       = new Env();
        $config    = new Config();
        $container = new Container();

        $application = new Valkyrja(
            container: $container,
            env: $env,
            configData: $config
        );

        self::assertSame($container, $application->getContainer());
        self::assertTrue($container->has(Config::class));
        self::assertSame($env, $application->getEnv());
        self::assertSame($env, $container->getSingleton(Env::class));
        self::assertSame($config, $container->getSingleton(Config::class));
        self::assertSame($env::APP_TIMEZONE, date_default_timezone_get());
    }

    /**
     * Test the application with a data object.
     */
    public function testApplicationWithData(): void
    {
        $env       = new Env();
        $data      = new Data();
        $container = new Container();

        $application = new Valkyrja(
            container: $container,
            env: $env,
            configData: $data
        );

        self::assertSame($container, $application->getContainer());
        self::assertNotTrue($container->has(Config::class));
        self::assertTrue($container->has(ContainerData::class));
        self::assertTrue($container->has(EventData::class));
        self::assertTrue($container->has(CliData::class));
        self::assertTrue($container->has(HttpData::class));
        self::assertSame($env, $application->getEnv());
        self::assertSame($env, $container->getSingleton(Env::class));
        self::assertSame($data->container, $container->getSingleton(ContainerData::class));
        self::assertSame($data->event, $container->getSingleton(EventData::class));
        self::assertSame($data->cli, $container->getSingleton(CliData::class));
        self::assertSame($data->http, $container->getSingleton(HttpData::class));
        self::assertSame($env::APP_TIMEZONE, date_default_timezone_get());
    }

    /**
     * Test to ensure that components cannot be added when using data to setup application.
     */
    public function testAddComponentWhenSetupWithData(): void
    {
        $this->expectException(RuntimeException::class);

        $env  = new Env();
        $data = new Data();

        $application = new Valkyrja(
            container: new Container(),
            env: $env,
            configData: $data
        );

        $application->addComponent(ComponentProvider::class);
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
        $config    = new Config();
        $container = new Container();

        $application = new Valkyrja(
            container: $container,
            env: $env,
            configData: $config
        );

        self::assertSame($container, $application->getContainer());
        self::assertTrue($container->has(Env::class));
        self::assertTrue($container->has(Config::class));
        self::assertTrue($container->has(ApplicationContract::class));
        self::assertTrue($container->has(ContainerData::class));
        self::assertTrue($container->has(ContainerCollector::class));
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

        self::assertContains(CacheCommand::class, $config->commands);
        self::assertContains(ClearCacheCommand::class, $config->commands);
        self::assertContains(HelpCommand::class, $config->commands);
        self::assertContains(ListBashCommand::class, $config->commands);
        self::assertContains(CliListCommand::class, $config->commands);
        self::assertContains(VersionCommand::class, $config->commands);
        self::assertContains(HttpListCommand::class, $config->commands);
    }

    /**
     * Ensure that event listeners, Cli/Http controllers do not get added to config when env settings are false.
     */
    public function testFalseEventListenersAndHttpCliComponents(): void
    {
        $env    = new class extends Env {
            /** @var bool */
            public const bool APP_ADD_CLI_CONTROLLERS = false;
            /** @var bool */
            public const bool APP_ADD_HTTP_CONTROLLERS = false;
            /** @var bool */
            public const bool APP_ADD_EVENT_LISTENERS = false;
        };
        $config = new Config();

        new Valkyrja(
            container: new Container(),
            env: $env,
            configData: $config
        );

        self::assertEmpty($config->listeners);
        self::assertEmpty($config->commands);
        self::assertEmpty($config->controllers);
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
        $config    = new Config();
        $container = new Container();

        $application = new Valkyrja(
            container: $container,
            env: $env,
            configData: $config
        );

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
        $config2    = new Config();
        $container2 = new Container();

        $application2 = new Valkyrja(
            container: $container2,
            env: $env2,
            configData: $config2
        );

        self::assertSame($container2, $application2->getContainer());
        self::assertTrue($container2->has(RendererContract::class));
    }
}
