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

namespace Valkyrja\Tests\Unit\Type\BuiltIn;

use Valkyrja\Tests\Unit\TestCase;
use Valkyrja\Type\Types\TrueT;

class TrueTest extends TestCase
{
    protected const VALUE = true;

    public function testValue(): void
    {
        $type = new TrueT(self::VALUE);

        self::assertSame(self::VALUE, $type->asValue());
    }

    public function testFromValue(): void
    {
        $typeFromValue = TrueT::fromValue(self::VALUE);

        self::assertSame(self::VALUE, $typeFromValue->asValue());
    }

    public function testAsFlatValue(): void
    {
        $type = new TrueT(self::VALUE);

        self::assertSame(self::VALUE, $type->asFlatValue());
    }

    public function testModify(): void
    {
        $type = new TrueT(self::VALUE);
        // The new value
        $newValue = false;

        $modified = $type->modify(fn (bool $subject): bool => $newValue);

        // Original should be unmodified
        self::assertSame(self::VALUE, $type->asValue());
        // New should be unmodified and always true
        self::assertNotSame($newValue, $modified->asValue());
    }

    public function testJsonSerialize(): void
    {
        $type = new TrueT(self::VALUE);

        self::assertSame(json_encode(self::VALUE), json_encode($type));
    }
}
