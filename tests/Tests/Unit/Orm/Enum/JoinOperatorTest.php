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

namespace Valkyrja\Tests\Unit\Orm\Enum;

use Valkyrja\Orm\Enum\JoinOperator;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class JoinOperatorTest extends TestCase
{
    public function testOnOperator(): void
    {
        self::assertSame('ON', JoinOperator::ON->value);
    }

    public function testWhereOperator(): void
    {
        self::assertSame('WHERE', JoinOperator::WHERE->value);
    }

    public function testCasesReturnsAllOperators(): void
    {
        $cases = JoinOperator::cases();

        self::assertCount(2, $cases);
        self::assertContains(JoinOperator::ON, $cases);
        self::assertContains(JoinOperator::WHERE, $cases);
    }
}
