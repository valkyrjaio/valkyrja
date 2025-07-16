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

namespace Valkyrja\Tests\Unit\Application;

use GuzzleHttp\Client as Guzzle;
use Valkyrja\Application\Cli\Command\CacheCommand;
use Valkyrja\Application\Cli\Command\ClearCacheCommand;
use Valkyrja\Application\Component;
use Valkyrja\Application\Config;
use Valkyrja\Application\Contract\Application;
use Valkyrja\Application\Data;
use Valkyrja\Application\Env;
use Valkyrja\Application\Exception\RuntimeException;
use Valkyrja\Application\Valkyrja;
use Valkyrja\Attribute\Contract\Attributes;
use Valkyrja\Cli\Interaction\Config as CliInteractionConfig;
use Valkyrja\Cli\Interaction\Factory\Contract\OutputFactory;
use Valkyrja\Cli\Middleware\Handler\Contract\CommandDispatchedHandler;
use Valkyrja\Cli\Middleware\Handler\Contract\CommandMatchedHandler;
use Valkyrja\Cli\Middleware\Handler\Contract\CommandNotMatchedHandler;
use Valkyrja\Cli\Middleware\Handler\Contract\ExitedHandler;
use Valkyrja\Cli\Middleware\Handler\Contract\InputReceivedHandler;
use Valkyrja\Cli\Middleware\Handler\Contract\ThrowableCaughtHandler;
use Valkyrja\Cli\Routing\Collection\Contract\Collection as CliRoutingCollection;
use Valkyrja\Cli\Routing\Collector\Contract\Collector as CliRoutingCollector;
use Valkyrja\Cli\Routing\Command\HelpCommand;
use Valkyrja\Cli\Routing\Command\ListBashCommand;
use Valkyrja\Cli\Routing\Command\ListCommand as CliListCommand;
use Valkyrja\Cli\Routing\Command\VersionCommand;
use Valkyrja\Cli\Routing\Contract\Router as CliRoutingRouter;
use Valkyrja\Cli\Routing\Data as CliData;
use Valkyrja\Cli\Server\Contract\InputHandler as InputHandlerContract;
use Valkyrja\Cli\Server\Middleware\LogThrowableCaughtMiddleware as CliLogThrowableCaughtMiddleware;
use Valkyrja\Container\Collector\Contract\Collector as ContainerCollector;
use Valkyrja\Container\Data as ContainerData;
use Valkyrja\Dispatcher\Contract\Dispatcher;
use Valkyrja\Event\Collection\Contract\Collection as EventCollection;
use Valkyrja\Event\Collector\Contract\Collector as EventCollector;
use Valkyrja\Event\Contract\Dispatcher as EventDispatcher;
use Valkyrja\Event\Data as EventData;
use Valkyrja\Http\Client\Contract\Client;
use Valkyrja\Http\Client\GuzzleClient;
use Valkyrja\Http\Client\LogClient;
use Valkyrja\Http\Client\NullClient;
use Valkyrja\Http\Message\Factory\Contract\ResponseFactory;
use Valkyrja\Http\Middleware\Cache\CacheResponseMiddleware;
use Valkyrja\Http\Middleware\Handler\Contract\RequestReceivedHandler;
use Valkyrja\Http\Middleware\Handler\Contract\RouteDispatchedHandler;
use Valkyrja\Http\Middleware\Handler\Contract\RouteMatchedHandler;
use Valkyrja\Http\Middleware\Handler\Contract\RouteNotMatchedHandler;
use Valkyrja\Http\Middleware\Handler\Contract\SendingResponseHandler;
use Valkyrja\Http\Middleware\Handler\Contract\TerminatedHandler;
use Valkyrja\Http\Middleware\Handler\Contract\ThrowableCaughtHandler as HttpThrowableCaughtHandler;
use Valkyrja\Http\Routing\Cli\Command\ListCommand as HttpListCommand;
use Valkyrja\Http\Routing\Collection\Contract\Collection as HttpRoutingCollection;
use Valkyrja\Http\Routing\Collector\Contract\Collector;
use Valkyrja\Http\Routing\Contract\Router as HttpRoutingRouter;
use Valkyrja\Http\Routing\Data as HttpData;
use Valkyrja\Http\Routing\Factory\Contract\ResponseFactory as HttpRoutingResponseFactory;
use Valkyrja\Http\Routing\Matcher\Contract\Matcher;
use Valkyrja\Http\Routing\Middleware\RequestStructMiddleware;
use Valkyrja\Http\Routing\Middleware\ResponseStructMiddleware;
use Valkyrja\Http\Routing\Middleware\ViewRouteNotMatchedMiddleware;
use Valkyrja\Http\Routing\Processor\Contract\Processor;
use Valkyrja\Http\Routing\Url\Contract\Url;
use Valkyrja\Http\Server\Contract\RequestHandler;
use Valkyrja\Http\Server\Middleware\LogThrowableCaughtMiddleware;
use Valkyrja\Http\Server\Middleware\ViewThrowableCaughtMiddleware;
use Valkyrja\Reflection\Contract\Reflection;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the Application service.
 *
 * @author Melech Mizrachi
 */
class ApplicationTest extends TestCase
{
    /**
     * Test the application with a config object.
     */
    public function testApplicationWithConfig(): void
    {
        $env    = new Env();
        $config = new Config();

        $application = new Valkyrja(
            env: $env,
            configData: $config
        );

        $container = $application->getContainer();

        self::assertTrue($container->has(Config::class));
        self::assertSame($env, $application->getEnv());
        self::assertSame($env, $container->getSingleton(Env::class));
        self::assertSame($config, $container->getSingleton(Config::class));
        self::assertSame($env::APP_TIMEZONE, date_default_timezone_get());

        $env2    = new Env();
        $config2 = new Config();

        $application->setup(
            env: $env2,
            configData: $config2
        );

        $container2 = $application->getContainer();

        // Ensure setup isn't run twice
        self::assertSame($container, $container2);
        self::assertTrue($container2->has(Config::class));
        self::assertSame($env, $application->getEnv());
        self::assertSame($env, $container2->getSingleton(Env::class));
        self::assertSame($config, $container2->getSingleton(Config::class));

        $env3    = new Env();
        $config3 = new Config();

        $application->setup(
            env: $env3,
            configData: $config3,
            force: true,
        );

        $container3 = $application->getContainer();

        // Ensure setup is run, and config and env are overriden when setup is forced
        self::assertNotSame($container, $container3);
        self::assertTrue($container3->has(Config::class));
        self::assertSame($env3, $application->getEnv());
        self::assertSame($env3, $container3->getSingleton(Env::class));
        self::assertSame($config3, $container3->getSingleton(Config::class));
    }

    /**
     * Test the application with a data object.
     */
    public function testApplicationWithData(): void
    {
        $env  = new Env();
        $data = new Data();

        $application = new Valkyrja(
            env: $env,
            configData: $data
        );

        $container = $application->getContainer();

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

        $env2  = new Env();
        $data2 = new Data();

        $application->setup(
            env: $env2,
            configData: $data2
        );

        $container2 = $application->getContainer();

        // Ensure setup isn't run twice
        self::assertSame($container, $container2);
        self::assertNotTrue($container2->has(Config::class));
        self::assertTrue($container2->has(ContainerData::class));
        self::assertTrue($container2->has(EventData::class));
        self::assertTrue($container2->has(CliData::class));
        self::assertTrue($container2->has(HttpData::class));
        self::assertSame($env, $application->getEnv());
        self::assertSame($env, $container2->getSingleton(Env::class));
        self::assertSame($data->container, $container2->getSingleton(ContainerData::class));
        self::assertSame($data->event, $container2->getSingleton(EventData::class));
        self::assertSame($data->cli, $container2->getSingleton(CliData::class));
        self::assertSame($data->http, $container2->getSingleton(HttpData::class));

        $env3  = new Env();
        $data3 = new Data();

        $application->setup(
            env: $env3,
            configData: $data3,
            force: true,
        );

        $container3 = $application->getContainer();

        // Ensure setup is run, and data and env are overriden when setup is forced
        self::assertNotSame($container, $container3);
        self::assertNotTrue($container3->has(Config::class));
        self::assertTrue($container3->has(ContainerData::class));
        self::assertTrue($container3->has(EventData::class));
        self::assertTrue($container3->has(CliData::class));
        self::assertTrue($container3->has(HttpData::class));
        self::assertSame($env3, $application->getEnv());
        self::assertSame($env3, $container3->getSingleton(Env::class));
        self::assertSame($data3->container, $container3->getSingleton(ContainerData::class));
        self::assertSame($data3->event, $container3->getSingleton(EventData::class));
        self::assertSame($data3->cli, $container3->getSingleton(CliData::class));
        self::assertSame($data3->http, $container3->getSingleton(HttpData::class));
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
            env: $env,
            configData: $data
        );

        $application->addComponent(Component::class);
    }

    /**
     * Ensure that default components are added when using config with empty APP_COMPONENTS.
     */
    public function testEnsureDefaultComponents(): void
    {
        $env    = new class extends Env {
            /** @var class-string<\Valkyrja\Application\Support\Component>[] */
            public const array APP_COMPONENTS = [];
        };
        $config = new Config();

        $application = new Valkyrja(
            env: $env,
            configData: $config
        );

        $container = $application->getContainer();

        self::assertTrue($container->has(Env::class));
        self::assertTrue($container->has(Config::class));
        self::assertTrue($container->has(Application::class));
        self::assertTrue($container->has(ContainerData::class));
        self::assertTrue($container->has(ContainerCollector::class));
        self::assertTrue($container->has(Attributes::class));
        self::assertTrue($container->has(CliInteractionConfig::class));
        self::assertTrue($container->has(OutputFactory::class));
        self::assertTrue($container->has(InputReceivedHandler::class));
        self::assertTrue($container->has(ThrowableCaughtHandler::class));
        self::assertTrue($container->has(CommandMatchedHandler::class));
        self::assertTrue($container->has(CommandNotMatchedHandler::class));
        self::assertTrue($container->has(CommandDispatchedHandler::class));
        self::assertTrue($container->has(ExitedHandler::class));
        self::assertTrue($container->has(CliRoutingCollector::class));
        self::assertTrue($container->has(CliRoutingRouter::class));
        self::assertTrue($container->has(CliRoutingCollection::class));
        self::assertTrue($container->has(InputHandlerContract::class));
        self::assertTrue($container->has(CliLogThrowableCaughtMiddleware::class));
        self::assertTrue($container->has(Dispatcher::class));
        self::assertTrue($container->has(EventCollector::class));
        self::assertTrue($container->has(EventDispatcher::class));
        self::assertTrue($container->has(EventCollection::class));
        self::assertTrue($container->has(Client::class));
        self::assertTrue($container->has(GuzzleClient::class));
        self::assertTrue($container->has(Guzzle::class));
        self::assertTrue($container->has(LogClient::class));
        self::assertTrue($container->has(NullClient::class));
        self::assertTrue($container->has(ResponseFactory::class));
        self::assertTrue($container->has(RequestReceivedHandler::class));
        self::assertTrue($container->has(HttpThrowableCaughtHandler::class));
        self::assertTrue($container->has(RouteMatchedHandler::class));
        self::assertTrue($container->has(RouteNotMatchedHandler::class));
        self::assertTrue($container->has(RouteDispatchedHandler::class));
        self::assertTrue($container->has(SendingResponseHandler::class));
        self::assertTrue($container->has(TerminatedHandler::class));
        self::assertTrue($container->has(CacheResponseMiddleware::class));
        self::assertTrue($container->has(HttpRoutingRouter::class));
        self::assertTrue($container->has(HttpRoutingCollection::class));
        self::assertTrue($container->has(Matcher::class));
        self::assertTrue($container->has(Url::class));
        self::assertTrue($container->has(Collector::class));
        self::assertTrue($container->has(Processor::class));
        self::assertTrue($container->has(HttpRoutingResponseFactory::class));
        self::assertTrue($container->has(RequestStructMiddleware::class));
        self::assertTrue($container->has(ResponseStructMiddleware::class));
        self::assertTrue($container->has(ViewRouteNotMatchedMiddleware::class));
        self::assertTrue($container->has(RequestHandler::class));
        self::assertTrue($container->has(LogThrowableCaughtMiddleware::class));
        self::assertTrue($container->has(ViewThrowableCaughtMiddleware::class));
        self::assertTrue($container->has(Reflection::class));

        self::assertContains(CacheCommand::class, $config->commands);
        self::assertContains(ClearCacheCommand::class, $config->commands);
        self::assertContains(HelpCommand::class, $config->commands);
        self::assertContains(ListBashCommand::class, $config->commands);
        self::assertContains(CliListCommand::class, $config->commands);
        self::assertContains(VersionCommand::class, $config->commands);
        self::assertContains(HttpListCommand::class, $config->commands);
    }
}
