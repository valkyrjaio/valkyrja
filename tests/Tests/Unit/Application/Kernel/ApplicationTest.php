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

use ReflectionClass;
use Valkyrja\Application\Data\Config;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Application\Kernel\Valkyrja;
use Valkyrja\Application\Provider\ApplicationComponentProvider;
use Valkyrja\Application\Provider\CliApplicationComponentProvider;
use Valkyrja\Application\Provider\CliWithHttpApplicationComponentProvider;
use Valkyrja\Cli\Interaction\Provider\CliInteractionComponentProvider;
use Valkyrja\Cli\Middleware\Provider\CliMiddlewareComponentProvider;
use Valkyrja\Cli\Routing\Provider\CliRoutingComponentProvider;
use Valkyrja\Cli\Server\Provider\CliServerComponentProvider;
use Valkyrja\Container\Manager\Container;
use Valkyrja\Container\Provider\ContainerComponentProvider;
use Valkyrja\Event\Provider\EventComponentProvider;
use Valkyrja\Http\Message\Provider\HttpMessageComponentProvider;
use Valkyrja\Http\Middleware\Provider\HttpMiddlewareComponentProvider;
use Valkyrja\Http\Routing\Provider\HttpRoutingCliComponentProvider;
use Valkyrja\Http\Routing\Provider\HttpRoutingComponentProvider;
use Valkyrja\Http\Server\Provider\HttpServerComponentProvider;
use Valkyrja\Log\Provider\LogComponentProvider;
use Valkyrja\Tests\Classes\Application\Provider\CliComponentProviderClass;
use Valkyrja\Tests\Classes\Application\Provider\CliContainerDataProviderClass;
use Valkyrja\Tests\Classes\Application\Provider\CliRouteComponentProviderClass;
use Valkyrja\Tests\Classes\Application\Provider\CliRouteProviderClass;
use Valkyrja\Tests\Classes\Application\Provider\CliRoutingDataProviderClass;
use Valkyrja\Tests\Classes\Application\Provider\ComponentProviderClass;
use Valkyrja\Tests\Classes\Application\Provider\EventComponentProviderClass;
use Valkyrja\Tests\Classes\Application\Provider\HttpComponentProviderClass;
use Valkyrja\Tests\Classes\Application\Provider\HttpContainerDataProviderClass;
use Valkyrja\Tests\Classes\Application\Provider\HttpRouteComponentProviderClass;
use Valkyrja\Tests\Classes\Application\Provider\HttpRouteProviderClass;
use Valkyrja\Tests\Classes\Application\Provider\HttpRoutingDataProviderClass;
use Valkyrja\Tests\Classes\Event\Provider\ListenerProviderClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the Application service.
 */
final class ApplicationTest extends TestCase
{
    /**
     * Test the application with defaults.
     */
    public function testDefaults(): void
    {
        $config    = new Config();
        $container = new Container();

        $application = new Valkyrja(
            container: $container,
            config: $config,
        );

        self::assertSame($container, $application->getContainer());
        self::assertSame($config->environment, $application->getEnvironment());
        self::assertSame($config->debugMode, $application->getDebugMode());
        self::assertSame($config->version, $application->getVersion());
        self::assertSame($config->timezone, date_default_timezone_get());

        $providers = $application->getProviders();
        self::assertCount(3, $providers);
        self::assertInstanceOf(ContainerComponentProvider::class, $providers[0]);
        self::assertInstanceOf(EventComponentProvider::class, $providers[1]);
        self::assertInstanceOf(ApplicationComponentProvider::class, $providers[2]);
    }

    /**
     * Test the application with defaults.
     */
    public function testWithCustomComponent(): void
    {
        $config    = new Config(
            providers: [
                new CliWithHttpApplicationComponentProvider(),
                new CliComponentProviderClass(),
            ],
        );
        $container = new Container();

        $application = new Valkyrja(
            container: $container,
            config: $config,
        );

        self::assertSame($container, $application->getContainer());
        self::assertSame($config->environment, $application->getEnvironment());
        self::assertSame($config->debugMode, $application->getDebugMode());
        self::assertSame($config->version, $application->getVersion());
        self::assertSame($config->timezone, date_default_timezone_get());

        $providers = $application->getProviders();
        self::assertCount(16, $providers);
        self::assertInstanceOf(ContainerComponentProvider::class, $providers[0]);
        self::assertInstanceOf(EventComponentProvider::class, $providers[1]);
        self::assertInstanceOf(ApplicationComponentProvider::class, $providers[2]);
        self::assertInstanceOf(CliInteractionComponentProvider::class, $providers[3]);
        self::assertInstanceOf(CliMiddlewareComponentProvider::class, $providers[4]);
        self::assertInstanceOf(CliRoutingComponentProvider::class, $providers[5]);
        self::assertInstanceOf(CliServerComponentProvider::class, $providers[6]);
        self::assertInstanceOf(LogComponentProvider::class, $providers[7]);
        self::assertInstanceOf(CliApplicationComponentProvider::class, $providers[8]);
        self::assertInstanceOf(HttpMessageComponentProvider::class, $providers[9]);
        self::assertInstanceOf(HttpMiddlewareComponentProvider::class, $providers[10]);
        self::assertInstanceOf(HttpRoutingComponentProvider::class, $providers[11]);
        self::assertInstanceOf(HttpRoutingCliComponentProvider::class, $providers[12]);
        self::assertInstanceOf(HttpServerComponentProvider::class, $providers[13]);
        self::assertInstanceOf(CliWithHttpApplicationComponentProvider::class, $providers[14]);
        self::assertInstanceOf(CliComponentProviderClass::class, $providers[15]);
    }

    /**
     * Test that getProviders expands nested component providers from getComponentProviders.
     */
    public function testGetProvidersExpandsComponentProviders(): void
    {
        $config      = new Config(providers: [new ComponentProviderClass()]);
        $application = new Valkyrja(container: new Container(), config: $config);

        $providers = $application->getProviders();
        self::assertCount(3, $providers);
        self::assertInstanceOf(CliComponentProviderClass::class, $providers[0]);
        self::assertInstanceOf(HttpComponentProviderClass::class, $providers[1]);
        self::assertInstanceOf(ComponentProviderClass::class, $providers[2]);
    }

    /**
     * Test that getProviders populates the internal cache and subsequent calls use it.
     */
    public function testGetProvidersIsCached(): void
    {
        $config      = new Config(providers: [new ComponentProviderClass()]);
        $application = new Valkyrja(container: new Container(), config: $config);
        $reflection  = new ReflectionClass($application);
        $property    = $reflection->getProperty('providers');

        self::assertSame([], $property->getValue($application));

        $result = $application->getProviders();

        self::assertCount(3, $result);
        self::assertInstanceOf(CliComponentProviderClass::class, $result[0]);
        self::assertInstanceOf(HttpComponentProviderClass::class, $result[1]);
        self::assertInstanceOf(ComponentProviderClass::class, $result[2]);
        self::assertSame($result, $property->getValue($application));
        self::assertSame($result, $application->getProviders());
    }

    /**
     * Test that getContainerProviders collects results from all expanded providers.
     */
    public function testGetContainerProviders(): void
    {
        $config      = new Config(providers: [new ComponentProviderClass()]);
        $application = new Valkyrja(container: new Container(), config: $config);

        $result = $application->getContainerProviders();

        self::assertCount(4, $result);
        self::assertInstanceOf(CliContainerDataProviderClass::class, $result[0]);
        self::assertInstanceOf(CliRoutingDataProviderClass::class, $result[1]);
        self::assertInstanceOf(HttpContainerDataProviderClass::class, $result[2]);
        self::assertInstanceOf(HttpRoutingDataProviderClass::class, $result[3]);
    }

    /**
     * Test that getContainerProviders populates the internal cache and subsequent calls use it.
     */
    public function testGetContainerProvidersIsCached(): void
    {
        $config      = new Config(providers: [new ComponentProviderClass()]);
        $application = new Valkyrja(container: new Container(), config: $config);
        $reflection  = new ReflectionClass($application);
        $property    = $reflection->getProperty('serviceProviders');

        self::assertSame([], $property->getValue($application));

        $result = $application->getContainerProviders();

        self::assertCount(4, $result);
        self::assertInstanceOf(CliContainerDataProviderClass::class, $result[0]);
        self::assertInstanceOf(CliRoutingDataProviderClass::class, $result[1]);
        self::assertInstanceOf(HttpContainerDataProviderClass::class, $result[2]);
        self::assertInstanceOf(HttpRoutingDataProviderClass::class, $result[3]);
        self::assertSame($result, $property->getValue($application));
        self::assertSame($result, $application->getContainerProviders());
    }

    /**
     * Test that getEventProviders collects results from all expanded providers.
     */
    public function testGetEventProviders(): void
    {
        $config      = new Config(providers: [new EventComponentProviderClass()]);
        $application = new Valkyrja(container: new Container(), config: $config);

        self::assertSame(
            [ListenerProviderClass::class],
            $application->getEventProviders(),
        );
    }

    /**
     * Test that getEventProviders populates the internal cache and subsequent calls use it.
     */
    public function testGetEventProvidersIsCached(): void
    {
        $config      = new Config(providers: [new EventComponentProviderClass()]);
        $application = new Valkyrja(container: new Container(), config: $config);
        $reflection  = new ReflectionClass($application);
        $property    = $reflection->getProperty('eventProviders');

        self::assertSame([], $property->getValue($application));

        $result = $application->getEventProviders();

        self::assertSame([ListenerProviderClass::class], $result);
        self::assertSame($result, $property->getValue($application));
        self::assertSame($result, $application->getEventProviders());
    }

    /**
     * Test that getCliProviders collects results from all expanded providers.
     */
    public function testGetCliProviders(): void
    {
        $config      = new Config(providers: [new CliRouteComponentProviderClass()]);
        $application = new Valkyrja(container: new Container(), config: $config);

        self::assertSame(
            [CliRouteProviderClass::class],
            $application->getCliProviders(),
        );
    }

    /**
     * Test that getCliProviders populates the internal cache and subsequent calls use it.
     */
    public function testGetCliProvidersIsCached(): void
    {
        $config      = new Config(providers: [new CliRouteComponentProviderClass()]);
        $application = new Valkyrja(container: new Container(), config: $config);
        $reflection  = new ReflectionClass($application);
        $property    = $reflection->getProperty('cliRouteProviders');

        self::assertSame([], $property->getValue($application));

        $result = $application->getCliProviders();

        self::assertSame([CliRouteProviderClass::class], $result);
        self::assertSame($result, $property->getValue($application));
        self::assertSame($result, $application->getCliProviders());
    }

    /**
     * Test that getHttpProviders collects results from all expanded providers.
     */
    public function testGetHttpProviders(): void
    {
        $config      = new Config(providers: [new HttpRouteComponentProviderClass()]);
        $application = new Valkyrja(container: new Container(), config: $config);

        self::assertSame(
            [HttpRouteProviderClass::class],
            $application->getHttpProviders(),
        );
    }

    /**
     * Test that getHttpProviders populates the internal cache and subsequent calls use it.
     */
    public function testGetHttpProvidersIsCached(): void
    {
        $config      = new Config(providers: [new HttpRouteComponentProviderClass()]);
        $application = new Valkyrja(container: new Container(), config: $config);
        $reflection  = new ReflectionClass($application);
        $property    = $reflection->getProperty('httpRouteProviders');

        self::assertSame([], $property->getValue($application));

        $result = $application->getHttpProviders();

        self::assertSame([HttpRouteProviderClass::class], $result);
        self::assertSame($result, $property->getValue($application));
        self::assertSame($result, $application->getHttpProviders());
    }

    /**
     * Test that publishProviderCallbacks invokes each callback with the application.
     */
    public function testPublishProviderCallbacks(): void
    {
        $received = [];

        $config = new Config(
            providers: [],
            callbacks: [
                static function (ApplicationContract $app) use (&$received): void {
                    $received[] = $app;
                },
                static function (ApplicationContract $app) use (&$received): void {
                    $received[] = $app;
                },
            ],
        );

        $application = new Valkyrja(container: new Container(), config: $config);
        $application->publishProviderCallbacks();

        self::assertCount(2, $received);
        self::assertSame($application, $received[0]);
        self::assertSame($application, $received[1]);
    }

    /**
     * Test that publishProviderCallbacks with no callbacks does nothing.
     */
    public function testPublishProviderCallbacksWithNoCallbacks(): void
    {
        $config      = new Config(providers: [], callbacks: []);
        $application = new Valkyrja(container: new Container(), config: $config);

        // No exception should be thrown
        $application->publishProviderCallbacks();

        self::assertTrue(true);
    }
}
