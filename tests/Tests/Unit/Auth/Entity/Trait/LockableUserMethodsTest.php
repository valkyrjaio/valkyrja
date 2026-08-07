<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Auth\Entity\Trait;

use Valkyrja\Auth\Constant\UserField;
use Valkyrja\Tests\Fixtures\Auth\Entity\LockableUserMethodsFixture;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class LockableUserMethodsTest extends TestCase
{
    public function testGetMaxLoginAttempts(): void
    {
        self::assertSame(3, $this->user()::getMaxLoginAttempts());
    }

    public function testGetLoginAttemptsField(): void
    {
        self::assertSame(UserField::LOGIN_ATTEMPTS, $this->user()::getLoginAttemptsField());
    }

    public function testGetIsLockedField(): void
    {
        self::assertSame(UserField::IS_LOCKED, $this->user()::getIsLockedField());
    }

    private function user(): LockableUserMethodsFixture
    {
        return new LockableUserMethodsFixture();
    }
}
