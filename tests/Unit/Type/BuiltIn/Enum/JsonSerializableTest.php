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

namespace Valkyrja\Tests\Unit\Type\BuiltIn\Enum;

use JsonException;
use Valkyrja\Tests\Classes\Enum\ArrayableEnum;
use Valkyrja\Tests\Classes\Enum\ArrayableIntEnum;
use Valkyrja\Tests\Classes\Enum\ArrayableStringEnum;
use Valkyrja\Tests\Unit\TestCase;

use function json_encode;

use const JSON_THROW_ON_ERROR;

class JsonSerializableTest extends TestCase
{
    /**
     * @throws JsonException
     */
    public function testJsonSerialize(): void
    {
        self::assertSame('heart', ArrayableEnum::heart->jsonSerialize());
        self::assertSame(
            json_encode('heart', JSON_THROW_ON_ERROR),
            json_encode(ArrayableEnum::heart, JSON_THROW_ON_ERROR)
        );

        self::assertSame('bar', ArrayableStringEnum::foo->jsonSerialize());
        self::assertSame(
            json_encode('bar', JSON_THROW_ON_ERROR),
            json_encode(ArrayableStringEnum::foo, JSON_THROW_ON_ERROR)
        );

        self::assertSame(1, ArrayableIntEnum::first->jsonSerialize());
        self::assertSame(
            json_encode(1, JSON_THROW_ON_ERROR),
            json_encode(ArrayableIntEnum::first, JSON_THROW_ON_ERROR)
        );
    }
}
