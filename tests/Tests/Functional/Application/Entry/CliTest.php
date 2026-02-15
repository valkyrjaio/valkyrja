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
use Valkyrja\Cli\Interaction\Output\Output;
use Valkyrja\Cli\Routing\Collection\Contract\CollectionContract;
use Valkyrja\Cli\Routing\Data\Route;
use Valkyrja\Cli\Routing\Generator\DataFileGenerator as CliDataFileGenerator;
use Valkyrja\Cli\Server\Support\Exiter;
use Valkyrja\Container\Generator\DataFileGenerator;
use Valkyrja\Dispatch\Data\MethodDispatch;
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
            /** @var bool|null */
            public const bool|null CONTAINER_USE_DATA = true;
            /** @var non-empty-string|null */
            public const string|null CONTAINER_DATA_FILE_PATH = 'AppTestCli-container.php';
            /** @var bool|null */
            public const bool|null CLI_ROUTING_COLLECTION_USE_DATA = true;
            /** @var non-empty-string|null */
            public const string|null CLI_ROUTING_COLLECTION_DATA_FILE_PATH = 'AppTestCli-routes.php';
        };
        /** @var non-empty-string $dir */
        $dir = $env::APP_DIR;
        /** @var non-empty-string $containerDataFilePath */
        $containerDataFilePath = $env::CONTAINER_DATA_FILE_PATH
            ?? '/container.php';
        $absoluteContainerDataFilePath = Directory::dataPath($containerDataFilePath);
        /** @var non-empty-string $containerDataFilePath */
        $routesDataFilePath = $env::CLI_ROUTING_COLLECTION_DATA_FILE_PATH
            ?? '/cli-routes.php';
        $absoluteRoutesDataFilePath = Directory::dataPath($routesDataFilePath);

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

        $dataFileGenerator = new DataFileGenerator($absoluteContainerDataFilePath, $container->getData());
        $dataFileGenerator->generateFile();
        $cliDataFileGenerator = new CliDataFileGenerator($absoluteRoutesDataFilePath, $cli->getData());
        $cliDataFileGenerator->generateFile();

        ob_start();
        Cli::run($dir, $env);
        ob_get_clean();

        restore_error_handler();
        restore_exception_handler();

        self::assertTrue(self::$runCalled);

        @unlink($absoluteContainerDataFilePath);
        @unlink($absoluteRoutesDataFilePath);
        self::$runCalled = false;
        Exiter::unfreeze();
    }
}
