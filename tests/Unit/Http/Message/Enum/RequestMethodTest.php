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
use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Tests\Unit\TestCase;

use function json_encode;

use const JSON_THROW_ON_ERROR;

class RequestMethodTest extends TestCase
{
    /**
     * @return RequestMethod[][]
     */
    public static function casesProvider(): array
    {
        return [RequestMethod::cases()];
    }

    /**
     * @throws JsonException
     */
    #[DataProvider('casesProvider')]
    public function testJsonSerialize(RequestMethod $requestMethod): void
    {
        self::assertSame($requestMethod->value, $requestMethod->jsonSerialize());
        self::assertSame("\"$requestMethod->value\"", json_encode($requestMethod, JSON_THROW_ON_ERROR));
    }
}
