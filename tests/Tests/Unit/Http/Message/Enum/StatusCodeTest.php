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

namespace Valkyrja\Tests\Unit\Http\Message\Enum;

use PHPUnit\Framework\Attributes\DataProvider;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Enum\StatusText;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\Enum\Support\Enumerable;

final class StatusCodeTest extends TestCase
{
    /**
     * @return int[][]
     */
    public static function codesProvider(): array
    {
        $codes = [];

        foreach (Enumerable::values(StatusCode::class) as $code) {
            $codes[] = [$code];
        }

        return $codes;
    }

    /**
     * @return StatusCode[][]
     */
    public static function casesProvider(): array
    {
        $cases = [];

        foreach (StatusCode::cases() as $case) {
            $cases[] = [$case];
        }

        return $cases;
    }

    #[DataProvider('casesProvider')]
    public static function testIsRedirect(StatusCode $status): void
    {
        if ($status->value >= StatusCode::MULTIPLE_CHOICES->value && $status->value < StatusCode::BAD_REQUEST->value) {
            self::assertTrue($status->isRedirect());
        } else {
            self::assertFalse($status->isRedirect());
        }
    }

    #[DataProvider('casesProvider')]
    public static function testIsError(StatusCode $status): void
    {
        if ($status->value >= StatusCode::INTERNAL_SERVER_ERROR->value) {
            self::assertTrue($status->isError());
        } else {
            self::assertFalse($status->isError());
        }
    }

    #[DataProvider('codesProvider')]
    public function testCode(int $status): void
    {
        self::assertSame($status, StatusCode::from($status)->code());
    }

    #[DataProvider('casesProvider')]
    public function testText(StatusCode $status): void
    {
        self::assertSame($status->asPhrase(), StatusText::{$status->name}->value);
    }
}
