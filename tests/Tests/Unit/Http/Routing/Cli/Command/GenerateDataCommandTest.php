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
use Valkyrja\Cli\Interaction\Provider\CliInteractionComponentProvider;
use Valkyrja\Cli\Middleware\Provider\CliMiddlewareComponentProvider;
use Valkyrja\Cli\Routing\Provider\CliRoutingComponentProvider;
use Valkyrja\Cli\Server\Provider\CliServerComponentProvider;
use Valkyrja\Container\Provider\ContainerComponentProvider;
use Valkyrja\Dispatch\Provider\DispatchComponentProvider;
use Valkyrja\Event\Provider\EventComponentProvider;
use Valkyrja\Http\Message\Provider\HttpMessageComponentProvider;
use Valkyrja\Http\Middleware\Provider\HttpMiddlewareComponentProvider;
use Valkyrja\Http\Routing\Cli\Command\GenerateDataCommand;
use Valkyrja\Http\Routing\Provider\HttpRoutingComponentProvider;
use Valkyrja\Http\Server\Provider\HttpServerComponentProvider;
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
            dir: $originalPath,
            providers: [
                ContainerComponentProvider::class,
                DispatchComponentProvider::class,
                CliInteractionComponentProvider::class,
                CliMiddlewareComponentProvider::class,
                CliRoutingComponentProvider::class,
                CliServerComponentProvider::class,
                EventComponentProvider::class,
                HttpMessageComponentProvider::class,
                HttpMiddlewareComponentProvider::class,
                HttpRoutingComponentProvider::class,
                HttpServerComponentProvider::class,
            ],
        );
        $output        = new PlainOutput();
        $outputFactory = $this->createMock(OutputFactoryContract::class);

        $containerDataPath = Directory::srcPath($config->dataPath . '/AppContainerData.php');
        $eventDataPath     = Directory::srcPath($config->dataPath . '/AppEventData.php');
        $cliDataPath       = Directory::srcPath($config->dataPath . '/AppCliRoutingData.php');
        $httpDataPath      = Directory::srcPath($config->dataPath . '/AppHttpRoutingData.php');

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
        self::assertFileExists($cliDataPath);
        self::assertFileExists($httpDataPath);

        self::assertStringContainsString('Generating Http Component Data:', $contents);
        self::assertStringContainsString('Generating Container Data......................Success', $contents);
        self::assertStringContainsString('Generating Event Data..........................Success', $contents);
        self::assertStringContainsString('Generating Http Routes Data....................Success', $contents);
        self::assertStringContainsString('Generating Cli Routes Data.....................Success', $contents);

        $expectedOutput = <<<'TEXT'

            Generating Http Component Data:

            Generating Container Data......................Success

            Generating Event Data..........................Success

            Generating Cli Routes Data.....................Success

            Generating Http Routes Data....................Success


            TEXT;

        self::assertSame($expectedOutput, $contents);

        ob_start();
        $command->run();
        $contents = ob_get_clean();

        self::assertFileExists($containerDataPath);
        self::assertFileExists($eventDataPath);
        self::assertFileExists($cliDataPath);
        self::assertFileExists($httpDataPath);

        self::assertStringContainsString('Generating Http Component Data:', $contents);
        self::assertStringContainsString('Generating Container Data......................Skipped', $contents);
        self::assertStringContainsString('Generating Event Data..........................Skipped', $contents);
        self::assertStringContainsString('Generating Http Routes Data....................Skipped', $contents);
        self::assertStringContainsString('Generating Cli Routes Data.....................Skipped', $contents);

        $expectedOutput = <<<'TEXT'

            Generating Http Component Data:

            Generating Container Data......................Skipped

            Generating Event Data..........................Skipped

            Generating Cli Routes Data.....................Skipped

            Generating Http Routes Data....................Skipped


            TEXT;

        self::assertSame($expectedOutput, $contents);

        $config = new HttpConfig(
            dir: '/non-existent-dir',
            providers: [
                ContainerComponentProvider::class,
                DispatchComponentProvider::class,
                CliInteractionComponentProvider::class,
                CliMiddlewareComponentProvider::class,
                CliRoutingComponentProvider::class,
                CliServerComponentProvider::class,
                EventComponentProvider::class,
                HttpMessageComponentProvider::class,
                HttpMiddlewareComponentProvider::class,
                HttpRoutingComponentProvider::class,
                HttpServerComponentProvider::class,
            ],
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
        self::assertFileExists($cliDataPath);
        self::assertFileExists($httpDataPath);

        self::assertStringContainsString('Generating Http Component Data:', $contents);
        self::assertStringContainsString('Generating Container Data......................Failed', $contents);
        self::assertStringContainsString('Generating Event Data..........................Failed', $contents);
        self::assertStringContainsString('Generating Http Routes Data....................Failed', $contents);
        self::assertStringContainsString('Generating Cli Routes Data.....................Failed', $contents);

        $expectedOutput = <<<'TEXT'

            Generating Http Component Data:

            Generating Container Data......................Failed

            Generating Event Data..........................Failed

            Generating Cli Routes Data.....................Failed

            Generating Http Routes Data....................Failed


            TEXT;

        self::assertSame($expectedOutput, $contents);

        Directory::$basePath = $originalPath;

        @unlink($containerDataPath);
        @unlink($eventDataPath);
        @unlink($cliDataPath);
        @unlink($httpDataPath);
    }

    public function testRunWithoutEventAndCli(): void
    {
        $originalPath = Directory::$basePath;

        $env           = new Env();
        $config        = new HttpConfig(
            dir: $originalPath,
            providers: [
                ContainerComponentProvider::class,
                DispatchComponentProvider::class,
                HttpMessageComponentProvider::class,
                HttpMiddlewareComponentProvider::class,
                HttpRoutingComponentProvider::class,
                HttpServerComponentProvider::class,
            ],
        );
        $output        = new PlainOutput();
        $outputFactory = $this->createMock(OutputFactoryContract::class);

        $containerDataPath = Directory::srcPath($config->dataPath . '/AppContainerData.php');
        $eventDataPath     = Directory::srcPath($config->dataPath . '/AppEventData.php');
        $cliDataPath       = Directory::srcPath($config->dataPath . '/AppCliRoutingData.php');
        $httpDataPath      = Directory::srcPath($config->dataPath . '/AppHttpRoutingData.php');

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
        self::assertFileDoesNotExist($eventDataPath);
        self::assertFileDoesNotExist($cliDataPath);
        self::assertFileExists($httpDataPath);

        self::assertStringContainsString('Generating Http Component Data:', $contents);
        self::assertStringContainsString('Generating Container Data......................Success', $contents);
        self::assertStringNotContainsString('Generating Event Data..........................Success', $contents);
        self::assertStringContainsString('Generating Http Routes Data....................Success', $contents);
        self::assertStringNotContainsString('Generating Cli Routes Data.....................Success', $contents);

        $expectedOutput = <<<'TEXT'

            Generating Http Component Data:

            Generating Container Data......................Success

            Generating Http Routes Data....................Success


            TEXT;

        self::assertSame($expectedOutput, $contents);

        ob_start();
        $command->run();
        $contents = ob_get_clean();

        self::assertFileExists($containerDataPath);
        self::assertFileDoesNotExist($eventDataPath);
        self::assertFileDoesNotExist($cliDataPath);
        self::assertFileExists($httpDataPath);

        self::assertStringContainsString('Generating Http Component Data:', $contents);
        self::assertStringContainsString('Generating Container Data......................Skipped', $contents);
        self::assertStringNotContainsString('Generating Event Data..........................Skipped', $contents);
        self::assertStringContainsString('Generating Http Routes Data....................Skipped', $contents);
        self::assertStringNotContainsString('Generating Cli Routes Data.....................Skipped', $contents);

        $expectedOutput = <<<'TEXT'

            Generating Http Component Data:

            Generating Container Data......................Skipped

            Generating Http Routes Data....................Skipped


            TEXT;

        self::assertSame($expectedOutput, $contents);

        $config = new HttpConfig(
            dir: '/non-existent-dir',
            providers: [
                ContainerComponentProvider::class,
                DispatchComponentProvider::class,
                HttpMessageComponentProvider::class,
                HttpMiddlewareComponentProvider::class,
                HttpRoutingComponentProvider::class,
                HttpServerComponentProvider::class,
            ],
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
        self::assertFileDoesNotExist($eventDataPath);
        self::assertFileDoesNotExist($cliDataPath);
        self::assertFileExists($httpDataPath);

        self::assertStringContainsString('Generating Http Component Data:', $contents);
        self::assertStringContainsString('Generating Container Data......................Failed', $contents);
        self::assertStringNotContainsString('Generating Event Data..........................Failed', $contents);
        self::assertStringContainsString('Generating Http Routes Data....................Failed', $contents);
        self::assertStringNotContainsString('Generating Cli Routes Data.....................Failed', $contents);

        $expectedOutput = <<<'TEXT'

            Generating Http Component Data:

            Generating Container Data......................Failed

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
