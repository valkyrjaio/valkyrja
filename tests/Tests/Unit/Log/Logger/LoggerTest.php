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

use PHPUnit\Framework\Attributes\DataProvider;
use Valkyrja\Log\Enum\LogLevel;
use Valkyrja\Log\Logger\NullLogger;
use Valkyrja\Log\Throwable\Exception\LogInvalidLogLevelException;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class LoggerTest extends TestCase
{
    /**
     * The PSR-3 signature leaves the level untyped, so anything can reach the guard.
     *
     * @return array<string, array{mixed}>
     */
    public static function invalidLogLevelProvider(): array
    {
        return [
            'unknown string' => ['invalid'],
            'level name'     => ['debug'],
            'int'            => [1],
            'null'           => [null],
        ];
    }

    #[DataProvider('invalidLogLevelProvider')]
    public function testLogWithInvalidLevelThrowsException(mixed $level): void
    {
        $this->expectException(LogInvalidLogLevelException::class);
        $this->expectExceptionMessage('Invalid log level passed');

        $logger = new NullLogger();

        $logger->log($level, 'Test message');
    }

    public function testLogRoutesToCorrectMethod(): void
    {
        $logger = new NullLogger();

        // All these should work without throwing exceptions
        $logger->log(LogLevel::DEBUG, 'Debug');
        $logger->log(LogLevel::INFO, 'Info');
        $logger->log(LogLevel::NOTICE, 'Notice');
        $logger->log(LogLevel::WARNING, 'Warning');
        $logger->log(LogLevel::ERROR, 'Error');
        $logger->log(LogLevel::CRITICAL, 'Critical');
        $logger->log(LogLevel::ALERT, 'Alert');
        $logger->log(LogLevel::EMERGENCY, 'Emergency');

        $this->expectNotToPerformAssertions();
    }

    public function testLogWithContext(): void
    {
        $logger  = new NullLogger();
        $context = ['user_id' => 123, 'action' => 'login'];

        // Should work without throwing exceptions
        $logger->log(LogLevel::INFO, 'User action', $context);

        $this->expectNotToPerformAssertions();
    }
}
