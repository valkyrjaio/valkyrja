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
use Valkyrja\Application\Provider\CliWithHttpApplicationComponentProvider;
use Valkyrja\Cli\Interaction\Provider\CliInteractionComponentProvider;
use Valkyrja\Cli\Middleware\Provider\CliMiddlewareComponentProvider;
use Valkyrja\Cli\Routing\Provider\CliRoutingComponentProvider;
use Valkyrja\Cli\Server\Provider\CliServerComponentProvider;
use Valkyrja\Container\Manager\Container;
use Valkyrja\Container\Provider\ContainerComponentProvider;
use Valkyrja\Dispatch\Provider\DispatchComponentProvider;
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
use Valkyrja\Tests\Classes\Application\Provider\DuplicateSubProviderClass;
use Valkyrja\Tests\Classes\Application\Provider\HttpRoutingDataProviderClass;
use Valkyrja\Tests\Classes\Event\Provider\ListenerProviderClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\View\Provider\ViewComponentProvider;

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

        self::assertSame(
            [
                ContainerComponentProvider::class,
                DispatchComponentProvider::class,
                CliInteractionComponentProvider::class,
                CliMiddlewareComponentProvider::class,
                CliRoutingComponentProvider::class,
                CliServerComponentProvider::class,
                EventComponentProvider::class,
                HttpMessageComponentProvider::class,
                HttpMiddlewareComponentProvider::class,
                HttpRoutingComponentProvider::class,
                HttpRoutingCliComponentProvider::class,
                HttpServerComponentProvider::class,
                LogComponentProvider::class,
                ViewComponentProvider::class,
                ApplicationComponentProvider::class,
            ],
            $application->getProviders(),
        );
    }

    /**
     * Test the application with defaults.
     */
    public function testWithCustomComponent(): void
    {
        $config    = new Config(
            providers: [
                CliWithHttpApplicationComponentProvider::class,
                CliComponentProviderClass::class,
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

        self::assertSame(
            [
                ContainerComponentProvider::class,
                DispatchComponentProvider::class,
                CliInteractionComponentProvider::class,
                CliMiddlewareComponentProvider::class,
                CliRoutingComponentProvider::class,
                CliServerComponentProvider::class,
                EventComponentProvider::class,
                HttpMessageComponentProvider::class,
                HttpMiddlewareComponentProvider::class,
                HttpRoutingComponentProvider::class,
                HttpRoutingCliComponentProvider::class,
                HttpServerComponentProvider::class,
                LogComponentProvider::class,
                CliWithHttpApplicationComponentProvider::class,
                CliComponentProviderClass::class,
            ],
            $application->getProviders(),
        );
    }

    /**
     * Test that getProviders expands nested component providers from getComponentProviders.
     */
    public function testGetProvidersExpandsComponentProviders(): void
    {
        $config      = new Config(providers: [ComponentProviderClass::class]);
        $application = new Valkyrja(container: new Container(), config: $config);

        self::assertSame(
            [
                CliComponentProviderClass::class,
                HttpComponentProviderClass::class,
                ComponentProviderClass::class,
            ],
            $application->getProviders(),
        );
    }

    /**
     * Test that getProviders populates the internal cache and subsequent calls use it.
     */
    public function testGetProvidersIsCached(): void
    {
        $config      = new Config(providers: [ComponentProviderClass::class]);
        $application = new Valkyrja(container: new Container(), config: $config);
        $reflection  = new ReflectionClass($application);
        $property    = $reflection->getProperty('providers');

        self::assertSame([], $property->getValue($application));

        $result = $application->getProviders();

        self::assertSame($result, $property->getValue($application));
        self::assertSame($result, $application->getProviders());
    }

    /**
     * Test that getContainerProviders collects results from all expanded providers.
     */
    public function testGetContainerProviders(): void
    {
        $config      = new Config(providers: [ComponentProviderClass::class]);
        $application = new Valkyrja(container: new Container(), config: $config);

        self::assertSame(
            [
                CliContainerDataProviderClass::class,
                CliRoutingDataProviderClass::class,
                HttpContainerDataProviderClass::class,
                HttpRoutingDataProviderClass::class,
            ],
            $application->getContainerProviders(),
        );
    }

    /**
     * Test that getContainerProviders populates the internal cache and subsequent calls use it.
     */
    public function testGetContainerProvidersIsCached(): void
    {
        $config      = new Config(providers: [ComponentProviderClass::class]);
        $application = new Valkyrja(container: new Container(), config: $config);
        $reflection  = new ReflectionClass($application);
        $property    = $reflection->getProperty('serviceProviders');

        self::assertSame([], $property->getValue($application));

        $result = $application->getContainerProviders();

        self::assertSame($result, $property->getValue($application));
        self::assertSame($result, $application->getContainerProviders());
    }

    /**
     * Test that getEventProviders collects results from all expanded providers.
     */
    public function testGetEventProviders(): void
    {
        $config      = new Config(providers: [EventComponentProviderClass::class]);
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
        $config      = new Config(providers: [EventComponentProviderClass::class]);
        $application = new Valkyrja(container: new Container(), config: $config);
        $reflection  = new ReflectionClass($application);
        $property    = $reflection->getProperty('eventProviders');

        self::assertSame([], $property->getValue($application));

        $result = $application->getEventProviders();

        self::assertSame($result, $property->getValue($application));
        self::assertSame($result, $application->getEventProviders());
    }

    /**
     * Test that getCliProviders collects results from all expanded providers.
     */
    public function testGetCliProviders(): void
    {
        $config      = new Config(providers: [CliRouteComponentProviderClass::class]);
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
        $config      = new Config(providers: [CliRouteComponentProviderClass::class]);
        $application = new Valkyrja(container: new Container(), config: $config);
        $reflection  = new ReflectionClass($application);
        $property    = $reflection->getProperty('cliRouteProviders');

        self::assertSame([], $property->getValue($application));

        $result = $application->getCliProviders();

        self::assertSame($result, $property->getValue($application));
        self::assertSame($result, $application->getCliProviders());
    }

    /**
     * Test that getHttpProviders collects results from all expanded providers.
     */
    public function testGetHttpProviders(): void
    {
        $config      = new Config(providers: [HttpRouteComponentProviderClass::class]);
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
        $config      = new Config(providers: [HttpRouteComponentProviderClass::class]);
        $application = new Valkyrja(container: new Container(), config: $config);
        $reflection  = new ReflectionClass($application);
        $property    = $reflection->getProperty('httpRouteProviders');

        self::assertSame([], $property->getValue($application));

        $result = $application->getHttpProviders();

        self::assertSame($result, $property->getValue($application));
        self::assertSame($result, $application->getHttpProviders());
    }

    /**
     * Test that getProviders deduplicates when a provider appears both as a direct
     * config entry and as a component dependency of another config entry.
     */
    public function testGetProvidersDeduplicates(): void
    {
        // ComponentProviderClass::getComponentProviders() returns [CliComponentProviderClass, HttpComponentProviderClass].
        // CliComponentProviderClass is also listed directly in config, so without array_unique it would appear twice.
        $config      = new Config(providers: [ComponentProviderClass::class, CliComponentProviderClass::class]);
        $application = new Valkyrja(container: new Container(), config: $config);

        $providers = $application->getProviders();

        self::assertSame(
            [
                CliComponentProviderClass::class,
                HttpComponentProviderClass::class,
                ComponentProviderClass::class,
            ],
            $providers,
        );
        self::assertCount(count(array_unique($providers)), $providers);
    }

    /**
     * Test that getContainerProviders deduplicates when two providers in the
     * expanded list both return the same container service provider.
     */
    public function testGetContainerProvidersDeduplicates(): void
    {
        // CliComponentProviderClass::getContainerProviders() returns [CliContainerDataProviderClass, CliRoutingDataProviderClass].
        // DuplicateSubProviderClass::getContainerProviders() also returns [CliContainerDataProviderClass].
        // Without array_unique the merged list would contain CliContainerDataProviderClass twice.
        $config      = new Config(providers: [CliComponentProviderClass::class, DuplicateSubProviderClass::class]);
        $application = new Valkyrja(container: new Container(), config: $config);

        $providers = $application->getContainerProviders();

        self::assertSame(
            [
                CliContainerDataProviderClass::class,
                CliRoutingDataProviderClass::class,
            ],
            $providers,
        );
        self::assertCount(count(array_unique($providers)), $providers);
    }

    /**
     * Test that getEventProviders deduplicates when two providers in the
     * expanded list both return the same listener provider.
     */
    public function testGetEventProvidersDeduplicates(): void
    {
        // EventComponentProviderClass::getEventProviders() returns [ListenerProviderClass].
        // DuplicateSubProviderClass::getEventProviders() also returns [ListenerProviderClass].
        // Without array_unique the merged list would contain ListenerProviderClass twice.
        $config      = new Config(providers: [EventComponentProviderClass::class, DuplicateSubProviderClass::class]);
        $application = new Valkyrja(container: new Container(), config: $config);

        $providers = $application->getEventProviders();

        self::assertSame(
            [ListenerProviderClass::class],
            $providers,
        );
        self::assertCount(count(array_unique($providers)), $providers);
    }

    /**
     * Test that getCliProviders deduplicates when two providers in the
     * expanded list both return the same CLI route provider.
     */
    public function testGetCliProvidersDeduplicates(): void
    {
        // CliRouteComponentProviderClass::getCliProviders() returns [CliRouteProviderClass].
        // DuplicateSubProviderClass::getCliProviders() also returns [CliRouteProviderClass].
        // Without array_unique the merged list would contain CliRouteProviderClass twice.
        $config      = new Config(providers: [CliRouteComponentProviderClass::class, DuplicateSubProviderClass::class]);
        $application = new Valkyrja(container: new Container(), config: $config);

        $providers = $application->getCliProviders();

        self::assertSame(
            [CliRouteProviderClass::class],
            $providers,
        );
        self::assertCount(count(array_unique($providers)), $providers);
    }

    /**
     * Test that getHttpProviders deduplicates when two providers in the
     * expanded list both return the same HTTP route provider.
     */
    public function testGetHttpProvidersDeduplicates(): void
    {
        // HttpRouteComponentProviderClass::getHttpProviders() returns [HttpRouteProviderClass].
        // DuplicateSubProviderClass::getHttpProviders() also returns [HttpRouteProviderClass].
        // Without array_unique the merged list would contain HttpRouteProviderClass twice.
        $config      = new Config(providers: [HttpRouteComponentProviderClass::class, DuplicateSubProviderClass::class]);
        $application = new Valkyrja(container: new Container(), config: $config);

        $providers = $application->getHttpProviders();

        self::assertSame(
            [HttpRouteProviderClass::class],
            $providers,
        );
        self::assertCount(count(array_unique($providers)), $providers);
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
