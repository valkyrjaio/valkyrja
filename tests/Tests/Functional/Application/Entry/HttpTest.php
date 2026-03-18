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
use Valkyrja\Application\Constant\ComponentClass;
use Valkyrja\Application\Data\HttpConfig;
use Valkyrja\Application\Directory\Directory;
use Valkyrja\Application\Entry\Http;
use Valkyrja\Container\Generator\DataFileGenerator;
use Valkyrja\Http\Message\Response\Response;
use Valkyrja\Http\Routing\Attribute\Route;
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

    #[Route('/version', 'version')]
    public static function routeCallback(): Response
    {
        self::$runCalled = true;

        return new Response();
    }

    public function testHttp(): void
    {
        Http::directory(Directory::$basePath);

        self::$runCalled = false;

        HttpComponentProviderClass::$publishedContainerData = false;

        $_SERVER['REQUEST_URI'] = '/version';

        $env = new class extends EnvClass {
            /** @var non-empty-string */
            public const string CONTAINER_DATA_CLASS_NAME = 'HttpTestContainerData';
            /** @var non-empty-string */
            public const string HTTP_ROUTING_DATA_CLASS_NAME = 'HttpTestHttpRoutingData';
        };
        $dir = Directory::$basePath;

        $config = new HttpConfig(
            dir: $dir,
            debugMode: true,
            providers: [
                ComponentClass::CONTAINER,
                ComponentClass::DISPATCHER,
                ComponentClass::EVENT,
                ComponentClass::HTTP_MESSAGE,
                ComponentClass::HTTP_MIDDLEWARE,
                ComponentClass::HTTP_ROUTING,
                ComponentClass::HTTP_SERVER,
                HttpComponentProviderClass::class,
            ],
        );

        $containerDataClassName        = 'HttpTestContainerData';
        $containerDataFilePath         = "/$containerDataClassName.php";
        $containerDirectory            = Directory::srcPath($config->dataPath);
        $absoluteContainerDataFilePath = $containerDirectory . $containerDataFilePath;
        $routesDataClassName           = 'HttpTestHttpRoutingData';
        $routesDataFilePath            = "/$routesDataClassName.php";
        $routesDirectory               = Directory::srcPath($config->dataPath);
        $absoluteRoutesDataFilePath    = $routesDirectory . $routesDataFilePath;

        @unlink($absoluteContainerDataFilePath);
        @unlink($absoluteRoutesDataFilePath);

        $application = Http::app($env, $config);
        $container   = $application->getContainer();

        $http = $container->getSingleton(CollectionContract::class);

        $dataFileGenerator = new DataFileGenerator(
            directory: $containerDirectory,
            data: $container->getData(),
            namespace: $config->dataNamespace,
            className: $containerDataClassName
        );
        $dataFileGenerator->generateFile();
        $httpDataFileGenerator = new HttpDataFileGenerator(
            directory: $routesDirectory,
            data: $http->getData(),
            namespace: $config->dataNamespace,
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
            /** @var non-empty-string */
            public const string CONTAINER_DATA_CLASS_NAME = 'HttpTestContainerData';
            /** @var non-empty-string */
            public const string HTTP_ROUTING_DATA_CLASS_NAME = 'HttpTestHttpRoutingData';
        };

        $config = new HttpConfig(
            dir: $dir,
            debugMode: false,
            providers: [
                ComponentClass::CONTAINER,
                ComponentClass::DISPATCHER,
                ComponentClass::EVENT,
                ComponentClass::HTTP_MESSAGE,
                ComponentClass::HTTP_MIDDLEWARE,
                ComponentClass::HTTP_ROUTING,
                ComponentClass::HTTP_SERVER,
                HttpComponentProviderClass::class,
            ],
            callbacks: [
                [HttpComponentProviderClass::class, 'publish'],
            ],
        );

        ob_start();
        Http::run($env, $config);
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
            /** @var non-empty-string */
            public const string CONTAINER_DATA_CLASS_NAME = 'HttpTestContainerData';
            /** @var non-empty-string */
            public const string HTTP_ROUTING_DATA_CLASS_NAME = 'HttpTestHttpRoutingData';
        };

        $config = new HttpConfig(
            dir: $dir,
            debugMode: true,
            providers: [
                ComponentClass::CONTAINER,
                ComponentClass::DISPATCHER,
                ComponentClass::EVENT,
                ComponentClass::HTTP_MESSAGE,
                ComponentClass::HTTP_MIDDLEWARE,
                ComponentClass::HTTP_ROUTING,
                ComponentClass::HTTP_SERVER,
                HttpComponentProviderClass::class,
            ],
            callbacks: [
                [HttpComponentProviderClass::class, 'publish'],
            ],
        );

        ob_start();
        Http::run($env, $config);
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
