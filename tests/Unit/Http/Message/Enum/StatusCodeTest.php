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

use JsonException;
use PHPUnit\Framework\Attributes\DataProvider;
use Valkyrja\Http\Message\Constant\StatusText;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Tests\Unit\TestCase;
use Valkyrja\Type\BuiltIn\Enum\Support\Enum;

use function constant;
use function json_encode;

use const JSON_THROW_ON_ERROR;

class StatusCodeTest extends TestCase
{
    /**
     * @return int[][]
     */
    public static function codesProvider(): array
    {
        return [Enum::values(StatusCode::class)];
    }

    /**
     * @return StatusCode[][]
     */
    public static function casesProvider(): array
    {
        return [StatusCode::cases()];
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
        self::assertSame($status->asPhrase(), constant(StatusText::class . "::$status->name"));
    }

    /**
     * @throws JsonException
     */
    #[DataProvider('casesProvider')]
    public function testJsonSerialize(StatusCode $statusCode): void
    {
        self::assertSame($statusCode->value, $statusCode->jsonSerialize());
        self::assertSame((string) $statusCode->value, json_encode($statusCode, JSON_THROW_ON_ERROR));
    }
}
