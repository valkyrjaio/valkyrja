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

namespace Valkyrja\Tests\Unit\Http\Routing\Cli\Command;

use Valkyrja\Application\Data\HttpConfig;
use Valkyrja\Application\Directory\Directory;
use Valkyrja\Application\Env\Env;
use Valkyrja\Cli\Interaction\Output\Factory\Contract\OutputFactoryContract;
use Valkyrja\Cli\Interaction\Output\PlainOutput;
use Valkyrja\Http\Routing\Cli\Command\GenerateDataCommand;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class GenerateDataCommandTest extends TestCase
{
    public function testHelp(): void
    {
        $message = GenerateDataCommand::help();

        self::assertSame('A command to generate all data classes for the Http component.', $message->getText());
    }

    public function testRun(): void
    {
        $originalPath = Directory::$basePath;

        $env           = new Env();
        $config        = new HttpConfig(
            dir: $originalPath
        );
        $output        = new PlainOutput();
        $outputFactory = $this->createMock(OutputFactoryContract::class);

        $containerDataPath = Directory::srcPath($config->dataPath . '/ContainerData.php');
        $eventDataPath     = Directory::srcPath($config->dataPath . '/EventData.php');
        $cliDataPath       = Directory::srcPath($config->dataPath . '/CliRoutingData.php');
        $httpDataPath      = Directory::srcPath($config->dataPath . '/HttpRoutingData.php');

        @unlink($containerDataPath);
        @unlink($eventDataPath);
        @unlink($cliDataPath);
        @unlink($httpDataPath);

        $outputFactory->expects($this->exactly(2))
            ->method('createOutput')
            ->willReturn($output);

        $command = new GenerateDataCommand(
            env: $env,
            config: $config,
            outputFactory: $outputFactory,
        );

        ob_start();
        $command->run();
        $contents = ob_get_clean();

        self::assertFileExists($containerDataPath);
        self::assertFileExists($eventDataPath);
        self::assertFileDoesNotExist($cliDataPath);
        self::assertFileExists($httpDataPath);

        self::assertStringContainsString('Generating Data:', $contents);
        self::assertStringContainsString('Generating Container Data......................Success', $contents);
        self::assertStringContainsString('Generating Event Data..........................Success', $contents);
        self::assertStringContainsString('Generating Http Routes Data....................Success', $contents);
        self::assertStringNotContainsString('Generating Cli Routes Data.....................Success', $contents);

        $expectedOutput = <<<'TEXT'

            Generating Data:

            Generating Container Data......................Success

            Generating Event Data..........................Success

            Generating Http Routes Data....................Success


            TEXT;

        self::assertSame($expectedOutput, $contents);

        ob_start();
        $command->run();
        $contents = ob_get_clean();

        self::assertFileExists($containerDataPath);
        self::assertFileExists($eventDataPath);
        self::assertFileDoesNotExist($cliDataPath);
        self::assertFileExists($httpDataPath);

        self::assertStringContainsString('Generating Data:', $contents);
        self::assertStringContainsString('Generating Container Data......................Skipped', $contents);
        self::assertStringContainsString('Generating Event Data..........................Skipped', $contents);
        self::assertStringContainsString('Generating Http Routes Data....................Skipped', $contents);
        self::assertStringNotContainsString('Generating Cli Routes Data.....................Skipped', $contents);

        $expectedOutput = <<<'TEXT'

            Generating Data:

            Generating Container Data......................Skipped

            Generating Event Data..........................Skipped

            Generating Http Routes Data....................Skipped


            TEXT;

        self::assertSame($expectedOutput, $contents);

        $config = new HttpConfig(
            dir: '/non-existent-dir'
        );

        Directory::$basePath = '/non-existent-dir';

        $outputFactory = $this->createMock(OutputFactoryContract::class);

        $outputFactory->expects($this->once())
            ->method('createOutput')
            ->willReturn($output);

        $command = new GenerateDataCommand(
            env: $env,
            config: $config,
            outputFactory: $outputFactory,
        );

        ob_start();
        // We expect warnings here due to the non-existent directory
        @$command->run();
        $contents = ob_get_clean();

        self::assertFileExists($containerDataPath);
        self::assertFileExists($eventDataPath);
        self::assertFileDoesNotExist($cliDataPath);
        self::assertFileExists($httpDataPath);

        self::assertStringContainsString('Generating Data:', $contents);
        self::assertStringContainsString('Generating Container Data......................Failed', $contents);
        self::assertStringContainsString('Generating Event Data..........................Failed', $contents);
        self::assertStringContainsString('Generating Http Routes Data....................Failed', $contents);
        self::assertStringNotContainsString('Generating Cli Routes Data.....................Failed', $contents);

        $expectedOutput = <<<'TEXT'

            Generating Data:

            Generating Container Data......................Failed

            Generating Event Data..........................Failed

            Generating Http Routes Data....................Failed


            TEXT;

        self::assertSame($expectedOutput, $contents);

        Directory::$basePath = $originalPath;

        @unlink($containerDataPath);
        @unlink($eventDataPath);
        @unlink($cliDataPath);
        @unlink($httpDataPath);
    }
}
