<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Log\Logger;

use Exception;
use Psr\Log\LoggerInterface;
use Valkyrja\Log\Enum\LogLevel;
use Valkyrja\Log\Logger\Contract\LoggerContract;
use Valkyrja\Log\Logger\NullLogger;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class NullLoggerTest extends TestCase
{
    protected NullLogger $logger;

    protected function setUp(): void
    {
        $this->logger = new NullLogger();
    }

    public function testInstanceOfContract(): void
    {
        self::assertInstanceOf(LoggerContract::class, $this->logger);
        self::assertInstanceOf(LoggerInterface::class, $this->logger);
    }

    public function testDebugDoesNothing(): void
    {
        // Should not throw any exception
        $this->logger->debug('Debug message', ['key' => 'value']);

        $this->expectNotToPerformAssertions();
    }

    public function testInfoDoesNothing(): void
    {
        $this->logger->info('Info message', ['key' => 'value']);

        $this->expectNotToPerformAssertions();
    }

    public function testNoticeDoesNothing(): void
    {
        $this->logger->notice('Notice message', ['key' => 'value']);

        $this->expectNotToPerformAssertions();
    }

    public function testWarningDoesNothing(): void
    {
        $this->logger->warning('Warning message', ['key' => 'value']);

        $this->expectNotToPerformAssertions();
    }

    public function testErrorDoesNothing(): void
    {
        $this->logger->error('Error message', ['key' => 'value']);

        $this->expectNotToPerformAssertions();
    }

    public function testCriticalDoesNothing(): void
    {
        $this->logger->critical('Critical message', ['key' => 'value']);

        $this->expectNotToPerformAssertions();
    }

    public function testAlertDoesNothing(): void
    {
        $this->logger->alert('Alert message', ['key' => 'value']);

        $this->expectNotToPerformAssertions();
    }

    public function testEmergencyDoesNothing(): void
    {
        $this->logger->emergency('Emergency message', ['key' => 'value']);

        $this->expectNotToPerformAssertions();
    }

    public function testLogWithDebugLevel(): void
    {
        $this->logger->log(LogLevel::DEBUG, 'Debug message');

        $this->expectNotToPerformAssertions();
    }

    public function testLogWithInfoLevel(): void
    {
        $this->logger->log(LogLevel::INFO, 'Info message');

        $this->expectNotToPerformAssertions();
    }

    public function testLogWithNoticeLevel(): void
    {
        $this->logger->log(LogLevel::NOTICE, 'Notice message');

        $this->expectNotToPerformAssertions();
    }

    public function testLogWithWarningLevel(): void
    {
        $this->logger->log(LogLevel::WARNING, 'Warning message');

        $this->expectNotToPerformAssertions();
    }

    public function testLogWithErrorLevel(): void
    {
        $this->logger->log(LogLevel::ERROR, 'Error message');

        $this->expectNotToPerformAssertions();
    }

    public function testLogWithCriticalLevel(): void
    {
        $this->logger->log(LogLevel::CRITICAL, 'Critical message');

        $this->expectNotToPerformAssertions();
    }

    public function testLogWithAlertLevel(): void
    {
        $this->logger->log(LogLevel::ALERT, 'Alert message');

        $this->expectNotToPerformAssertions();
    }

    public function testLogWithEmergencyLevel(): void
    {
        $this->logger->log(LogLevel::EMERGENCY, 'Emergency message');

        $this->expectNotToPerformAssertions();
    }

    public function testThrowableDoesNothing(): void
    {
        $exception = new Exception('Test exception');

        $this->logger->throwable($exception, 'An error occurred', ['key' => 'value']);

        $this->expectNotToPerformAssertions();
    }
}
