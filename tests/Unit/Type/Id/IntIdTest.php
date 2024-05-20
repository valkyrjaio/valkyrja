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

namespace Valkyrja\Tests\Unit\Type\Id;

use Valkyrja\Tests\Unit\TestCase;
use Valkyrja\Type\Id\IntId;

class IntIdTest extends TestCase
{
    protected const VALUE = 1;

    public function testValue(): void
    {
        $type = new IntId(self::VALUE);

        self::assertSame(self::VALUE, $type->asValue());
    }

    public function testFromValue(): void
    {
        $typeFromValue = IntId::fromValue(self::VALUE);

        self::assertSame(self::VALUE, $typeFromValue->asValue());
    }

    public function testAsFlatValue(): void
    {
        $type = new IntId(self::VALUE);

        self::assertSame(self::VALUE, $type->asFlatValue());
    }

    public function testModify(): void
    {
        $type = new IntId(self::VALUE);
        // The new value
        $newValue = 2;

        $modified = $type->modify(static fn (int $subject): int => $newValue);

        // Original should be unmodified
        self::assertSame(self::VALUE, $type->asValue());
        // New should be modified
        self::assertSame($newValue, $modified->asValue());
    }

    public function testJsonSerialize(): void
    {
        $type = new IntId(self::VALUE);

        self::assertSame(json_encode(self::VALUE), json_encode($type));
    }
}
