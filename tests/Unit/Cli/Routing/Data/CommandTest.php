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

namespace Valkyrja\Tests\Unit\Cli\Routing\Data;

use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Routing\Data\Route;
use Valkyrja\Dispatcher\Data\MethodDispatch as DefaultDispatch;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the Command class.
 *
 * @author Melech Mizrachi
 */
class CommandTest extends TestCase
{
    public function testDefaults(): void
    {
        $command = new Route(
            name: 'test-command',
            description: 'Test Command',
            helpText: new Message('Help text'),
            dispatch: new DefaultDispatch(self::class, 'dispatch')
        );

        self::assertSame('test-command', $command->getName());
        self::assertSame('Test Command', $command->getDescription());
        self::assertInstanceOf(Message::class, $command->getHelpText());
        self::assertEmpty($command->getArguments());
        self::assertEmpty($command->getOptions());
        self::assertEmpty($command->getCommandMatchedMiddleware());
        self::assertEmpty($command->getCommandDispatchedMiddleware());
        self::assertEmpty($command->getThrowableCaughtMiddleware());
        self::assertEmpty($command->getExitedMiddleware());
    }
}
