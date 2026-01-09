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

namespace Valkyrja\Tests\Unit\Type\Data;

use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\BuiltIn\StringT;
use Valkyrja\Type\Data\ArrayCast;
use Valkyrja\Type\Data\Cast;
use Valkyrja\Type\Enum\CastType;

use function json_encode;

class ArrayCastTest extends TestCase
{
    public function testInherits(): void
    {
        self::isA(Cast::class, ArrayCast::class);
    }

    public function testString(): void
    {
        $value = StringT::class;
        $data  = new ArrayCast($value);

        self::assertSame($value, $data->type);
        self::assertTrue($data->convert);
        self::assertTrue($data->isArray);
    }

    public function testStringNoConvert(): void
    {
        $value = StringT::class;
        $data  = new ArrayCast($value, false);

        self::assertSame($value, $data->type);
        self::assertFalse($data->convert);
        self::assertTrue($data->isArray);
    }

    public function testType(): void
    {
        $value = CastType::string;
        $data  = new ArrayCast($value);

        self::assertSame($value->value, $data->type);
        self::assertTrue($data->convert);
        self::assertTrue($data->isArray);
    }

    public function testTypeNoConvert(): void
    {
        $value = CastType::string;
        $data  = new ArrayCast($value, false);

        self::assertSame($value->value, $data->type);
        self::assertFalse($data->convert);
        self::assertTrue($data->isArray);
    }

    public function testJsonSerialize(): void
    {
        $value = StringT::class;
        $data  = new ArrayCast($value);

        self::assertSame(
            json_encode([
                'type'    => $value,
                'convert' => true,
                'isArray' => true,
            ]),
            json_encode($data)
        );
    }
}
