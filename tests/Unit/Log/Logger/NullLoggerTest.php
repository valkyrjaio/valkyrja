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
use Psr\Log\LoggerInterface;
use Valkyrja\Log\Enum\LogLevel;
use Valkyrja\Log\Logger\Contract\LoggerContract;
use Valkyrja\Log\Logger\NullLogger;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class NullLoggerTest extends TestCase
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

        self::assertTrue(true);
    }

    public function testInfoDoesNothing(): void
    {
        $this->logger->info('Info message', ['key' => 'value']);

        self::assertTrue(true);
    }

    public function testNoticeDoesNothing(): void
    {
        $this->logger->notice('Notice message', ['key' => 'value']);

        self::assertTrue(true);
    }

    public function testWarningDoesNothing(): void
    {
        $this->logger->warning('Warning message', ['key' => 'value']);

        self::assertTrue(true);
    }

    public function testErrorDoesNothing(): void
    {
        $this->logger->error('Error message', ['key' => 'value']);

        self::assertTrue(true);
    }

    public function testCriticalDoesNothing(): void
    {
        $this->logger->critical('Critical message', ['key' => 'value']);

        self::assertTrue(true);
    }

    public function testAlertDoesNothing(): void
    {
        $this->logger->alert('Alert message', ['key' => 'value']);

        self::assertTrue(true);
    }

    public function testEmergencyDoesNothing(): void
    {
        $this->logger->emergency('Emergency message', ['key' => 'value']);

        self::assertTrue(true);
    }

    public function testLogWithDebugLevel(): void
    {
        $this->logger->log(LogLevel::DEBUG, 'Debug message');

        self::assertTrue(true);
    }

    public function testLogWithInfoLevel(): void
    {
        $this->logger->log(LogLevel::INFO, 'Info message');

        self::assertTrue(true);
    }

    public function testLogWithNoticeLevel(): void
    {
        $this->logger->log(LogLevel::NOTICE, 'Notice message');

        self::assertTrue(true);
    }

    public function testLogWithWarningLevel(): void
    {
        $this->logger->log(LogLevel::WARNING, 'Warning message');

        self::assertTrue(true);
    }

    public function testLogWithErrorLevel(): void
    {
        $this->logger->log(LogLevel::ERROR, 'Error message');

        self::assertTrue(true);
    }

    public function testLogWithCriticalLevel(): void
    {
        $this->logger->log(LogLevel::CRITICAL, 'Critical message');

        self::assertTrue(true);
    }

    public function testLogWithAlertLevel(): void
    {
        $this->logger->log(LogLevel::ALERT, 'Alert message');

        self::assertTrue(true);
    }

    public function testLogWithEmergencyLevel(): void
    {
        $this->logger->log(LogLevel::EMERGENCY, 'Emergency message');

        self::assertTrue(true);
    }

    public function testThrowableDoesNothing(): void
    {
        $exception = new Exception('Test exception');

        $this->logger->throwable($exception, 'An error occurred', ['key' => 'value']);

        self::assertTrue(true);
    }
}
