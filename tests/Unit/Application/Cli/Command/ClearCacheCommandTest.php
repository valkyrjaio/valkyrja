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

use Valkyrja\Application\Cli\Command\ClearCacheCommand;
use Valkyrja\Cli\Interaction\Factory\OutputFactory;
use Valkyrja\Tests\EnvClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the ClearCacheCommand service.
 */
class ClearCacheCommandTest extends TestCase
{
    public function testRun(): void
    {
        $env = new class extends EnvClass {
            /** @var non-empty-string */
            public const string APP_CACHE_FILE_PATH = '/storage/ClearCacheCommandTestCache.php';
        };

        /** @var non-empty-string $filepath */
        $filepath = EnvClass::APP_DIR . $env::APP_CACHE_FILE_PATH;

        file_put_contents($filepath, 'test');

        self::assertFileExists($filepath);

        $command = new ClearCacheCommand();

        ob_start();
        $output = $command->run(
            env: $env,
            outputFactory: new OutputFactory()
        );
        $output->writeMessages();
        $contents = ob_get_clean();

        $expected = 'Application config cache cleared successfully.';

        self::assertFileDoesNotExist($filepath);
        self::assertIsString($contents);
        self::assertStringContainsString($expected, $contents);
    }
}
