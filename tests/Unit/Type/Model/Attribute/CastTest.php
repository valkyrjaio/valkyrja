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

namespace Valkyrja\Tests\Unit\Type\Model\Attribute;

use Valkyrja\Tests\Unit\TestCase;
use Valkyrja\Type\BuiltIn\StringT;
use Valkyrja\Type\Data\Cast as CastData;
use Valkyrja\Type\Enum\CastType;
use Valkyrja\Type\Model\Attribute\Cast;

use function json_encode;

class CastTest extends TestCase
{
    public function testInherits(): void
    {
        self::isA(CastData::class, Cast::class);
    }

    public function testStringType(): void
    {
        $value = StringT::class;
        $data  = new Cast($value);

        self::assertSame($value, $data->type);
        self::assertTrue($data->convert);
        self::assertFalse($data->isArray);
    }

    public function testCastType(): void
    {
        $value = CastType::string;
        $data  = new Cast($value);

        self::assertSame($value->value, $data->type);
        self::assertTrue($data->convert);
        self::assertFalse($data->isArray);
    }

    public function testNoConvert(): void
    {
        $value = StringT::class;
        $data  = new Cast($value, convert: false);

        self::assertSame($value, $data->type);
        self::assertFalse($data->convert);
        self::assertFalse($data->isArray);
    }

    public function testArray(): void
    {
        $value = StringT::class;
        $data  = new Cast($value, isArray: true);

        self::assertSame($value, $data->type);
        self::assertTrue($data->convert);
        self::assertTrue($data->isArray);
    }

    public function testJsonSerialize(): void
    {
        $value = StringT::class;
        $data  = new Cast($value);

        self::assertSame(
            json_encode([
                'type'    => $value,
                'convert' => true,
                'isArray' => false,
            ]),
            json_encode($data)
        );
    }
}
