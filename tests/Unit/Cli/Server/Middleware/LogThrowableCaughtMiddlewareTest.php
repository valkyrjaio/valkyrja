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

namespace Valkyrja\Tests\Unit\Cli\Server\Middleware;

use Valkyrja\Cli\Interaction\Input\Input;
use Valkyrja\Cli\Interaction\Output\Output;
use Valkyrja\Cli\Middleware\Handler\ThrowableCaughtHandler;
use Valkyrja\Cli\Server\Middleware\LogThrowableCaughtMiddleware;
use Valkyrja\Log\Logger\Contract\LoggerContract;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Throwable\Exception\Exception;

class LogThrowableCaughtMiddlewareTest extends TestCase
{
    public function testThrowableCaught(): void
    {
        $input       = new Input(commandName: 'test');
        $output      = new Output();
        $exception   = new Exception();
        $commandName = $input->getCommandName();

        $logger = $this->createMock(LoggerContract::class);
        $logger->expects($this->once())
               ->method('throwable')
               ->with(
                   self::equalTo($exception),
                   self::equalTo("Cli Server Error\nUrl: $commandName"),
               );

        $handler = $this->createMock(ThrowableCaughtHandler::class);
        $handler->expects($this->once())
                ->method('throwableCaught')
                ->with(
                    self::equalTo($input),
                    self::equalTo($output),
                    self::equalTo($exception),
                )
                ->willReturn($output);

        $middleware = new LogThrowableCaughtMiddleware(logger: $logger);

        $outputAfterMiddleware = $middleware->throwableCaught($input, $output, $exception, $handler);

        self::assertSame($output, $outputAfterMiddleware);
    }
}
