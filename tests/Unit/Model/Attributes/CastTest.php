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

namespace Valkyrja\Tests\Unit\Model\Attributes;

use Valkyrja\Model\Attributes\Cast;
use Valkyrja\Model\Data\Cast as CastData;
use Valkyrja\Model\Enums\CastType;
use Valkyrja\Tests\Unit\TestCase;
use Valkyrja\Type\Types\StringT;

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
        self::assertSame(true, $data->convert);
        self::assertSame(false, $data->isArray);
    }

    public function testCastType(): void
    {
        $value = CastType::string;
        $data  = new Cast($value);

        self::assertSame($value->value, $data->type);
        self::assertSame(true, $data->convert);
        self::assertSame(false, $data->isArray);
    }

    public function testNoConvert(): void
    {
        $value = StringT::class;
        $data  = new Cast($value, convert: false);

        self::assertSame($value, $data->type);
        self::assertSame(false, $data->convert);
        self::assertSame(false, $data->isArray);
    }

    public function testArray(): void
    {
        $value = StringT::class;
        $data  = new Cast($value, isArray: true);

        self::assertSame($value, $data->type);
        self::assertSame(true, $data->convert);
        self::assertSame(true, $data->isArray);
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
