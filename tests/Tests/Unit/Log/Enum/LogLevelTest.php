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

namespace Valkyrja\Tests\Unit\Log\Enum;

use Valkyrja\Log\Enum\LogLevel;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class LogLevelTest extends TestCase
{
    public function testDebugLevel(): void
    {
        self::assertSame('DEBUG', LogLevel::DEBUG->name);
        self::assertSame('debug', LogLevel::DEBUG->value);
    }

    public function testInfoLevel(): void
    {
        self::assertSame('INFO', LogLevel::INFO->name);
        self::assertSame('info', LogLevel::INFO->value);
    }

    public function testNoticeLevel(): void
    {
        self::assertSame('NOTICE', LogLevel::NOTICE->name);
        self::assertSame('notice', LogLevel::NOTICE->value);
    }

    public function testWarningLevel(): void
    {
        self::assertSame('WARNING', LogLevel::WARNING->name);
        self::assertSame('warning', LogLevel::WARNING->value);
    }

    public function testErrorLevel(): void
    {
        self::assertSame('ERROR', LogLevel::ERROR->name);
        self::assertSame('error', LogLevel::ERROR->value);
    }

    public function testCriticalLevel(): void
    {
        self::assertSame('CRITICAL', LogLevel::CRITICAL->name);
        self::assertSame('critical', LogLevel::CRITICAL->value);
    }

    public function testAlertLevel(): void
    {
        self::assertSame('ALERT', LogLevel::ALERT->name);
        self::assertSame('alert', LogLevel::ALERT->value);
    }

    public function testEmergencyLevel(): void
    {
        self::assertSame('EMERGENCY', LogLevel::EMERGENCY->name);
        self::assertSame('emergency', LogLevel::EMERGENCY->value);
    }

    public function testCasesReturnsAllLevels(): void
    {
        $cases = LogLevel::cases();

        self::assertCount(8, $cases);
        self::assertContains(LogLevel::DEBUG, $cases);
        self::assertContains(LogLevel::INFO, $cases);
        self::assertContains(LogLevel::NOTICE, $cases);
        self::assertContains(LogLevel::WARNING, $cases);
        self::assertContains(LogLevel::ERROR, $cases);
        self::assertContains(LogLevel::CRITICAL, $cases);
        self::assertContains(LogLevel::ALERT, $cases);
        self::assertContains(LogLevel::EMERGENCY, $cases);
    }

    public function testFromValueReturnsCorrectLevel(): void
    {
        self::assertSame(LogLevel::DEBUG, LogLevel::from('debug'));
        self::assertSame(LogLevel::INFO, LogLevel::from('info'));
        self::assertSame(LogLevel::NOTICE, LogLevel::from('notice'));
        self::assertSame(LogLevel::WARNING, LogLevel::from('warning'));
        self::assertSame(LogLevel::ERROR, LogLevel::from('error'));
        self::assertSame(LogLevel::CRITICAL, LogLevel::from('critical'));
        self::assertSame(LogLevel::ALERT, LogLevel::from('alert'));
        self::assertSame(LogLevel::EMERGENCY, LogLevel::from('emergency'));
    }

    public function testTryFromReturnsNullForInvalidValue(): void
    {
        self::assertNull(LogLevel::tryFrom('invalid'));
    }
}
