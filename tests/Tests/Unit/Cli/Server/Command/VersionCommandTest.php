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

namespace Valkyrja\Tests\Unit\Cli\Server\Command;

use Valkyrja\Application\Constant\ApplicationInfo;
use Valkyrja\Cli\Interaction\Message\NewLine;
use Valkyrja\Cli\Interaction\Output\Factory\OutputFactory;
use Valkyrja\Cli\Server\Command\VersionCommand;
use Valkyrja\Tests\Unit\Abstract\TestCase;

use const PHP_VERSION;

class VersionCommandTest extends TestCase
{
    public function testRun(): void
    {
        $outputFactory = new OutputFactory();
        $command       = new VersionCommand($outputFactory);

        $output = $command->run();

        self::assertSame(ApplicationInfo::ASCII, $output->getMessages()[0]->getText());
        self::assertInstanceOf(NewLine::class, $output->getMessages()[1]);
        self::assertInstanceOf(NewLine::class, $output->getMessages()[2]);
        self::assertSame('Valkyrja Framework', $output->getMessages()[3]->getText());
        self::assertSame(' version ', $output->getMessages()[4]->getText());
        self::assertSame(ApplicationInfo::VERSION, $output->getMessages()[5]->getText());
        self::assertSame(' (built: ', $output->getMessages()[6]->getText());
        self::assertSame(ApplicationInfo::VERSION_BUILD_DATE_TIME, $output->getMessages()[7]->getText());
        self::assertSame(')', $output->getMessages()[8]->getText());
        self::assertInstanceOf(NewLine::class, $output->getMessages()[9]);
        self::assertSame('Copyright (c) Melech Mizrachi', $output->getMessages()[10]->getText());
        self::assertInstanceOf(NewLine::class, $output->getMessages()[11]);
        self::assertSame('GitHub https://github.com/valkyrjaio/valkyrja', $output->getMessages()[12]->getText());
        self::assertInstanceOf(NewLine::class, $output->getMessages()[13]);
        self::assertSame('Running on PHP ' . PHP_VERSION, $output->getMessages()[14]->getText());
        self::assertInstanceOf(NewLine::class, $output->getMessages()[15]);
        self::assertInstanceOf(NewLine::class, $output->getMessages()[16]);
    }

    public function testHelp(): void
    {
        $text = 'A command to show the application version and info.';

        self::assertSame($text, VersionCommand::help()->getText());
        self::assertSame($text, VersionCommand::help()->getFormattedText());
    }
}
