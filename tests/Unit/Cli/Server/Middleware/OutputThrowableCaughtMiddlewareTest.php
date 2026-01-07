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
use Valkyrja\Cli\Interaction\Message\NewLine;
use Valkyrja\Cli\Interaction\Output\Output;
use Valkyrja\Cli\Middleware\Handler\ThrowableCaughtHandler;
use Valkyrja\Cli\Server\Middleware\OutputThrowableCaughtMiddleware;
use Valkyrja\Tests\Unit\TestCase;
use Valkyrja\Throwable\Exception\Exception;

class OutputThrowableCaughtMiddlewareTest extends TestCase
{
    public function testThrowableCaught(): void
    {
        $input       = new Input(commandName: 'test');
        $output      = new Output();
        $exception   = new Exception();
        $commandName = $input->getCommandName();

        $handler = $this->createMock(ThrowableCaughtHandler::class);
        $handler->expects($this->once())
                ->method('throwableCaught')
                ->with(
                    self::equalTo($input),
                    self::anything(),
                    self::equalTo($exception),
                )
                ->willReturnArgument(1);

        $middleware = new OutputThrowableCaughtMiddleware();

        $outputAfterMiddleware = $middleware->throwableCaught($input, $output, $exception, $handler);

        self::assertNotSame($output, $outputAfterMiddleware);
        self::assertNotEmpty($outputAfterMiddleware->getMessages());
        self::assertNotEmpty($outputAfterMiddleware->getUnwrittenMessages());
        self::assertSame($outputAfterMiddleware->getMessages(), $outputAfterMiddleware->getUnwrittenMessages());
        self::assertSame('Cli Server Error:', $outputAfterMiddleware->getMessages()[0]->getText());
        self::assertInstanceOf(NewLine::class, $outputAfterMiddleware->getMessages()[1]);
        self::assertSame("Url: $commandName", $outputAfterMiddleware->getMessages()[2]->getText());
        self::assertInstanceOf(NewLine::class, $outputAfterMiddleware->getMessages()[3]);
        self::assertSame('Message: ' . $exception->getMessage(), $outputAfterMiddleware->getMessages()[4]->getText());
        self::assertInstanceOf(NewLine::class, $outputAfterMiddleware->getMessages()[5]);
        self::assertSame('Line: ' . ((string) $exception->getLine()), $outputAfterMiddleware->getMessages()[6]->getText());
        self::assertInstanceOf(NewLine::class, $outputAfterMiddleware->getMessages()[7]);
        self::assertSame('Trace: ' . $exception->getTraceAsString(), $outputAfterMiddleware->getMessages()[8]->getText());
    }
}
