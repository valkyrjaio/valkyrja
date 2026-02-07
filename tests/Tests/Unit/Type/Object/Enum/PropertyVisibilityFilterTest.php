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

namespace Valkyrja\Tests\Unit\Type\Object\Enum;

use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\Object\Enum\PropertyVisibilityFilter;

class PropertyVisibilityFilterTest extends TestCase
{
    public function testTotalCaseCount(): void
    {
        self::assertCount(7, PropertyVisibilityFilter::cases());
    }

    public function testAllShouldIncludePublic(): void
    {
        self::assertTrue(PropertyVisibilityFilter::ALL->shouldIncludePublic());
    }

    public function testAllShouldIncludeProtected(): void
    {
        self::assertTrue(PropertyVisibilityFilter::ALL->shouldIncludeProtected());
    }

    public function testAllShouldIncludePrivate(): void
    {
        self::assertTrue(PropertyVisibilityFilter::ALL->shouldIncludePrivate());
    }

    public function testPublicShouldIncludePublic(): void
    {
        self::assertTrue(PropertyVisibilityFilter::PUBLIC->shouldIncludePublic());
    }

    public function testPublicShouldNotIncludeProtected(): void
    {
        self::assertFalse(PropertyVisibilityFilter::PUBLIC->shouldIncludeProtected());
    }

    public function testPublicShouldNotIncludePrivate(): void
    {
        self::assertFalse(PropertyVisibilityFilter::PUBLIC->shouldIncludePrivate());
    }

    public function testProtectedShouldNotIncludePublic(): void
    {
        self::assertFalse(PropertyVisibilityFilter::PROTECTED->shouldIncludePublic());
    }

    public function testProtectedShouldIncludeProtected(): void
    {
        self::assertTrue(PropertyVisibilityFilter::PROTECTED->shouldIncludeProtected());
    }

    public function testProtectedShouldNotIncludePrivate(): void
    {
        self::assertFalse(PropertyVisibilityFilter::PROTECTED->shouldIncludePrivate());
    }

    public function testPrivateShouldNotIncludePublic(): void
    {
        self::assertFalse(PropertyVisibilityFilter::PRIVATE->shouldIncludePublic());
    }

    public function testPrivateShouldNotIncludeProtected(): void
    {
        self::assertFalse(PropertyVisibilityFilter::PRIVATE->shouldIncludeProtected());
    }

    public function testPrivateShouldIncludePrivate(): void
    {
        self::assertTrue(PropertyVisibilityFilter::PRIVATE->shouldIncludePrivate());
    }

    public function testPublicProtectedShouldIncludePublic(): void
    {
        self::assertTrue(PropertyVisibilityFilter::PUBLIC_PROTECTED->shouldIncludePublic());
    }

    public function testPublicProtectedShouldIncludeProtected(): void
    {
        self::assertTrue(PropertyVisibilityFilter::PUBLIC_PROTECTED->shouldIncludeProtected());
    }

    public function testPublicProtectedShouldNotIncludePrivate(): void
    {
        self::assertFalse(PropertyVisibilityFilter::PUBLIC_PROTECTED->shouldIncludePrivate());
    }

    public function testPublicPrivateShouldIncludePublic(): void
    {
        self::assertTrue(PropertyVisibilityFilter::PUBLIC_PRIVATE->shouldIncludePublic());
    }

    public function testPublicPrivateShouldNotIncludeProtected(): void
    {
        self::assertFalse(PropertyVisibilityFilter::PUBLIC_PRIVATE->shouldIncludeProtected());
    }

    public function testPublicPrivateShouldIncludePrivate(): void
    {
        self::assertTrue(PropertyVisibilityFilter::PUBLIC_PRIVATE->shouldIncludePrivate());
    }

    public function testPrivateProtectedShouldNotIncludePublic(): void
    {
        self::assertFalse(PropertyVisibilityFilter::PRIVATE_PROTECTED->shouldIncludePublic());
    }

    public function testPrivateProtectedShouldIncludeProtected(): void
    {
        self::assertTrue(PropertyVisibilityFilter::PRIVATE_PROTECTED->shouldIncludeProtected());
    }

    public function testPrivateProtectedShouldIncludePrivate(): void
    {
        self::assertTrue(PropertyVisibilityFilter::PRIVATE_PROTECTED->shouldIncludePrivate());
    }
}
