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

namespace Valkyrja\Tests\Unit\Cli\Interaction\Factory;

use Valkyrja\Cli\Interaction\Enum\ExitCode;
use Valkyrja\Cli\Interaction\Factory\OutputFactory;
use Valkyrja\Cli\Interaction\Message\NewLine;
use Valkyrja\Cli\Interaction\Output\EmptyOutput;
use Valkyrja\Cli\Interaction\Output\FileOutput;
use Valkyrja\Cli\Interaction\Output\Output;
use Valkyrja\Cli\Interaction\Output\PlainOutput;
use Valkyrja\Cli\Interaction\Output\StreamOutput;
use Valkyrja\Tests\Unit\TestCase;

use function fopen;

/**
 * Test the OutputFactory class.
 *
 * @author Melech Mizrachi
 */
class OutputFactoryTest extends TestCase
{
    public function testCreateOutput(): void
    {
        $factory = new OutputFactory();
        $output  = $factory->createOutput();
        $output2 = $factory->createOutput(ExitCode::AUTO_EXIT, new NewLine());

        self::assertInstanceOf(Output::class, $output);
        self::assertInstanceOf(Output::class, $output2);
        self::assertSame(ExitCode::SUCCESS, $output->getExitCode());
        self::assertEmpty($output->getMessages());
        self::assertEmpty($output->getUnwrittenMessages());
        self::assertEmpty($output->getWrittenMessages());
        self::assertSame(ExitCode::AUTO_EXIT, $output2->getExitCode());
        self::assertCount(1, $output2->getMessages());
        self::assertCount(1, $output2->getUnwrittenMessages());
        self::assertEmpty($output2->getWrittenMessages());
    }

    public function testCreateEmptyOutput(): void
    {
        $factory = new OutputFactory();
        $output  = $factory->createEmptyOutput();
        $output2 = $factory->createEmptyOutput(ExitCode::AUTO_EXIT, new NewLine());

        self::assertInstanceOf(EmptyOutput::class, $output);
        self::assertInstanceOf(EmptyOutput::class, $output2);
        self::assertSame(ExitCode::SUCCESS, $output->getExitCode());
        self::assertEmpty($output->getMessages());
        self::assertEmpty($output->getUnwrittenMessages());
        self::assertEmpty($output->getWrittenMessages());
        self::assertSame(ExitCode::AUTO_EXIT, $output2->getExitCode());
        self::assertCount(1, $output2->getMessages());
        self::assertCount(1, $output2->getUnwrittenMessages());
        self::assertEmpty($output2->getWrittenMessages());
    }

    public function testCreatePlainOutput(): void
    {
        $factory = new OutputFactory();
        $output  = $factory->createPlainOutput();
        $output2 = $factory->createPlainOutput(ExitCode::AUTO_EXIT, new NewLine());

        self::assertInstanceOf(PlainOutput::class, $output);
        self::assertInstanceOf(PlainOutput::class, $output2);
        self::assertSame(ExitCode::SUCCESS, $output->getExitCode());
        self::assertEmpty($output->getMessages());
        self::assertEmpty($output->getUnwrittenMessages());
        self::assertEmpty($output->getWrittenMessages());
        self::assertSame(ExitCode::AUTO_EXIT, $output2->getExitCode());
        self::assertCount(1, $output2->getMessages());
        self::assertCount(1, $output2->getUnwrittenMessages());
        self::assertEmpty($output2->getWrittenMessages());
    }

    public function testCreateFileOutput(): void
    {
        $factory = new OutputFactory();
        $output  = $factory->createFileOutput('filepath');
        $output2 = $factory->createFileOutput('filepath2', ExitCode::AUTO_EXIT, new NewLine());

        self::assertInstanceOf(FileOutput::class, $output);
        self::assertInstanceOf(FileOutput::class, $output2);
        self::assertSame(ExitCode::SUCCESS, $output->getExitCode());
        self::assertSame('filepath', $output->getFilepath());
        self::assertEmpty($output->getMessages());
        self::assertEmpty($output->getUnwrittenMessages());
        self::assertEmpty($output->getWrittenMessages());
        self::assertSame(ExitCode::AUTO_EXIT, $output2->getExitCode());
        self::assertSame('filepath2', $output2->getFilepath());
        self::assertCount(1, $output2->getMessages());
        self::assertCount(1, $output2->getUnwrittenMessages());
        self::assertEmpty($output2->getWrittenMessages());
    }

    public function testCreateStreamOutput(): void
    {
        $stream  = fopen('php://input', 'rb');
        $stream2 = fopen('php://memory', 'rb');

        $factory = new OutputFactory();
        $output  = $factory->createStreamOutput($stream);
        $output2 = $factory->createStreamOutput($stream2, ExitCode::AUTO_EXIT, new NewLine());

        self::assertInstanceOf(StreamOutput::class, $output);
        self::assertInstanceOf(StreamOutput::class, $output2);
        self::assertSame(ExitCode::SUCCESS, $output->getExitCode());
        self::assertSame($stream, $output->getStream());
        self::assertEmpty($output->getMessages());
        self::assertEmpty($output->getUnwrittenMessages());
        self::assertEmpty($output->getWrittenMessages());
        self::assertSame(ExitCode::AUTO_EXIT, $output2->getExitCode());
        self::assertSame($stream2, $output2->getStream());
        self::assertCount(1, $output2->getMessages());
        self::assertCount(1, $output2->getUnwrittenMessages());
        self::assertEmpty($output2->getWrittenMessages());
    }
}
