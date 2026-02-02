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
use Valkyrja\Cli\Interaction\Output\Factory\OutputFactory;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the ClearCacheCommand service.
 */
class CacheCommandTest extends TestCase
{
    public function testRun(): void
    {
        $command = new CacheCommand();

        ob_start();
        $output = $command->run(
            outputFactory: new OutputFactory()
        );
        $output->writeMessages();
        $outputContents = ob_get_clean();

        $expected = 'Application config cached successfully.';

        self::assertIsString($outputContents);
        self::assertStringContainsString($expected, $outputContents);
    }
}
