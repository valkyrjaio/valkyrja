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

namespace Valkyrja\Tests\Unit\Filesystem\Enum;

use Valkyrja\Filesystem\Enum\Visibility;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class VisibilityTest extends TestCase
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
