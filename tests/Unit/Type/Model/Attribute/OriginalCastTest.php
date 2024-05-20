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
use Valkyrja\Type\Model\Attribute\Cast;
use Valkyrja\Type\Model\Attribute\OriginalCast;
use Valkyrja\Type\Model\Enum\CastType;

use function json_encode;

class OriginalCastTest extends TestCase
{
    public function testInherits(): void
    {
        self::isA(Cast::class, OriginalCast::class);
    }

    public function testStringType(): void
    {
        $value = StringT::class;
        $data  = new OriginalCast($value);

        self::assertSame($value, $data->type);
        self::assertFalse($data->convert);
        self::assertFalse($data->isArray);
    }

    public function testCastType(): void
    {
        $value = CastType::string;
        $data  = new OriginalCast($value);

        self::assertSame($value->value, $data->type);
        self::assertFalse($data->convert);
        self::assertFalse($data->isArray);
    }

    public function testArray(): void
    {
        $value = StringT::class;
        $data  = new OriginalCast($value, isArray: true);

        self::assertSame($value, $data->type);
        self::assertFalse($data->convert);
        self::assertTrue($data->isArray);
    }

    public function testJsonSerialize(): void
    {
        $value = StringT::class;
        $data  = new OriginalCast($value);

        self::assertSame(
            json_encode([
                'type'    => $value,
                'convert' => false,
                'isArray' => false,
            ]),
            json_encode($data)
        );
    }
}
