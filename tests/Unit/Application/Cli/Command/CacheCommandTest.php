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
use Valkyrja\Cli\Interaction\Output\Factory\OutputFactory;
use Valkyrja\Cli\Routing\Collection\Collection as CliCollection;
use Valkyrja\Event\Collection\Collection as EventCollection;
use Valkyrja\Tests\EnvClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;

use const LOCK_EX;

/**
 * Test the ClearCacheCommand service.
 */
class CacheCommandTest extends TestCase
{
    public function testRun(): void
    {
        $env = new class extends EnvClass {
            /** @var non-empty-string */
            public const string APP_CACHE_FILE_PATH = '/storage/CacheCommandTestCache.php';
        };

        /** @var non-empty-string $filepath */
        $filepath = EnvClass::APP_DIR . $env::APP_CACHE_FILE_PATH;

        file_put_contents($filepath, 'test', LOCK_EX);

        self::assertFileExists($filepath);

        $command   = new CacheCommand();
        $cli       = new CliCollection();
        $event     = new EventCollection();

        $data = new Data(
            event: $event->getData(),
            cli: $cli->getData(),
        );

        $serializedData = serialize($data);

        ob_start();
        $output = $command->run(
            cliCollection: $cli,
            eventCollection: $event,
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
        $cli       = new CliCollection();
        $event     = new EventCollection();

        ob_start();
        $output = $command->run(
            cliCollection: $cli,
            eventCollection: $event,
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
