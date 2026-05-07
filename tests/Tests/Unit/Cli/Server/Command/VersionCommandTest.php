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
use Valkyrja\Application\Data\CliConfig;
use Valkyrja\Cli\Interaction\Message\Header;
use Valkyrja\Cli\Interaction\Output\Factory\Contract\OutputFactoryContract;
use Valkyrja\Cli\Interaction\Output\PlainOutput;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;
use Valkyrja\Cli\Server\Command\VersionCommand;
use Valkyrja\Tests\Unit\Abstract\TestCase;

use const PHP_VERSION;

use function ob_get_clean;
use function ob_start;

final class VersionCommandTest extends TestCase
{
    private function makeRoute(bool $isShort = false, bool $isPlain = false): RouteContract
    {
        $route = $this->createMock(RouteContract::class);

        $route->method('hasOption')
            ->willReturnMap([
                ['short', $isShort],
                ['plain', $isPlain],
            ]);

        if (! $isShort && ! $isPlain) {
            $route->method('getDescription')->willReturn('Get the application version');
            $route->method('getName')->willReturn('version');
        }

        return $route;
    }

    private function makeOutputFactory(): OutputFactoryContract
    {
        $outputFactory = $this->createMock(OutputFactoryContract::class);
        $outputFactory->method('createOutput')->willReturn(new PlainOutput());

        return $outputFactory;
    }

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
}
