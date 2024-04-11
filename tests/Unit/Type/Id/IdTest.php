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
use Valkyrja\Type\Types\Id;

class IdTest extends TestCase
{
    protected const INT_VALUE    = 1;
    protected const STRING_VALUE = 'id';

    public function testIntValue(): void
    {
        $type = new Id(self::INT_VALUE);

        self::assertSame(self::INT_VALUE, $type->asValue());
    }

    public function testStringValue(): void
    {
        $type = new Id(self::STRING_VALUE);

        self::assertSame(self::STRING_VALUE, $type->asValue());
    }

    public function testIntFromValue(): void
    {
        $typeFromValue = Id::fromValue(self::INT_VALUE);

        self::assertSame(self::INT_VALUE, $typeFromValue->asValue());
    }

    public function testStringFromValue(): void
    {
        $typeFromValue = Id::fromValue(self::STRING_VALUE);

        self::assertSame(self::STRING_VALUE, $typeFromValue->asValue());
    }

    public function testIntAsFlatValue(): void
    {
        $type = new Id(self::INT_VALUE);

        self::assertSame(self::INT_VALUE, $type->asFlatValue());
    }

    public function testStringAsFlatValue(): void
    {
        $type = new Id(self::STRING_VALUE);

        self::assertSame(self::STRING_VALUE, $type->asFlatValue());
    }

    public function testIntModify(): void
    {
        $type = new Id(self::INT_VALUE);
        // The new value
        $newValue = 2;

        $modified = $type->modify(static fn (int $subject): int => $newValue);

        // Original should be unmodified
        self::assertSame(self::INT_VALUE, $type->asValue());
        // New should be modified
        self::assertSame($newValue, $modified->asValue());
    }

    public function testStringModify(): void
    {
        $type = new Id(self::STRING_VALUE);
        // The new value
        $newValue = 'id2';

        $modified = $type->modify(static fn (string $subject): string => $newValue);

        // Original should be unmodified
        self::assertSame(self::STRING_VALUE, $type->asValue());
        // New should be modified
        self::assertSame($newValue, $modified->asValue());
    }

    public function testIntJsonSerialize(): void
    {
        $type = new Id(self::INT_VALUE);

        self::assertSame(json_encode(self::INT_VALUE), json_encode($type));
    }

    public function testStringJsonSerialize(): void
    {
        $type = new Id(self::STRING_VALUE);

        self::assertSame(json_encode(self::STRING_VALUE), json_encode($type));
    }
}
