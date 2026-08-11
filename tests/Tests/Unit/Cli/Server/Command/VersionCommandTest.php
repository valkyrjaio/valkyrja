<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Cli\Server\Command;

use Valkyrja\Application\Constant\ApplicationInfo;
use Valkyrja\Application\Data\CliConfig;
use Valkyrja\Cli\Interaction\Message\Header;
use Valkyrja\Cli\Interaction\Option\Option;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Interaction\Output\Factory\Contract\OutputFactoryContract;
use Valkyrja\Cli\Interaction\Output\PlainOutput;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;
use Valkyrja\Cli\Routing\Data\OptionParameter;
use Valkyrja\Cli\Routing\Data\Route;
use Valkyrja\Cli\Server\Command\VersionCommand;
use Valkyrja\Tests\Unit\Abstract\TestCase;

use function ob_get_clean;
use function ob_start;

use const PHP_VERSION;

final class VersionCommandTest extends TestCase
{
    public function testRun(): void
    {
        $appName    = 'TestApp';
        $appVersion = '2.0.0';

        $command = new VersionCommand(
            $this->makeOutputFactory(),
            new CliConfig(namespace: $appName, version: $appVersion),
            $this->makeRoute(),
        );
        $output  = $command->run();

        self::assertInstanceOf(Header::class, $output->getMessages()[0]);

        ob_start();
        $output->writeMessages();
        $obOutput = ob_get_clean();

        self::assertStringContainsString("╭── $appName v$appVersion", $obOutput);
        self::assertStringContainsString('│   Built on Valkyrja v' . ApplicationInfo::VERSION, $obOutput);
        self::assertStringContainsString('│   Running on PHP ' . PHP_VERSION, $obOutput);
    }

    public function testRunShort(): void
    {
        $appVersion = '3.0.0';

        $command = new VersionCommand(
            $this->makeOutputFactory(),
            new CliConfig(namespace: 'App', version: $appVersion),
            $this->makeRoute(isShort: true),
        );
        $output  = $command->run();

        ob_start();
        $output->writeMessages();
        $obOutput = ob_get_clean();

        self::assertStringContainsString($appVersion, $obOutput);
        self::assertStringNotContainsString('╭──', $obOutput);
        self::assertStringNotContainsString('Built on Valkyrja', $obOutput);
    }

    public function testRunPlain(): void
    {
        $appName    = 'PlainApp';
        $appVersion = '4.0.0';

        $command = new VersionCommand(
            $this->makeOutputFactory(),
            new CliConfig(namespace: $appName, version: $appVersion),
            $this->makeRoute(isPlain: true),
        );
        $output  = $command->run();

        ob_start();
        $output->writeMessages();
        $obOutput = ob_get_clean();

        self::assertStringContainsString("$appName v$appVersion", $obOutput);
        self::assertStringContainsString('Built on Valkyrja v' . ApplicationInfo::VERSION, $obOutput);
        self::assertStringContainsString('Running on PHP ' . PHP_VERSION, $obOutput);
        self::assertStringNotContainsString('╭──', $obOutput);
    }

    public function testHelp(): void
    {
        $text = 'A command to show the application version and info.';

        self::assertSame($text, VersionCommand::help()->getText());
        self::assertSame($text, VersionCommand::help()->getFormattedText());
    }

    /**
     * A route that declares both options, and carries only the ones spelled out.
     */
    private function makeRoute(bool $isShort = false, bool $isPlain = false): RouteContract
    {
        $short = new OptionParameter(name: 'short', description: 'Output the version number only');
        $plain = new OptionParameter(name: 'plain', description: 'Output version info without the banner');

        if ($isShort) {
            $short = $short->withOptions(new Option('short'));
        }

        if ($isPlain) {
            $plain = $plain->withOptions(new Option('plain'));
        }

        return new Route(
            name: 'version',
            description: 'Get the application version',
            handler: static fn (): OutputContract => new PlainOutput(),
            options: [$short, $plain],
        );
    }

    private function makeOutputFactory(): OutputFactoryContract
    {
        $outputFactory = $this->createMock(OutputFactoryContract::class);
        $outputFactory->expects($this->once())->method('createOutput')->willReturn(new PlainOutput());

        return $outputFactory;
    }
}
