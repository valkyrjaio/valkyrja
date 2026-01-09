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

use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\Enum\Type;

use function json_encode;

class TypeTest extends TestCase
{
    public function testTotalCaseCount(): void
    {
        self::assertCount(9, Type::cases());
    }

    public function testNames(): void
    {
        self::assertSame('array', Type::array->name);
        self::assertSame('object', Type::object->name);
        self::assertSame('string', Type::string->name);
        self::assertSame('int', Type::int->name);
        self::assertSame('float', Type::float->name);
        self::assertSame('bool', Type::bool->name);
        self::assertSame('true', Type::true->name);
        self::assertSame('false', Type::false->name);
        self::assertSame('null', Type::null->name);
    }

    public function testJsonSerialize(): void
    {
        self::assertSame(json_encode('array'), json_encode(Type::array));
        self::assertSame(json_encode('object'), json_encode(Type::object));
        self::assertSame(json_encode('string'), json_encode(Type::string));
        self::assertSame(json_encode('int'), json_encode(Type::int));
        self::assertSame(json_encode('float'), json_encode(Type::float));
        self::assertSame(json_encode('bool'), json_encode(Type::bool));
        self::assertSame(json_encode('true'), json_encode(Type::true));
        self::assertSame(json_encode('false'), json_encode(Type::false));
        self::assertSame(json_encode('null'), json_encode(Type::null));
    }
}
