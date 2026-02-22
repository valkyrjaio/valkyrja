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

namespace Valkyrja\Tests\Unit\Application\Entry;

use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Valkyrja\Tests\Classes\Application\Entry\CliClass;
use Valkyrja\Tests\EnvClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the App service.
 */
#[RunTestsInSeparateProcesses]
final class CliTest extends TestCase
{
    public function testGetInputDefaults(): void
    {
        $_SERVER['argv'] = [];

        $input = CliClass::getInputExposed(new EnvClass());

        self::assertSame('valkyrja', $input->getCaller());
        self::assertSame('list', $input->getCommandName());
        self::assertEmpty($input->getArguments());
        self::assertEmpty($input->getOptions());
    }

    public function testGetInputWithCustomApplicationName(): void
    {
        $_SERVER['argv'] = [];

        $input = CliClass::getInputExposed(new class extends EnvClass {
            /** @var non-empty-string */
            public const string APP_CLI_DEFAULT_APPLICATION_NAME = 'test';
        });

        self::assertSame('test', $input->getCaller());
        self::assertSame('list', $input->getCommandName());
        self::assertEmpty($input->getArguments());
        self::assertEmpty($input->getOptions());
    }

    public function testGetInputWithCustomCommandName(): void
    {
        $_SERVER['argv'] = [];

        $input = CliClass::getInputExposed(new class extends EnvClass {
            /** @var non-empty-string */
            public const string APP_CLI_DEFAULT_COMMAND_NAME = 'test';
        });

        self::assertSame('valkyrja', $input->getCaller());
        self::assertSame('test', $input->getCommandName());
        self::assertEmpty($input->getArguments());
        self::assertEmpty($input->getOptions());
    }

    public function testGetInputFromGlobals(): void
    {
        $_SERVER['argv'] = [
            'cli',
            'command',
            '-t',
            '-v=value',
            '--value',
            '--value2=test',
            'argument',
            'argument2',
        ];

        $input = CliClass::getInputExposed(new EnvClass());

        self::assertSame('cli', $input->getCaller());
        self::assertSame('command', $input->getCommandName());
        self::assertCount(2, $input->getArguments());
        self::assertCount(4, $input->getOptions());
    }
}
