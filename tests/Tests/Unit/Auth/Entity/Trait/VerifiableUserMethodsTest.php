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
