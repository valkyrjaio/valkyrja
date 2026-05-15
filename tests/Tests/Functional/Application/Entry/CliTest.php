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

namespace Valkyrja\Tests\Functional\Application\Entry;

use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Valkyrja\Application\Data\CliConfig;
use Valkyrja\Application\Data\Contract\CliConfigContract;
use Valkyrja\Application\Directory\Directory;
use Valkyrja\Application\Entry\Cli;
use Valkyrja\Application\Env\Env;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Application\Provider\CliApplicationComponentProvider;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Interaction\Output\Output;
use Valkyrja\Cli\Routing\Attribute\Route;
use Valkyrja\Cli\Routing\Attribute\Route\RouteHandler;
use Valkyrja\Cli\Routing\Collection\Contract\RouteCollectionContract;
use Valkyrja\Cli\Routing\Data\Contract\CliRoutingConfigContract;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;
use Valkyrja\Cli\Server\Support\Exiter;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Tests\Classes\Application\Provider\CliComponentProviderClass;
use Valkyrja\Tests\Classes\Application\Provider\CliRouteProviderClass;
use Valkyrja\Tests\Classes\Application\Provider\CliRoutingDataProviderClass;
use Valkyrja\Tests\EnvClass;
use Valkyrja\Tests\Functional\Abstract\TestCase;

use function restore_error_handler;
use function restore_exception_handler;

/**
 * Test the Cli service.
 */
#[RunTestsInSeparateProcesses]
final class CliTest extends TestCase
{
    protected static bool $handlerCalled = false;
    protected static bool $runCalled     = false;

    public static function routeHandler(ContainerContract $container, RouteContract $route): OutputContract
    {
        self::$handlerCalled = true;

        return self::routeCallback();
    }

    #[Route('version', 'test')]
    #[RouteHandler([self::class, 'routeHandler'])]
    public static function routeCallback(): Output
    {
        self::$runCalled = true;

        return new Output();
    }

    public function testRun(): void
    {
        Cli::directory(Directory::$basePath);

        self::$handlerCalled = false;
        self::$runCalled     = false;

        CliComponentProviderClass::$publishedContainerData = false;

        CliRoutingDataProviderClass::$published = false;

        Exiter::freeze();

        $_SERVER['argv'] = [
            'cli',
            'version',
        ];

        $env = new class extends EnvClass {
            /** @var non-empty-string */
            public const string CONTAINER_DATA_CLASS_NAME = 'CliTestContainerData';
        };
        $dir = Directory::$basePath;

        $config = new class(dir: $dir) extends CliConfig implements CliRoutingConfigContract {
            public string $dataClassName = 'CliTestCliRoutingData';

            public function __construct(
                string $dir,
            ) {
                parent::__construct(
                    dir: $dir,
                    debugMode: true,
                    providers: [
                        new CliApplicationComponentProvider(),
                        new CliComponentProviderClass(),
                    ],
                );
            }
        };

        $application = Cli::app($env, $config);
        $container   = $application->getContainer();

        $container->getSingleton(RouteCollectionContract::class);

        self::assertTrue($container->has(CliConfigContract::class));
        self::assertTrue($container->has(Env::class));
        self::assertTrue($container->has(ContainerContract::class));
        self::assertTrue($container->has(ApplicationContract::class));

        // With debug mode on we expect the data service providers to NOT provide the data and routes
        self::assertTrue(CliRouteProviderClass::$called);
        CliRouteProviderClass::$called = false;
        // With debug mode on we expect the component publish method to bypass
        self::assertFalse(CliComponentProviderClass::$publishedContainerData);
        CliComponentProviderClass::$publishedContainerData = false;
        // With debug mode on we expect the route data publisher publish method to bypass
        self::assertFalse(CliRoutingDataProviderClass::$published);
        CliRoutingDataProviderClass::$published = false;

        $env = new class extends EnvClass {
            /** @var non-empty-string */
            public const string CONTAINER_DATA_CLASS_NAME = 'CliTestContainerData';
        };

        $config = new class(dir: $dir) extends CliConfig implements CliRoutingConfigContract {
            public string $dataClassName = 'CliTestCliRoutingData';

            public function __construct(
                string $dir,
            ) {
                parent::__construct(
                    dir: $dir,
                    debugMode: false,
                    providers: [
                        new CliApplicationComponentProvider(),
                        new CliComponentProviderClass(),
                    ],
                    callbacks: [
                        [CliComponentProviderClass::class, 'publish'],
                    ],
                );
            }
        };

        ob_start();
        Cli::run(config: $config, env: $env);
        ob_get_clean();

        self::assertTrue(self::$runCalled);
        self::$runCalled = false;

        self::assertTrue(self::$handlerCalled);
        self::$handlerCalled = false;

        // With debug mode off we expect the data service providers to provide the data and routes
        self::assertFalse(CliRouteProviderClass::$called);
        CliRouteProviderClass::$called = false;
        // With debug mode off we expect the component publish method to NOT bypass
        self::assertTrue(CliComponentProviderClass::$publishedContainerData);
        CliComponentProviderClass::$publishedContainerData = false;
        // With debug mode off we expect the route data publisher publish method to NOT bypass
        self::assertTrue(CliRoutingDataProviderClass::$published);
        CliRoutingDataProviderClass::$published = false;

        $env = new class extends EnvClass {
            /** @var non-empty-string */
            public const string CONTAINER_DATA_CLASS_NAME = 'CliTestContainerData';
        };

        $config = new class(dir: $dir) extends CliConfig implements CliRoutingConfigContract {
            public string $dataClassName = 'CliTestCliRoutingData';

            public function __construct(
                string $dir,
            ) {
                parent::__construct(
                    dir: $dir,
                    debugMode: true,
                    providers: [
                        new CliApplicationComponentProvider(),
                        new CliComponentProviderClass(),
                    ],
                    callbacks: [
                        [CliComponentProviderClass::class, 'publish'],
                    ],
                );
            }
        };

        ob_start();
        Cli::run(config: $config, env: $env);
        ob_get_clean();

        restore_error_handler();
        restore_exception_handler();

        self::assertTrue(self::$runCalled);
        self::$runCalled = false;

        self::assertTrue(self::$handlerCalled);
        self::$handlerCalled = false;

        // With debug mode on we expect the data service providers to NOT provide the data and routes
        self::assertTrue(CliRouteProviderClass::$called);
        CliRouteProviderClass::$called = false;
        // With debug mode on we expect the component publish method to bypass
        self::assertFalse(CliComponentProviderClass::$publishedContainerData);
        CliComponentProviderClass::$publishedContainerData = false;
        // With debug mode on we expect the route data publisher publish method to bypass
        self::assertFalse(CliRoutingDataProviderClass::$published);
        CliRoutingDataProviderClass::$published = false;

        Exiter::unfreeze();
    }
}
