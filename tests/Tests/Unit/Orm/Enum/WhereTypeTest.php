<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Orm\Enum;

use Valkyrja\Orm\Enum\WhereType;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class WhereTypeTest extends TestCase
{
    public function testDefaultWhereType(): void
    {
        self::assertSame('', WhereType::DEFAULT->value);
    }

    public function testAndWhereType(): void
    {
        self::assertSame('AND', WhereType::AND->value);
    }

    public function testOrWhereType(): void
    {
        self::assertSame('OR', WhereType::OR->value);
    }

    public function testNotWhereType(): void
    {
        self::assertSame('NOT', WhereType::NOT->value);
    }

    public function testAndNotWhereType(): void
    {
        self::assertSame('AND NOT', WhereType::AND_NOT->value);
    }

    public function testOrNotWhereType(): void
    {
        self::assertSame('OR NOT', WhereType::OR_NOT->value);
    }

    public function testCasesReturnsAllWhereTypes(): void
    {
        $cases = WhereType::cases();

        self::assertCount(6, $cases);
        self::assertContains(WhereType::DEFAULT, $cases);
        self::assertContains(WhereType::AND, $cases);
        self::assertContains(WhereType::OR, $cases);
        self::assertContains(WhereType::NOT, $cases);
        self::assertContains(WhereType::AND_NOT, $cases);
        self::assertContains(WhereType::OR_NOT, $cases);
    }
}
