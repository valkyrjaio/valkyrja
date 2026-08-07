<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Orm\Constant;

use DateTime;
use DateTimeZone;
use PDO;
use PHPUnit\Framework\Attributes\DataProvider;
use Valkyrja\Orm\Constant\DateFormat;
use Valkyrja\Tests\Unit\Abstract\TestCase;

use function usort;

final class DateFormatTest extends TestCase
{
    /**
     * @return array<string, array{non-empty-string}>
     */
    public static function formatProvider(): array
    {
        return [
            'default'     => [DateFormat::DEFAULT],
            'millisecond' => [DateFormat::MILLISECOND],
            'microsecond' => [DateFormat::MICROSECOND],
        ];
    }

    public function testDefaultFormat(): void
    {
        self::assertSame('Y-m-d H:i:s', DateFormat::DEFAULT);
    }

    public function testMillisecondFormat(): void
    {
        self::assertSame('Y-m-d H:i:s.v', DateFormat::MILLISECOND);
    }

    public function testMicrosecondFormat(): void
    {
        self::assertSame('Y-m-d H:i:s.u', DateFormat::MICROSECOND);
    }

    public function testFormatsContainTime(): void
    {
        self::assertStringContainsString('H:i:s', DateFormat::DEFAULT);
        self::assertStringContainsString('H:i:s', DateFormat::MILLISECOND);
        self::assertStringContainsString('H:i:s', DateFormat::MICROSECOND);
    }

    /**
     * `DateFactory` builds each time in UTC, so a timezone renders the same
     * characters on every row. The suffix also stops the value from fitting a
     * DATETIME column.
     */
    public function testNoFormatHoldsATimezone(): void
    {
        self::assertStringNotContainsString('T', DateFormat::DEFAULT);
        self::assertStringNotContainsString('T', DateFormat::MILLISECOND);
        self::assertStringNotContainsString('T', DateFormat::MICROSECOND);
        self::assertStringNotContainsString('P', DateFormat::DEFAULT);
        self::assertStringNotContainsString('e', DateFormat::DEFAULT);
    }

    /**
     * The format placed the month first before, so a text sort of the column
     * was not a date sort and `ORDER BY created_at` read the wrong row. Each
     * format must put the largest unit first.
     *
     * @param non-empty-string $format The format
     */
    #[DataProvider('formatProvider')]
    public function testATextSortIsADateSort(string $format): void
    {
        $utc = new DateTimeZone('+00:00');

        $chronological = [
            new DateTime('2025-12-01 10:00:00', $utc),
            new DateTime('2026-01-15 10:00:00', $utc),
            new DateTime('2026-06-10 10:00:00', $utc),
        ];

        $formatted = [];

        foreach ($chronological as $dateTime) {
            $formatted[] = $dateTime->format($format);
        }

        $sorted = $formatted;

        usort($sorted, static fn (string $a, string $b): int => $a <=> $b);

        self::assertSame($formatted, $sorted, "A text sort of the $format format is not a date sort.");
    }

    /**
     * SQLite is one of the four managers that the ORM ships, and it reads ISO
     * 8601 only. The old format gave null for every date function, so a query
     * could not group or filter by a stored date.
     *
     * @param non-empty-string $format The format
     */
    #[DataProvider('formatProvider')]
    public function testSqliteReadsTheStoredValue(string $format): void
    {
        $pdo = new PDO('sqlite::memory:');
        $pdo->exec('CREATE TABLE dates (id INTEGER PRIMARY KEY, created_at TEXT)');

        $utc       = new DateTimeZone('+00:00');
        $dateTime  = new DateTime('2026-01-15 10:00:00', $utc);
        $statement = $pdo->prepare('INSERT INTO dates VALUES (1, ?)');
        $statement->execute([$dateTime->format($format)]);

        $read = $pdo->query('SELECT date(created_at) AS day, strftime(\'%Y\', created_at) AS year FROM dates');

        self::assertNotFalse($read);

        /** @psalm-suppress MixedAssignment The test gives invalid input on purpose to reach the guard. */
        $row = $read->fetch(PDO::FETCH_ASSOC);

        /**
         * @psalm-suppress MixedArrayAccess The test gives invalid input on purpose to reach the guard.
         *
         * @phpstan-ignore offsetAccess.nonOffsetAccessible (The test gives invalid input on purpose to reach the guard.)
         */
        self::assertSame('2026-01-15', $row['day'], "SQLite cannot read a date in the $format format.");
        /**
         * @psalm-suppress MixedArrayAccess The test gives invalid input on purpose to reach the guard.
         *
         * @phpstan-ignore offsetAccess.nonOffsetAccessible (The test gives invalid input on purpose to reach the guard.)
         */
        self::assertSame('2026', $row['year'], "SQLite cannot read a year in the $format format.");
    }
}
