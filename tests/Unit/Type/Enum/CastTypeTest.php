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

namespace Valkyrja\Tests\Unit\Type\Enum;

use Valkyrja\Tests\Unit\TestCase;
use Valkyrja\Type\BuiltIn\ArrayT;
use Valkyrja\Type\BuiltIn\BoolT;
use Valkyrja\Type\BuiltIn\FalseT;
use Valkyrja\Type\BuiltIn\FloatT;
use Valkyrja\Type\BuiltIn\IntT;
use Valkyrja\Type\BuiltIn\NullT;
use Valkyrja\Type\BuiltIn\ObjectT;
use Valkyrja\Type\BuiltIn\SerializedObject;
use Valkyrja\Type\BuiltIn\StringT;
use Valkyrja\Type\BuiltIn\TrueT;
use Valkyrja\Type\Enum\CastType;
use Valkyrja\Type\Json\Json;
use Valkyrja\Type\Json\JsonObject;

use function json_encode;

class CastTypeTest extends TestCase
{
    public function testTotalCaseCount(): void
    {
        self::assertCount(12, CastType::cases());
    }

    public function testValues(): void
    {
        self::assertSame(StringT::class, CastType::string->value);
        self::assertSame(IntT::class, CastType::int->value);
        self::assertSame(FloatT::class, CastType::float->value);
        self::assertSame(BoolT::class, CastType::bool->value);
        self::assertSame(ArrayT::class, CastType::array->value);
        self::assertSame(ObjectT::class, CastType::object->value);
        self::assertSame(SerializedObject::class, CastType::serialized_object->value);
        self::assertSame(Json::class, CastType::json->value);
        self::assertSame(JsonObject::class, CastType::json_object->value);
        self::assertSame(TrueT::class, CastType::true->value);
        self::assertSame(FalseT::class, CastType::false->value);
        self::assertSame(NullT::class, CastType::null->value);
    }

    public function testJsonSerialize(): void
    {
        self::assertSame(StringT::class, CastType::string->jsonSerialize());
        self::assertSame(IntT::class, CastType::int->jsonSerialize());
        self::assertSame(FloatT::class, CastType::float->jsonSerialize());
        self::assertSame(BoolT::class, CastType::bool->jsonSerialize());
        self::assertSame(ArrayT::class, CastType::array->jsonSerialize());
        self::assertSame(ObjectT::class, CastType::object->jsonSerialize());
        self::assertSame(SerializedObject::class, CastType::serialized_object->jsonSerialize());
        self::assertSame(Json::class, CastType::json->jsonSerialize());
        self::assertSame(JsonObject::class, CastType::json_object->jsonSerialize());
        self::assertSame(TrueT::class, CastType::true->jsonSerialize());
        self::assertSame(FalseT::class, CastType::false->jsonSerialize());
        self::assertSame(NullT::class, CastType::null->jsonSerialize());

        self::assertSame(json_encode(StringT::class), json_encode(CastType::string));
        self::assertSame(json_encode(IntT::class), json_encode(CastType::int));
        self::assertSame(json_encode(FloatT::class), json_encode(CastType::float));
        self::assertSame(json_encode(BoolT::class), json_encode(CastType::bool));
        self::assertSame(json_encode(ArrayT::class), json_encode(CastType::array));
        self::assertSame(json_encode(ObjectT::class), json_encode(CastType::object));
        self::assertSame(json_encode(SerializedObject::class), json_encode(CastType::serialized_object));
        self::assertSame(json_encode(Json::class), json_encode(CastType::json));
        self::assertSame(json_encode(JsonObject::class), json_encode(CastType::json_object));
        self::assertSame(json_encode(TrueT::class), json_encode(CastType::true));
        self::assertSame(json_encode(FalseT::class), json_encode(CastType::false));
        self::assertSame(json_encode(NullT::class), json_encode(CastType::null));
    }
}
