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

namespace Valkyrja\Tests\Unit\Cli\Interaction\Message;

use Valkyrja\Application\Constant\ApplicationInfo;
use Valkyrja\Cli\Interaction\Message\Header;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;
use Valkyrja\Tests\Unit\Abstract\TestCase;

use function getcwd;

use const PHP_VERSION;

/**
 * Test the Header class.
 */
final class HeaderTest extends TestCase
{
    public function testDefaults(): void
    {
        $appName     = 'TestApp';
        $appVersion  = '2.0.0';
        $description = 'Run the test command';
        $name        = 'test:run';

        $header = new Header($appName, $appVersion, $this->makeRoute($description, $name));
        $text   = $header->getText();

        self::assertStringContainsString("╭── $appName v$appVersion", $text);
        self::assertStringContainsString('│   ▗▄▄▖     ▗▄▄▖', $text);
        self::assertStringContainsString('│   Built on Valkyrja v' . ApplicationInfo::VERSION, $text);
        self::assertStringContainsString('│   Running on PHP ' . PHP_VERSION, $text);
        self::assertStringContainsString('│   ' . (string) getcwd(), $text);
        self::assertStringContainsString("╰── $description · $name", $text);
    }

    public function testExplicitSlots(): void
    {
        $appName           = 'MyApp';
        $appVersion        = '3.1.0';
        $icon              = 'custom-icon';
        $valkyrjaVersion   = '99.0.0';
        $valkyrjaBuildDate = 'January 1 2030';
        $phpVersion        = '9.0.0';
        $projectRoot       = '/custom/path';
        $actionDescription = 'Custom action';
        $commandName       = 'custom:cmd';

        $header = new Header(
            appName: $appName,
            appVersion: $appVersion,
            route: $this->makeRoute(expectDescriptionCall: false, expectNameCall: false),
            icon: $icon,
            valkyrjaVersion: $valkyrjaVersion,
            valkyrjaBuildDate: $valkyrjaBuildDate,
            phpVersion: $phpVersion,
            projectRoot: $projectRoot,
            actionDescription: $actionDescription,
            commandName: $commandName,
        );
        $text = $header->getText();

        self::assertStringContainsString("╭── $appName v$appVersion", $text);
        self::assertStringContainsString('│   ' . $icon, $text);
        self::assertStringContainsString("│   Built on Valkyrja v$valkyrjaVersion (date: $valkyrjaBuildDate)", $text);
        self::assertStringContainsString("│   Running on PHP $phpVersion", $text);
        self::assertStringContainsString("│   $projectRoot", $text);
        self::assertStringContainsString("╰── $actionDescription · $commandName", $text);
    }

    public function testWithAppName(): void
    {
        $original = new Header('OldApp', '1.0.0', $this->makeRoute());
        $updated  = $original->withAppName('NewApp');

        self::assertNotSame($original, $updated);
        self::assertStringContainsString('╭── OldApp v1.0.0', $original->getText());
        self::assertStringContainsString('╭── NewApp v1.0.0', $updated->getText());
    }

    public function testWithAppVersion(): void
    {
        $original = new Header('App', '1.0.0', $this->makeRoute());
        $updated  = $original->withAppVersion('2.0.0');

        self::assertNotSame($original, $updated);
        self::assertStringContainsString('╭── App v1.0.0', $original->getText());
        self::assertStringContainsString('╭── App v2.0.0', $updated->getText());
    }

    public function testWithIcon(): void
    {
        $original = new Header('App', '1.0.0', $this->makeRoute(), icon: 'old-icon');
        $updated  = $original->withIcon('new-icon');

        self::assertNotSame($original, $updated);
        self::assertStringContainsString('│   old-icon', $original->getText());
        self::assertStringContainsString('│   new-icon', $updated->getText());
    }

    public function testWithValkyrjaVersion(): void
    {
        $original = new Header('App', '1.0.0', $this->makeRoute(), valkyrjaVersion: '10.0.0');
        $updated  = $original->withValkyrjaVersion('20.0.0');

        self::assertNotSame($original, $updated);
        self::assertStringContainsString('Built on Valkyrja v10.0.0', $original->getText());
        self::assertStringContainsString('Built on Valkyrja v20.0.0', $updated->getText());
    }

    public function testWithValkyrjaBuildDate(): void
    {
        $original = new Header('App', '1.0.0', $this->makeRoute(), valkyrjaBuildDate: 'Jan 1 2025');
        $updated  = $original->withValkyrjaBuildDate('Jan 1 2030');

        self::assertNotSame($original, $updated);
        self::assertStringContainsString('(date: Jan 1 2025)', $original->getText());
        self::assertStringContainsString('(date: Jan 1 2030)', $updated->getText());
    }

    public function testWithPhpVersion(): void
    {
        $original = new Header('App', '1.0.0', $this->makeRoute(), phpVersion: '8.4.0');
        $updated  = $original->withPhpVersion('9.0.0');

        self::assertNotSame($original, $updated);
        self::assertStringContainsString('Running on PHP 8.4.0', $original->getText());
        self::assertStringContainsString('Running on PHP 9.0.0', $updated->getText());
    }

    public function testWithProjectRoot(): void
    {
        $original = new Header('App', '1.0.0', $this->makeRoute(), projectRoot: '/old/path');
        $updated  = $original->withProjectRoot('/new/path');

        self::assertNotSame($original, $updated);
        self::assertStringContainsString('│   /old/path', $original->getText());
        self::assertStringContainsString('│   /new/path', $updated->getText());
    }

    public function testWithActionDescription(): void
    {
        $original = new Header('App', '1.0.0', $this->makeRoute(expectDescriptionCall: false), actionDescription: 'Old action');
        $updated  = $original->withActionDescription('New action');

        self::assertNotSame($original, $updated);
        self::assertStringContainsString('╰── Old action ·', $original->getText());
        self::assertStringContainsString('╰── New action ·', $updated->getText());
    }

    public function testWithCommandName(): void
    {
        $original = new Header('App', '1.0.0', $this->makeRoute(expectNameCall: false), commandName: 'old:cmd');
        $updated  = $original->withCommandName('new:cmd');

        self::assertNotSame($original, $updated);
        self::assertStringContainsString('· old:cmd', $original->getText());
        self::assertStringContainsString('· new:cmd', $updated->getText());
    }

    public function testProjectRootFallsBackToGetcwd(): void
    {
        $header = new Header('App', '1.0.0', $this->makeRoute());

        self::assertStringContainsString('│   ' . (string) getcwd(), $header->getText());
    }

    public function testActionDescriptionAndCommandNameFromRoute(): void
    {
        $description = 'Specific description';
        $name        = 'specific:name';

        $header = new Header('App', '1.0.0', $this->makeRoute($description, $name));

        self::assertStringContainsString("╰── $description · $name", $header->getText());
    }

    private function makeRoute(
        string $description = 'Run command',
        string $name = 'cmd',
        bool $expectDescriptionCall = true,
        bool $expectNameCall = true,
    ): RouteContract {
        $route = $this->createMock(RouteContract::class);

        $route->expects($expectDescriptionCall ? $this->once() : $this->never())
            ->method('getDescription')
            ->willReturn($description);

        $route->expects($expectNameCall ? $this->once() : $this->never())
            ->method('getName')
            ->willReturn($name);

        return $route;
    }
}
