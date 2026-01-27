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

namespace Valkyrja\Tests\Unit\Log\Logger;

use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\LoggerInterface;
use Valkyrja\Log\Enum\LogLevel;
use Valkyrja\Log\Logger\Contract\LoggerContract;
use Valkyrja\Log\Logger\PsrLogger;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class PsrLoggerTest extends TestCase
{
    protected PsrLogger $logger;

    /** @var LoggerInterface&MockObject */
    protected LoggerInterface $psrLogger;

    protected function setUp(): void
    {
        $this->psrLogger = $this->createMock(LoggerInterface::class);
        $this->logger    = new PsrLogger($this->psrLogger);
    }

    public function testInstanceOfContract(): void
    {
        $this->psrLogger->expects($this->never())->method('log');

        self::assertInstanceOf(LoggerContract::class, $this->logger);
        self::assertInstanceOf(LoggerInterface::class, $this->logger);
    }

    public function testDebugDelegatesToPsrLogger(): void
    {
        $message = 'Debug message';
        $context = ['key' => 'value'];

        $this->psrLogger
            ->expects($this->once())
            ->method('debug')
            ->with($message, $context);

        $this->logger->debug($message, $context);
    }

    public function testInfoDelegatesToPsrLogger(): void
    {
        $message = 'Info message';
        $context = ['key' => 'value'];

        $this->psrLogger
            ->expects($this->once())
            ->method('info')
            ->with($message, $context);

        $this->logger->info($message, $context);
    }

    public function testNoticeDelegatesToPsrLogger(): void
    {
        $message = 'Notice message';
        $context = ['key' => 'value'];

        $this->psrLogger
            ->expects($this->once())
            ->method('notice')
            ->with($message, $context);

        $this->logger->notice($message, $context);
    }

    public function testWarningDelegatesToPsrLogger(): void
    {
        $message = 'Warning message';
        $context = ['key' => 'value'];

        $this->psrLogger
            ->expects($this->once())
            ->method('warning')
            ->with($message, $context);

        $this->logger->warning($message, $context);
    }

    public function testErrorDelegatesToPsrLogger(): void
    {
        $message = 'Error message';
        $context = ['key' => 'value'];

        $this->psrLogger
            ->expects($this->once())
            ->method('error')
            ->with($message, $context);

        $this->logger->error($message, $context);
    }

    public function testCriticalDelegatesToPsrLogger(): void
    {
        $message = 'Critical message';
        $context = ['key' => 'value'];

        $this->psrLogger
            ->expects($this->once())
            ->method('critical')
            ->with($message, $context);

        $this->logger->critical($message, $context);
    }

    public function testAlertDelegatesToPsrLogger(): void
    {
        $message = 'Alert message';
        $context = ['key' => 'value'];

        $this->psrLogger
            ->expects($this->once())
            ->method('alert')
            ->with($message, $context);

        $this->logger->alert($message, $context);
    }

    public function testEmergencyDelegatesToPsrLogger(): void
    {
        $message = 'Emergency message';
        $context = ['key' => 'value'];

        $this->psrLogger
            ->expects($this->once())
            ->method('emergency')
            ->with($message, $context);

        $this->logger->emergency($message, $context);
    }

    public function testLogWithDebugLevel(): void
    {
        $this->psrLogger
            ->expects($this->once())
            ->method('debug')
            ->with('Log message', []);

        $this->logger->log(LogLevel::DEBUG, 'Log message');
    }

    public function testLogWithInfoLevel(): void
    {
        $this->psrLogger
            ->expects($this->once())
            ->method('info')
            ->with('Log message', []);

        $this->logger->log(LogLevel::INFO, 'Log message');
    }

    public function testLogWithNoticeLevel(): void
    {
        $this->psrLogger
            ->expects($this->once())
            ->method('notice')
            ->with('Log message', []);

        $this->logger->log(LogLevel::NOTICE, 'Log message');
    }

    public function testLogWithWarningLevel(): void
    {
        $this->psrLogger
            ->expects($this->once())
            ->method('warning')
            ->with('Log message', []);

        $this->logger->log(LogLevel::WARNING, 'Log message');
    }

    public function testLogWithErrorLevel(): void
    {
        $this->psrLogger
            ->expects($this->once())
            ->method('error')
            ->with('Log message', []);

        $this->logger->log(LogLevel::ERROR, 'Log message');
    }

    public function testLogWithCriticalLevel(): void
    {
        $this->psrLogger
            ->expects($this->once())
            ->method('critical')
            ->with('Log message', []);

        $this->logger->log(LogLevel::CRITICAL, 'Log message');
    }

    public function testLogWithAlertLevel(): void
    {
        $this->psrLogger
            ->expects($this->once())
            ->method('alert')
            ->with('Log message', []);

        $this->logger->log(LogLevel::ALERT, 'Log message');
    }

    public function testLogWithEmergencyLevel(): void
    {
        $this->psrLogger
            ->expects($this->once())
            ->method('emergency')
            ->with('Log message', []);

        $this->logger->log(LogLevel::EMERGENCY, 'Log message');
    }

    public function testThrowableLogsFormattedMessage(): void
    {
        $exception = new Exception('Test exception message');
        $message   = 'An error occurred';
        $context   = ['key' => 'value'];

        $this->psrLogger
            ->expects($this->once())
            ->method('error')
            ->with(
                self::callback(static fn (string $logMessage): bool => str_contains($logMessage, 'Trace Code:')
                        && str_contains($logMessage, 'Exception Message: ' . $exception->getMessage())
                        && str_contains($logMessage, 'Message: ' . $message)
                        && str_contains($logMessage, 'Stack Trace:')),
                $context
            );

        $this->logger->throwable($exception, $message, $context);
    }

    public function testThrowableIncludesTraceAsString(): void
    {
        $exception = new Exception('Test exception');

        $this->psrLogger
            ->expects($this->once())
            ->method('error')
            ->with(
                self::callback(static fn (string $logMessage): bool => str_contains($logMessage, $exception->getTraceAsString())),
                []
            );

        $this->logger->throwable($exception, 'Error', []);
    }
}
