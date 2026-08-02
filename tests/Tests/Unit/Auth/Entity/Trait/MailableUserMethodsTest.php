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
use Valkyrja\Auth\Entity\Trait\MailableUserMethods;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class MailableUserMethodsTest extends TestCase
{
    public function testGetEmailField(): void
    {
        $user = new class {
            use MailableUserMethods;
        };

        self::assertSame(UserField::EMAIL, $user::getEmailField());
    }
}
