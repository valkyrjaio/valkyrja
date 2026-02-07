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

namespace Valkyrja\Tests\Unit\Orm\Data;

use ReflectionClass;
use Valkyrja\Orm\Data\Id;
use Valkyrja\Orm\Data\Value;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class IdTest extends TestCase
{
    public function testExtendsValue(): void
    {
        $id = new Id(123);

        self::assertInstanceOf(Value::class, $id);
    }

    public function testDefaultNameIsId(): void
    {
        $id = new Id(123);

        self::assertSame('id', $id->name);
    }

    public function testCustomName(): void
    {
        $id = new Id(123, 'user_id');

        self::assertSame('user_id', $id->name);
    }

    public function testIntegerValue(): void
    {
        $id = new Id(123);

        self::assertSame(123, $id->value);
    }

    public function testStringValue(): void
    {
        $id = new Id('uuid-12345');

        self::assertSame('uuid-12345', $id->value);
    }

    public function testToStringReturnsBindParameter(): void
    {
        $id = new Id(123);

        self::assertSame(':id', (string) $id);
    }

    public function testToStringWithCustomName(): void
    {
        $id = new Id(123, 'user_id');

        self::assertSame(':user_id', (string) $id);
    }

    public function testReadonlyClass(): void
    {
        $reflection = new ReflectionClass(Id::class);

        self::assertTrue($reflection->isReadOnly());
    }
}
