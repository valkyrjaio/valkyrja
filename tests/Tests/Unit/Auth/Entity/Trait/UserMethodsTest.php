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
use Valkyrja\Auth\Entity\Trait\UserMethods;
use Valkyrja\Auth\Throwable\Exception\AuthUnexpectedPasswordValueException;
use Valkyrja\Auth\Throwable\Exception\AuthUnexpectedUsernameValueException;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class UserMethodsTest extends TestCase
{
    public function testStaticFieldGetters(): void
    {
        $user = $this->user();

        self::assertSame(UserField::USERNAME, $user::getUsernameField());
        self::assertSame(UserField::PASSWORD, $user::getPasswordField());
        self::assertSame(UserField::RESET_TOKEN, $user::getResetTokenField());
    }

    public function testGetUsernameValueReturnsStringValue(): void
    {
        $user = $this->user([UserField::USERNAME => 'bob']);

        self::assertSame('bob', $user->getUsernameValue());
    }

    public function testGetUsernameValueThrowsForNonStringValue(): void
    {
        $this->expectException(AuthUnexpectedUsernameValueException::class);

        $this->user()->getUsernameValue();
    }

    public function testGetPasswordValueReturnsStringValue(): void
    {
        $user = $this->user([UserField::PASSWORD => 'secret']);

        self::assertSame('secret', $user->getPasswordValue());
    }

    public function testGetPasswordValueThrowsForNonStringValue(): void
    {
        $this->expectException(AuthUnexpectedPasswordValueException::class);

        $this->user()->getPasswordValue();
    }

    /**
     * @param array<string, mixed> $data
     */
    private function user(array $data = []): object
    {
        return new class($data) {
            use UserMethods;

            /**
             * @param array<string, mixed> $data
             */
            public function __construct(private array $data)
            {
            }

            public function __get(string $name): mixed
            {
                return $this->data[$name] ?? null;
            }
        };
    }
}
