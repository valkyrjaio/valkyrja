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

namespace Valkyrja\Tests\Unit\Cli\Server\Middleware\ThrowableCaught;

use Valkyrja\Cli\Interaction\Input\Input;
use Valkyrja\Cli\Interaction\Message\Banner;
use Valkyrja\Cli\Interaction\Message\NewLine;
use Valkyrja\Cli\Interaction\Output\Output;
use Valkyrja\Cli\Middleware\Handler\ThrowableCaughtHandler;
use Valkyrja\Cli\Server\Middleware\ThrowableCaught\OutputThrowableCaughtMiddleware;
use Valkyrja\Tests\Unit\Abstract\TestCase;
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
        self::assertInstanceOf(Banner::class, $outputAfterMiddleware->getMessages()[0]);
        self::assertStringContainsString('Cli Server Error:', $outputAfterMiddleware->getMessages()[0]->getText());
        self::assertInstanceOf(NewLine::class, $outputAfterMiddleware->getMessages()[1]);
        self::assertSame('Command:', $outputAfterMiddleware->getMessages()[2]->getText());
        self::assertSame(" $commandName", $outputAfterMiddleware->getMessages()[3]->getText());
        self::assertInstanceOf(NewLine::class, $outputAfterMiddleware->getMessages()[4]);
        self::assertInstanceOf(NewLine::class, $outputAfterMiddleware->getMessages()[5]);
        self::assertSame('Message:', $outputAfterMiddleware->getMessages()[6]->getText());
        self::assertSame(' ' . $exception->getMessage(), $outputAfterMiddleware->getMessages()[7]->getText());
        self::assertInstanceOf(NewLine::class, $outputAfterMiddleware->getMessages()[8]);
        self::assertInstanceOf(NewLine::class, $outputAfterMiddleware->getMessages()[9]);
        self::assertSame('Line:', $outputAfterMiddleware->getMessages()[10]->getText());
        self::assertSame(' ' . ((string) $exception->getLine()), $outputAfterMiddleware->getMessages()[11]->getText());
        self::assertInstanceOf(NewLine::class, $outputAfterMiddleware->getMessages()[12]);
        self::assertInstanceOf(NewLine::class, $outputAfterMiddleware->getMessages()[13]);
        self::assertSame('Trace:', $outputAfterMiddleware->getMessages()[14]->getText());
        self::assertInstanceOf(NewLine::class, $outputAfterMiddleware->getMessages()[15]);
        self::assertSame($exception->getTraceAsString() . "\n", $outputAfterMiddleware->getMessages()[16]->getText());
    }
}
