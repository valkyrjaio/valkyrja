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

namespace Valkyrja\Tests\Unit\Type\Json;

use JsonException;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\Json\JsonObject;

use function json_encode;

class JsonObjectTest extends TestCase
{
    public function testValue(): void
    {
        $value = new class {
        };
        $type  = new JsonObject($value);

        self::assertSame($value, $type->asValue());
    }

    /**
     * @throws JsonException
     */
    public function testFromValue(): void
    {
        $value         = new class {
        };
        $typeFromValue = JsonObject::fromValue($value);

        self::assertSame($value, $typeFromValue->asValue());
    }

    /**
     * @throws JsonException
     */
    public function testAsFlatValue(): void
    {
        $value = new class {
        };
        $type  = new JsonObject($value);

        self::assertSame(json_encode($value), $type->asFlatValue());
    }

    /**
     * @throws JsonException
     */
    public function testModify(): void
    {
        $value = new class {
            public string $foo = 'test';
        };
        $type  = new JsonObject($value);
        // The new value
        $newValue = 'bar';

        $modified = $type->modify(static function (object $subject) use ($newValue): object {
            $subject->foo = $newValue;

            return $subject;
        });

        // Original should be unmodified
        self::assertSame('test', $type->asValue()->foo);
        self::assertSame($value, $type->asValue());
        // New should be modified
        self::assertSame($newValue, $modified->asValue()->foo);
    }

    public function testJsonSerialize(): void
    {
        $value = new class {
            public string $pie = 'pie';
        };
        $type  = new JsonObject($value);

        self::assertSame(json_encode($value), json_encode($type));
    }
}
