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

namespace Valkyrja\Tests\Unit\Application\Entry;

use Valkyrja\Application\Data\Data;
use Valkyrja\Application\Entry\Cli;
use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Interaction\Output\Output;
use Valkyrja\Cli\Routing\Collection\Contract\CollectionContract;
use Valkyrja\Cli\Routing\Data\Route;
use Valkyrja\Cli\Server\Support\Exiter;
use Valkyrja\Container\Generator\Contract\DataFileGeneratorContract;
use Valkyrja\Dispatch\Data\MethodDispatch;
use Valkyrja\Event\Data\Data as EventData;
use Valkyrja\Http\Routing\Data\Data as HttpData;
use Valkyrja\Support\Directory\Directory;
use Valkyrja\Tests\EnvClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;

use function restore_error_handler;
use function restore_exception_handler;

use const LOCK_EX;

/**
 * Test the Cli service.
 */
class CliTest extends TestCase
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
            /** @var non-empty-string */
            public const string APP_CACHE_FILE_PATH = '/storage/AppTestCli.php';
            /** @var bool|null */
            public const bool|null CONTAINER_USE_CACHE = true;
            /** @var non-empty-string|null */
            public const string|null CONTAINER_CACHE_FILE_PATH = 'AppTestCli-container.php';
        };
        /** @var non-empty-string $dir */
        $dir = $env::APP_DIR;
        /** @var non-empty-string $filepath */
        $filepath = EnvClass::APP_DIR . $env::APP_CACHE_FILE_PATH;
        /** @var non-empty-string $containerCacheFilePath */
        $containerCacheFilePath = $env::CONTAINER_CACHE_FILE_PATH
            ?? '/container.php';
        $absoluteContainerCacheFilePath = Directory::cachePath($containerCacheFilePath);

        @unlink($filepath);
        @unlink($absoluteContainerCacheFilePath);

        $application = Cli::app($env);
        $container   = $application->getContainer();

        $cli = $container->getSingleton(CollectionContract::class);

        $cli->add(
            new Route(
                name: 'version',
                description: 'test',
                helpText: new Message('test'),
                dispatch: MethodDispatch::fromCallableOrArray([self::class, 'routeCallback'])
            )
        );
        $data = new Data(
            event: new EventData(),
            cli: $cli->getData(),
            http: new HttpData()
        );

        $dataFileGenerator = $container->getSingleton(DataFileGeneratorContract::class);
        $dataFileGenerator->generateFile($container->getData());

        file_put_contents($filepath, serialize($data), LOCK_EX);

        ob_start();
        Cli::run($dir, $env);
        ob_get_clean();

        restore_error_handler();
        restore_exception_handler();

        self::assertTrue(self::$runCalled);

        @unlink($filepath);
        @unlink($absoluteContainerCacheFilePath);
        self::$runCalled = false;
        Exiter::unfreeze();
    }
}
