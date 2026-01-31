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

namespace Valkyrja\Tests\Unit\Cli\Interaction\Output\Factory;

use Valkyrja\Cli\Interaction\Data\Config;
use Valkyrja\Cli\Interaction\Enum\ExitCode;
use Valkyrja\Cli\Interaction\Message\NewLine;
use Valkyrja\Cli\Interaction\Output\EmptyOutput;
use Valkyrja\Cli\Interaction\Output\Factory\OutputFactory;
use Valkyrja\Cli\Interaction\Output\FileOutput;
use Valkyrja\Cli\Interaction\Output\Output;
use Valkyrja\Cli\Interaction\Output\PlainOutput;
use Valkyrja\Cli\Interaction\Output\StreamOutput;
use Valkyrja\Tests\Unit\Abstract\TestCase;

use function fopen;

/**
 * Test the OutputFactory class.
 */
class OutputFactoryTest extends TestCase
{
    public function testCreateOutput(): void
    {
        $config  = new Config(
            isQuiet: true,
            isInteractive: false,
            isSilent: true
        );
        $factory = new OutputFactory(config: $config);
        $output  = $factory->createOutput();
        $output2 = $factory->createOutput(ExitCode::AUTO_EXIT, new NewLine());

        self::assertInstanceOf(Output::class, $output);
        self::assertInstanceOf(Output::class, $output2);
        self::assertSame(ExitCode::SUCCESS, $output->getExitCode());
        self::assertTrue($output->isQuiet());
        self::assertFalse($output->isInteractive());
        self::assertTrue($output->isSilent());
        self::assertEmpty($output->getMessages());
        self::assertEmpty($output->getUnwrittenMessages());
        self::assertEmpty($output->getWrittenMessages());
        self::assertSame(ExitCode::AUTO_EXIT, $output2->getExitCode());
        self::assertCount(1, $output2->getMessages());
        self::assertCount(1, $output2->getUnwrittenMessages());
        self::assertEmpty($output2->getWrittenMessages());
        self::assertTrue($output2->isQuiet());
        self::assertFalse($output2->isInteractive());
        self::assertTrue($output2->isSilent());
    }

    public function testCreateEmptyOutput(): void
    {
        $config  = new Config(
            isQuiet: true,
            isInteractive: false,
            isSilent: true
        );
        $factory = new OutputFactory(config: $config);
        $output  = $factory->createEmptyOutput();
        $output2 = $factory->createEmptyOutput(ExitCode::AUTO_EXIT, new NewLine());

        self::assertInstanceOf(EmptyOutput::class, $output);
        self::assertInstanceOf(EmptyOutput::class, $output2);
        self::assertSame(ExitCode::SUCCESS, $output->getExitCode());
        self::assertTrue($output->isQuiet());
        self::assertFalse($output->isInteractive());
        self::assertTrue($output->isSilent());
        self::assertEmpty($output->getMessages());
        self::assertEmpty($output->getUnwrittenMessages());
        self::assertEmpty($output->getWrittenMessages());
        self::assertSame(ExitCode::AUTO_EXIT, $output2->getExitCode());
        self::assertCount(1, $output2->getMessages());
        self::assertCount(1, $output2->getUnwrittenMessages());
        self::assertEmpty($output2->getWrittenMessages());
        self::assertTrue($output2->isQuiet());
        self::assertFalse($output2->isInteractive());
        self::assertTrue($output2->isSilent());
    }

    public function testCreatePlainOutput(): void
    {
        $config  = new Config(
            isQuiet: true,
            isInteractive: false,
            isSilent: true
        );
        $factory = new OutputFactory(config: $config);
        $output  = $factory->createPlainOutput();
        $output2 = $factory->createPlainOutput(ExitCode::AUTO_EXIT, new NewLine());

        self::assertInstanceOf(PlainOutput::class, $output);
        self::assertInstanceOf(PlainOutput::class, $output2);
        self::assertSame(ExitCode::SUCCESS, $output->getExitCode());
        self::assertTrue($output->isQuiet());
        self::assertFalse($output->isInteractive());
        self::assertTrue($output->isSilent());
        self::assertEmpty($output->getMessages());
        self::assertEmpty($output->getUnwrittenMessages());
        self::assertEmpty($output->getWrittenMessages());
        self::assertSame(ExitCode::AUTO_EXIT, $output2->getExitCode());
        self::assertCount(1, $output2->getMessages());
        self::assertCount(1, $output2->getUnwrittenMessages());
        self::assertEmpty($output2->getWrittenMessages());
        self::assertTrue($output2->isQuiet());
        self::assertFalse($output2->isInteractive());
        self::assertTrue($output2->isSilent());
    }

    public function testCreateFileOutput(): void
    {
        $config  = new Config(
            isQuiet: true,
            isInteractive: false,
            isSilent: true
        );
        $factory = new OutputFactory(config: $config);
        $output  = $factory->createFileOutput('filepath');
        $output2 = $factory->createFileOutput('filepath2', ExitCode::AUTO_EXIT, new NewLine());

        self::assertInstanceOf(FileOutput::class, $output);
        self::assertInstanceOf(FileOutput::class, $output2);
        self::assertSame(ExitCode::SUCCESS, $output->getExitCode());
        self::assertSame('filepath', $output->getFilepath());
        self::assertTrue($output->isQuiet());
        self::assertFalse($output->isInteractive());
        self::assertTrue($output->isSilent());
        self::assertEmpty($output->getMessages());
        self::assertEmpty($output->getUnwrittenMessages());
        self::assertEmpty($output->getWrittenMessages());
        self::assertSame(ExitCode::AUTO_EXIT, $output2->getExitCode());
        self::assertSame('filepath2', $output2->getFilepath());
        self::assertCount(1, $output2->getMessages());
        self::assertCount(1, $output2->getUnwrittenMessages());
        self::assertEmpty($output2->getWrittenMessages());
        self::assertTrue($output2->isQuiet());
        self::assertFalse($output2->isInteractive());
        self::assertTrue($output2->isSilent());
    }

    public function testCreateStreamOutput(): void
    {
        $stream  = fopen(filename: 'php://input', mode: 'rb');
        $stream2 = fopen(filename: 'php://memory', mode: 'rb');

        $config  = new Config(
            isQuiet: true,
            isInteractive: false,
            isSilent: true
        );
        $factory = new OutputFactory(config: $config);
        $output  = $factory->createStreamOutput($stream);
        $output2 = $factory->createStreamOutput($stream2, ExitCode::AUTO_EXIT, new NewLine());

        self::assertInstanceOf(StreamOutput::class, $output);
        self::assertInstanceOf(StreamOutput::class, $output2);
        self::assertSame(ExitCode::SUCCESS, $output->getExitCode());
        self::assertSame($stream, $output->getStream());
        self::assertTrue($output->isQuiet());
        self::assertFalse($output->isInteractive());
        self::assertTrue($output->isSilent());
        self::assertEmpty($output->getMessages());
        self::assertEmpty($output->getUnwrittenMessages());
        self::assertEmpty($output->getWrittenMessages());
        self::assertSame(ExitCode::AUTO_EXIT, $output2->getExitCode());
        self::assertSame($stream2, $output2->getStream());
        self::assertCount(1, $output2->getMessages());
        self::assertCount(1, $output2->getUnwrittenMessages());
        self::assertEmpty($output2->getWrittenMessages());
        self::assertTrue($output2->isQuiet());
        self::assertFalse($output2->isInteractive());
        self::assertTrue($output2->isSilent());
    }
}
