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
use Valkyrja\Auth\Entity\Trait\VerifiableUserMethods;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class VerifiableUserMethodsTest extends TestCase
{
    public function testGetIsVerifiedField(): void
    {
        $user = new class {
            use VerifiableUserMethods;
        };

        self::assertSame(UserField::IS_VERIFIED, $user::getIsVerifiedField());
    }
}
