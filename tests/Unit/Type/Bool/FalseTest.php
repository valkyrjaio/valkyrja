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

namespace Valkyrja\Tests\Unit\Type\Bool;

use JsonException;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\Bool\FalseT;

use const JSON_THROW_ON_ERROR;

class FalseTest extends TestCase
{
    protected const false VALUE = false;

    public function testValue(): void
    {
        $type = new FalseT();

        self::assertSame(self::VALUE, $type->asValue());
    }

    public function testFromValue(): void
    {
        $typeFromValue = FalseT::fromValue(self::VALUE);

        self::assertFalse($typeFromValue->asValue());
    }

    public function testAsFlatValue(): void
    {
        $type = new FalseT();

        self::assertSame(self::VALUE, $type->asFlatValue());
    }

    public function testModify(): void
    {
        $type = new FalseT();
        // The new value
        $newValue = true;

        $modified = $type->modify(static fn (bool $subject): bool => $newValue);

        // Original should be unmodified
        self::assertSame(self::VALUE, $type->asValue());
        // New should be unmodified and always false
        self::assertNotSame($newValue, $modified->asValue());
    }

    /**
     * @throws JsonException
     */
    public function testJsonSerialize(): void
    {
        $type = new FalseT();

        self::assertSame(json_encode(self::VALUE, JSON_THROW_ON_ERROR), json_encode($type, JSON_THROW_ON_ERROR));
    }
}
