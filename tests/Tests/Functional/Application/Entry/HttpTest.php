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
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Application\Provider\Provider;
use Valkyrja\Container\Generator\DataFileGenerator;
use Valkyrja\Http\Message\Response\Response;
use Valkyrja\Http\Routing\Attribute\Route as Attribute;
use Valkyrja\Http\Routing\Collection\Contract\CollectionContract;
use Valkyrja\Http\Routing\Generator\DataFileGenerator as HttpDataFileGenerator;
use Valkyrja\Tests\Classes\Application\Provider\HttpComponentProviderClass;
use Valkyrja\Tests\Classes\Application\Provider\HttpRouteProviderClass;
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

        HttpComponentProviderClass::$publishedContainerData = false;

        $_SERVER['REQUEST_URI'] = '/version';

        $env = new class extends EnvClass {
            /** @var bool */
            public const bool APP_DEBUG_MODE = true;
            /** @var non-empty-string */
            public const string CONTAINER_DATA_CLASS_NAME = 'HttpTestContainerData';
            /** @var non-empty-string */
            public const string HTTP_ROUTING_DATA_CLASS_NAME = 'HttpTestHttpRoutingData';
            /** @var class-string<Provider>[] */
            public const array APP_CUSTOM_COMPONENTS = [
                HttpComponentProviderClass::class,
            ];
        };
        /** @var non-empty-string $dir */
        $dir                           = $env::APP_DIR;
        $containerDataClassName        = 'HttpTestContainerData';
        $containerDataFilePath         = "/$containerDataClassName.php";
        $containerDirectory            = Directory::srcPath(EnvClass::APP_DATA_PATH);
        $absoluteContainerDataFilePath = $containerDirectory . $containerDataFilePath;
        $routesDataClassName           = 'HttpTestHttpRoutingData';
        $routesDataFilePath            = "/$routesDataClassName.php";
        $routesDirectory               = Directory::srcPath(EnvClass::APP_DATA_PATH);
        $absoluteRoutesDataFilePath    = $routesDirectory . $routesDataFilePath;

        @unlink($absoluteContainerDataFilePath);
        @unlink($absoluteRoutesDataFilePath);

        $application = Http::app($env);
        $container   = $application->getContainer();

        $http = $container->getSingleton(CollectionContract::class);

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

        // With debug mode on we expect the data service providers to NOT provide the data and routes
        self::assertTrue(HttpRouteProviderClass::$called);
        HttpRouteProviderClass::$called = false;
        // With debug mode on we expect the component publish method to bypass
        self::assertFalse(HttpComponentProviderClass::$publishedContainerData);
        HttpComponentProviderClass::$publishedContainerData = false;

        require_once $absoluteContainerDataFilePath;

        require_once $absoluteRoutesDataFilePath;

        $env = new class extends EnvClass {
            /** @var bool */
            public const bool APP_DEBUG_MODE = false;
            /** @var non-empty-string */
            public const string CONTAINER_DATA_CLASS_NAME = 'HttpTestContainerData';
            /** @var non-empty-string */
            public const string HTTP_ROUTING_DATA_CLASS_NAME = 'HttpTestHttpRoutingData';
            /** @var class-string<Provider>[] */
            public const array APP_CUSTOM_COMPONENTS = [
                HttpComponentProviderClass::class,
            ];
            /** @var (callable(ApplicationContract):void)[] */
            public const array APP_PUBLISHABLE_CALLBACKS = [
                [HttpComponentProviderClass::class, 'publish'],
            ];
        };

        ob_start();
        Http::run($dir, $env);
        ob_get_clean();

        self::assertTrue(self::$runCalled);
        self::$runCalled = false;

        // With debug mode off we expect the data service providers to provide the data and routes
        self::assertFalse(HttpRouteProviderClass::$called);
        HttpRouteProviderClass::$called = false;
        // With debug mode off we expect the component publish method to NOT bypass
        self::assertTrue(HttpComponentProviderClass::$publishedContainerData);
        HttpComponentProviderClass::$publishedContainerData = false;

        $env = new class extends EnvClass {
            /** @var bool */
            public const bool APP_DEBUG_MODE = true;
            /** @var non-empty-string */
            public const string CONTAINER_DATA_CLASS_NAME = 'HttpTestContainerData';
            /** @var non-empty-string */
            public const string HTTP_ROUTING_DATA_CLASS_NAME = 'HttpTestHttpRoutingData';
            /** @var class-string<Provider>[] */
            public const array APP_CUSTOM_COMPONENTS = [
                HttpComponentProviderClass::class,
            ];
            /** @var (callable(ApplicationContract):void)[] */
            public const array APP_PUBLISHABLE_CALLBACKS = [
                [HttpComponentProviderClass::class, 'publish'],
            ];
        };

        ob_start();
        Http::run($dir, $env);
        ob_get_clean();

        self::assertTrue(self::$runCalled);
        self::$runCalled = false;

        // With debug mode on we expect the data service providers to NOT provide the data and routes
        self::assertTrue(HttpRouteProviderClass::$called);
        HttpRouteProviderClass::$called = false;
        // With debug mode on we expect the component publish method to bypass
        self::assertFalse(HttpComponentProviderClass::$publishedContainerData);
        HttpComponentProviderClass::$publishedContainerData = false;

        @unlink($absoluteContainerDataFilePath);
        @unlink($absoluteRoutesDataFilePath);
    }
}
