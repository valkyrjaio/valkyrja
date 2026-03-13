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
use Valkyrja\Application\Directory\Directory;
use Valkyrja\Application\Entry\Cli;
use Valkyrja\Application\Provider\Provider;
use Valkyrja\Cli\Interaction\Output\Output;
use Valkyrja\Cli\Routing\Attribute\Route as Attribute;
use Valkyrja\Cli\Routing\Collection\Contract\CollectionContract;
use Valkyrja\Cli\Routing\Data\Route;
use Valkyrja\Cli\Routing\Generator\DataFileGenerator as CliDataFileGenerator;
use Valkyrja\Cli\Server\Support\Exiter;
use Valkyrja\Container\Generator\DataFileGenerator;
use Valkyrja\Dispatch\Data\MethodDispatch;
use Valkyrja\Tests\Classes\Application\Provider\CliComponentProviderClass;
use Valkyrja\Tests\Classes\Application\Provider\CliRouteProviderClass;
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
    protected static bool $runCalled = false;

    #[Attribute('version', 'test')]
    public static function routeCallback(): Output
    {
        self::$runCalled = true;

        return new Output();
    }

    public function testRun(): void
    {
        Cli::directory(EnvClass::APP_DIR);

        self::$runCalled = false;

        Exiter::freeze();

        $_SERVER['argv'] = [
            'cli',
            'version',
        ];

        $env = new class extends EnvClass {
            /** @var bool */
            public const bool APP_DEBUG_MODE = true;
            /** @var non-empty-string */
            public const string CONTAINER_DATA_PROVIDER_CLASS_NAME = 'CliTestContainerDataProvider';
            /** @var non-empty-string */
            public const string CLI_ROUTING_DATA_PROVIDER_CLASS_NAME = 'CliTestCliRoutingDataProvider';
        };
        /** @var non-empty-string $dir */
        $dir                           = $env::APP_DIR;
        $containerDataClassName        = 'CliTestContainerDataProvider';
        $containerDataFilePath         = "/$containerDataClassName.php";
        $containerDirectory            = Directory::srcPath(EnvClass::APP_DATA_PATH);
        $absoluteContainerDataFilePath = $containerDirectory . $containerDataFilePath;
        $routesDataClassName           = 'CliTestCliRoutingDataProvider';
        $routesDataFilePath            = "/$routesDataClassName.php";
        $routesDirectory               = Directory::srcPath(EnvClass::APP_DATA_PATH);
        $absoluteRoutesDataFilePath    = $routesDirectory . $routesDataFilePath;

        @unlink($absoluteContainerDataFilePath);
        @unlink($absoluteRoutesDataFilePath);

        $application = Cli::app($env);
        $container   = $application->getContainer();

        $cli = $container->getSingleton(CollectionContract::class);

        $cli->add(
            new Route(
                name: 'version',
                description: 'test',
                dispatch: MethodDispatch::fromCallableOrArray([self::class, 'routeCallback'])
            )
        );

        $dataFileGenerator = new DataFileGenerator(
            directory: $containerDirectory,
            data: $container->getData(),
            namespace: EnvClass::APP_DATA_NAMESPACE,
            className: $containerDataClassName
        );
        $dataFileGenerator->generateFile();
        $cliDataFileGenerator = new CliDataFileGenerator(
            directory: $routesDirectory,
            data: $cli->getData(),
            namespace: EnvClass::APP_DATA_NAMESPACE,
            className: $routesDataClassName
        );
        $cliDataFileGenerator->generateFile();

        require_once $absoluteContainerDataFilePath;

        require_once $absoluteRoutesDataFilePath;

        $env = new class extends EnvClass {
            /** @var bool */
            public const bool APP_DEBUG_MODE = false;
            /** @var non-empty-string */
            public const string CONTAINER_DATA_PROVIDER_CLASS_NAME = 'CliTestContainerDataProvider';
            /** @var non-empty-string */
            public const string CLI_ROUTING_DATA_PROVIDER_CLASS_NAME = 'CliTestCliRoutingDataProvider';
            /** @var class-string<Provider>[] */
            public const array APP_CUSTOM_COMPONENTS = [
                CliComponentProviderClass::class,
            ];
        };

        ob_start();
        Cli::run($dir, $env);
        ob_get_clean();

        self::assertTrue(self::$runCalled);
        self::$runCalled = false;

        // With debug mode off we expect the data service providers to provide the data and routes
        self::assertFalse(CliRouteProviderClass::$called);
        CliRouteProviderClass::$called = false;

        $env = new class extends EnvClass {
            /** @var bool */
            public const bool APP_DEBUG_MODE = true;
            /** @var non-empty-string */
            public const string CONTAINER_DATA_PROVIDER_CLASS_NAME = 'CliTestContainerDataProvider';
            /** @var non-empty-string */
            public const string CLI_ROUTING_DATA_PROVIDER_CLASS_NAME = 'CliTestCliRoutingDataProvider';
            /** @var class-string<Provider>[] */
            public const array APP_CUSTOM_COMPONENTS = [
                CliComponentProviderClass::class,
            ];
        };

        ob_start();
        Cli::run($dir, $env);
        ob_get_clean();

        restore_error_handler();
        restore_exception_handler();

        self::assertTrue(self::$runCalled);
        self::$runCalled = false;

        // With debug mode on we expect the data service providers to NOT provide the data and routes
        self::assertTrue(CliRouteProviderClass::$called);
        CliRouteProviderClass::$called = false;

        Exiter::unfreeze();

        @unlink($absoluteContainerDataFilePath);
        @unlink($absoluteRoutesDataFilePath);
    }
}
