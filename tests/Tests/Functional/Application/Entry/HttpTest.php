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
use Valkyrja\Container\Generator\DataFileGenerator;
use Valkyrja\Dispatch\Data\MethodDispatch;
use Valkyrja\Http\Message\Response\Response;
use Valkyrja\Http\Routing\Collection\Contract\CollectionContract;
use Valkyrja\Http\Routing\Data\Route;
use Valkyrja\Http\Routing\Generator\DataFileGenerator as HttpDataFileGenerator;
use Valkyrja\Tests\EnvClass;
use Valkyrja\Tests\Functional\Abstract\TestCase;

/**
 * Test the Http service.
 */
#[RunTestsInSeparateProcesses]
final class HttpTest extends TestCase
{
    protected static bool $runCalled = false;

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
            /** @var bool|null */
            public const bool|null CONTAINER_USE_DATA = true;
            /** @var non-empty-string|null */
            public const string|null CONTAINER_DATA_FILE_PATH = 'AppTestHttp-container.php';
            /** @var bool|null */
            public const bool|null HTTP_ROUTING_COLLECTION_USE_DATA = true;
            /** @var non-empty-string|null */
            public const string|null HTTP_ROUTING_COLLECTION_DATA_FILE_PATH = 'AppTestHttp-routes.php';
        };
        /** @var non-empty-string $dir */
        $dir = $env::APP_DIR;
        /** @var non-empty-string $containerDataFilePath */
        $containerDataFilePath = $env::CONTAINER_DATA_FILE_PATH
            ?? '/container.php';
        $absoluteContainerDataFilePath = Directory::dataPath($containerDataFilePath);
        /** @var non-empty-string $containerDataFilePath */
        $routesDataFilePath = $env::HTTP_ROUTING_COLLECTION_DATA_FILE_PATH
            ?? '/routes.php';
        $absoluteRoutesDataFilePath = Directory::dataPath($routesDataFilePath);

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

        $dataFileGenerator = new DataFileGenerator($absoluteContainerDataFilePath, $container->getData());
        $dataFileGenerator->generateFile();
        $httpDataFileGenerator = new HttpDataFileGenerator($absoluteRoutesDataFilePath, $http->getData());
        $httpDataFileGenerator->generateFile();

        ob_start();
        Http::run($dir, $env);
        ob_get_clean();

        self::assertTrue(self::$runCalled);

        @unlink($absoluteContainerDataFilePath);
        @unlink($absoluteRoutesDataFilePath);
        self::$runCalled = false;
    }
}
