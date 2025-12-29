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

namespace Valkyrja\Tests\Unit\Application\Cli\Command;

use Valkyrja\Application\Cli\Command\CacheCommand;
use Valkyrja\Application\Data\Data;
use Valkyrja\Cli\Interaction\Factory\OutputFactory;
use Valkyrja\Cli\Routing\Collection\Collection as CliCollection;
use Valkyrja\Container\Manager\Container;
use Valkyrja\Event\Collection\Collection as EventCollection;
use Valkyrja\Http\Routing\Collection\Collection as HttpCollection;
use Valkyrja\Tests\EnvClass;
use Valkyrja\Tests\Unit\TestCase;

use const LOCK_EX;

/**
 * Test the ClearCacheCommand service.
 *
 * @author Melech Mizrachi
 */
class CacheCommandTest extends TestCase
{
    public function testRun(): void
    {
        $env = new class extends EnvClass {
            /** @var non-empty-string */
            public const string APP_CACHE_FILE_PATH = EnvClass::APP_DIR . '/storage/CacheCommandTestCache.php';
        };

        /** @var non-empty-string $filepath */
        $filepath = $env::APP_CACHE_FILE_PATH;

        file_put_contents($filepath, 'test', LOCK_EX);

        self::assertFileExists($filepath);

        $command   = new CacheCommand();
        $container = new Container();
        $cli       = new CliCollection();
        $event     = new EventCollection();
        $http      = new HttpCollection();

        $data = new Data(
            container: $container->getData(),
            event: $event->getData(),
            cli: $cli->getData(),
            http: $http->getData(),
        );

        $serializedData = serialize($data);

        ob_start();
        $output = $command->run(
            container: $container,
            cliCollection: $cli,
            eventCollection: $event,
            routerCollection: $http,
            env: $env,
            outputFactory: new OutputFactory()
        );
        $output->writeMessages();
        $outputContents = ob_get_clean();

        $fileContents = file_get_contents($filepath);

        $expected = 'Application config cached successfully.';

        self::assertFileExists($filepath);
        self::assertSame($serializedData, $fileContents);
        self::assertIsString($outputContents);
        self::assertStringContainsString($expected, $outputContents);

        unlink($filepath);
    }

    public function testRunInvalidCachePath(): void
    {
        $env = new class extends EnvClass {
            /** @var non-empty-string */
            public const string APP_CACHE_FILE_PATH = EnvClass::APP_DIR . '/storage-non-existent/CacheCommandTestCache.php';
        };

        /** @var non-empty-string $filepath */
        $filepath = $env::APP_CACHE_FILE_PATH;

        $command   = new CacheCommand();
        $container = new Container();
        $cli       = new CliCollection();
        $event     = new EventCollection();
        $http      = new HttpCollection();

        ob_start();
        $output = $command->run(
            container: $container,
            cliCollection: $cli,
            eventCollection: $event,
            routerCollection: $http,
            env: $env,
            outputFactory: new OutputFactory()
        );
        $output->writeMessages();
        $outputContents = ob_get_clean();

        $expected = 'An error occurred while caching the config.';

        self::assertFileDoesNotExist($filepath);
        self::assertIsString($outputContents);
        self::assertStringContainsString($expected, $outputContents);
    }
}
