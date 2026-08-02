<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Filesystem\Enum;

use Valkyrja\Filesystem\Enum\Visibility;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class VisibilityTest extends TestCase
{
    public function testPublicCase(): void
    {
        self::assertSame('public', Visibility::PUBLIC->value);
    }

    public function testPrivateCase(): void
    {
        self::assertSame('private', Visibility::PRIVATE->value);
    }

    public function testFromString(): void
    {
        self::assertSame(Visibility::PUBLIC, Visibility::from('public'));
        self::assertSame(Visibility::PRIVATE, Visibility::from('private'));
    }

    public function testTryFromString(): void
    {
        self::assertSame(Visibility::PUBLIC, Visibility::tryFrom('public'));
        self::assertSame(Visibility::PRIVATE, Visibility::tryFrom('private'));
        self::assertNull(Visibility::tryFrom('invalid'));
    }

    public function testCases(): void
    {
        $cases = Visibility::cases();

        self::assertCount(2, $cases);
        self::assertContains(Visibility::PUBLIC, $cases);
        self::assertContains(Visibility::PRIVATE, $cases);
    }
}
