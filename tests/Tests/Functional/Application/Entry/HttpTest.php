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
use Valkyrja\Application\Entry\Http;
use Valkyrja\Application\Provider\Provider;
use Valkyrja\Container\Generator\DataFileGenerator;
use Valkyrja\Dispatch\Data\MethodDispatch;
use Valkyrja\Http\Message\Response\Response;
use Valkyrja\Http\Routing\Attribute\Route as Attribute;
use Valkyrja\Http\Routing\Collection\Contract\CollectionContract;
use Valkyrja\Http\Routing\Data\Route;
use Valkyrja\Http\Routing\Generator\DataFileGenerator as HttpDataFileGenerator;
use Valkyrja\Tests\Classes\Application\Provider\HttpComponentProvider;
use Valkyrja\Tests\Classes\Application\Provider\HttpRouteProvider;
use Valkyrja\Tests\EnvClass;
use Valkyrja\Tests\Functional\Abstract\TestCase;

/**
 * Test the Http service.
 */
#[RunTestsInSeparateProcesses]
final class HttpTest extends TestCase
{
    protected static bool $runCalled = false;

    #[Attribute('/version', 'version')]
    public static function routeCallback(): Response
    {
        self::$runCalled = true;

        return new Response();
    }

    public function testHttp(): void
    {
        Http::directory(EnvClass::APP_DIR);

        self::$runCalled = false;

        $_SERVER['REQUEST_URI'] = '/version';

        $env = new class extends EnvClass {
            /** @var bool */
            public const bool APP_DEBUG_MODE = true;
            /** @var non-empty-string */
            public const string CONTAINER_DATA_PROVIDER_CLASS_NAME = 'HttpTestContainerDataProvider';
            /** @var non-empty-string */
            public const string HTTP_ROUTING_DATA_PROVIDER_CLASS_NAME = 'HttpTestHttpRoutingDataProvider';
        };
        /** @var non-empty-string $dir */
        $dir                           = $env::APP_DIR;
        $containerDataClassName        = 'HttpTestContainerDataProvider';
        $containerDataFilePath         = "/$containerDataClassName.php";
        $containerDirectory            = Directory::srcPath(EnvClass::APP_DATA_PATH);
        $absoluteContainerDataFilePath = $containerDirectory . $containerDataFilePath;
        $routesDataClassName           = 'HttpTestHttpRoutingDataProvider';
        $routesDataFilePath            = "/$routesDataClassName.php";
        $routesDirectory               = Directory::srcPath(EnvClass::APP_DATA_PATH);
        $absoluteRoutesDataFilePath    = $routesDirectory . $routesDataFilePath;

        @unlink($absoluteContainerDataFilePath);
        @unlink($absoluteRoutesDataFilePath);

        $application = Http::app($env);
        $container   = $application->getContainer();

        $http = $container->getSingleton(CollectionContract::class);

        $http->add(
            new Route(
                path: '/version',
                name: 'version',
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
        $httpDataFileGenerator = new HttpDataFileGenerator(
            directory: $routesDirectory,
            data: $http->getData(),
            namespace: EnvClass::APP_DATA_NAMESPACE,
            className: $routesDataClassName
        );
        $httpDataFileGenerator->generateFile();

        require_once $absoluteContainerDataFilePath;

        require_once $absoluteRoutesDataFilePath;

        $env = new class extends EnvClass {
            /** @var bool */
            public const bool APP_DEBUG_MODE = false;
            /** @var non-empty-string */
            public const string CONTAINER_DATA_PROVIDER_CLASS_NAME = 'HttpTestContainerDataProvider';
            /** @var non-empty-string */
            public const string HTTP_ROUTING_DATA_PROVIDER_CLASS_NAME = 'HttpTestHttpRoutingDataProvider';
            /** @var class-string<Provider>[] */
            public const array APP_CUSTOM_COMPONENTS = [
                HttpComponentProvider::class,
            ];
        };

        ob_start();
        Http::run($dir, $env);
        ob_get_clean();

        self::assertTrue(self::$runCalled);
        self::$runCalled = false;

        // With debug mode off we expect the data service providers to provide the data and routes
        self::assertFalse(HttpRouteProvider::$called);
        HttpRouteProvider::$called = false;

        $env = new class extends EnvClass {
            /** @var bool */
            public const bool APP_DEBUG_MODE = true;
            /** @var non-empty-string */
            public const string CONTAINER_DATA_PROVIDER_CLASS_NAME = 'HttpTestContainerDataProvider';
            /** @var non-empty-string */
            public const string HTTP_ROUTING_DATA_PROVIDER_CLASS_NAME = 'HttpTestHttpRoutingDataProvider';
            /** @var class-string<Provider>[] */
            public const array APP_CUSTOM_COMPONENTS = [
                HttpComponentProvider::class,
            ];
        };

        ob_start();
        Http::run($dir, $env);
        ob_get_clean();

        self::assertTrue(self::$runCalled);
        self::$runCalled = false;

        // With debug mode on we expect the data service providers to NOT provide the data and routes
        self::assertTrue(HttpRouteProvider::$called);
        HttpRouteProvider::$called = false;

        @unlink($absoluteContainerDataFilePath);
        @unlink($absoluteRoutesDataFilePath);
    }
}
