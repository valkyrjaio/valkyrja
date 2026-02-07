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

use Valkyrja\Log\Enum\LogLevel;
use Valkyrja\Log\Logger\NullLogger;
use Valkyrja\Log\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class LoggerTest extends TestCase
{
    public function testLogWithInvalidLevelThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid log level passed');

        $logger = new NullLogger();

        // Pass a string instead of LogLevel enum
        $logger->log('invalid', 'Test message');
    }

    public function testLogWithStringLevelThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $logger = new NullLogger();

        // Pass the string value instead of the enum
        $logger->log('debug', 'Test message');
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

        self::assertTrue(true);
    }

    public function testLogWithContext(): void
    {
        $logger  = new NullLogger();
        $context = ['user_id' => 123, 'action' => 'login'];

        // Should work without throwing exceptions
        $logger->log(LogLevel::INFO, 'User action', $context);

        self::assertTrue(true);
    }
}
