<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Type\Enum;

use JsonException;
use Valkyrja\Tests\Fixtures\Enum\ArrayableEnum;
use Valkyrja\Tests\Fixtures\Enum\ArrayableIntEnum;
use Valkyrja\Tests\Fixtures\Enum\ArrayableStringEnum;
use Valkyrja\Tests\Unit\Abstract\TestCase;

use function json_encode;

use const JSON_THROW_ON_ERROR;

final class JsonSerializableTest extends TestCase
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
