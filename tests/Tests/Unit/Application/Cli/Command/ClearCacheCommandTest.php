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
use Valkyrja\Cli\Interaction\Output\Factory\OutputFactory;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the ClearCacheCommand service.
 */
final class ClearCacheCommandTest extends TestCase
{
    public function testRun(): void
    {
        $command = new ClearCacheCommand();

        ob_start();
        $output = $command->run(
            outputFactory: new OutputFactory()
        );
        $output->writeMessages();
        $contents = ob_get_clean();

        $expected = 'Application config cache cleared successfully.';

        self::assertIsString($contents);
        self::assertStringContainsString($expected, $contents);
    }

    public function testHelp(): void
    {
        $text = 'A command to clear the config cache.';

        self::assertSame($text, ClearCacheCommand::help()->getText());
        self::assertSame($text, ClearCacheCommand::help()->getFormattedText());
    }
}
