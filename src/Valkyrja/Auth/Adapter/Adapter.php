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

namespace Valkyrja\Auth\Adapter;

use Exception;
use Valkyrja\Auth\Adapter\Contract\Adapter as Contract;
use Valkyrja\Auth\Config;
use Valkyrja\Auth\Entity\Contract\User;
use Valkyrja\Type\BuiltIn\Support\Str;

use const PASSWORD_DEFAULT;

/**
 * Class Adapter.
 *
 * @author Melech Mizrachi
 */
abstract class Adapter implements Contract
{
    /**
     * Adapter constructor.
     *
     * @param Config|array<string, mixed> $config The config
     */
    public function __construct(
        protected Config|array $config
    ) {
    }

    /**
     * Determine if a password verifies against the user's password.
     *
     * @param User   $user
     * @param string $password
     *
     * @return bool
     */
    public function verifyPassword(User $user, string $password): bool
    {
        return password_verify($password, $user->getPasswordValue());
    }

    /**
     * Update a user's password.
     *
     * @param User   $user
     * @param string $password
     *
     * @return void
     */
    public function updatePassword(User $user, string $password): void
    {
        $resetTokenField = $user::getResetTokenField();

        $user->__set($resetTokenField, null);
        $user->__set($user::getPasswordField(), $this->hashPassword($password));

        $this->save($user);
    }

    /**
     * Generate a reset token for a user.
     *
     * @param User $user
     *
     * @throws Exception
     *
     * @return void
     */
    public function updateResetToken(User $user): void
    {
        $user->__set($user::getResetTokenField(), Str::random());

        $this->save($user);
    }

    /**
     * Hash a plain password.
     *
     * @param string $password
     *
     * @return string
     */
    protected function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
}
