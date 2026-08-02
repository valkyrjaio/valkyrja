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

use Valkyrja\Orm\Enum\JoinType;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class JoinTypeTest extends TestCase
{
    public function testDefaultJoinType(): void
    {
        self::assertSame('', JoinType::DEFAULT->value);
    }

    public function testRightJoinType(): void
    {
        self::assertSame('RIGHT', JoinType::RIGHT->value);
    }

    public function testLeftJoinType(): void
    {
        self::assertSame('LEFT', JoinType::LEFT->value);
    }

    public function testInnerJoinType(): void
    {
        self::assertSame('INNER', JoinType::INNER->value);
    }

    public function testOuterJoinType(): void
    {
        self::assertSame('OUTER', JoinType::OUTER->value);
    }

    public function testFullOuterJoinType(): void
    {
        self::assertSame('FULL OUTER', JoinType::FULL_OUTER->value);
    }

    public function testCasesReturnsAllJoinTypes(): void
    {
        $cases = JoinType::cases();

        self::assertCount(6, $cases);
        self::assertContains(JoinType::DEFAULT, $cases);
        self::assertContains(JoinType::RIGHT, $cases);
        self::assertContains(JoinType::LEFT, $cases);
        self::assertContains(JoinType::INNER, $cases);
        self::assertContains(JoinType::OUTER, $cases);
        self::assertContains(JoinType::FULL_OUTER, $cases);
    }
}
