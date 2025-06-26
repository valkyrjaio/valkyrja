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

use Valkyrja\Auth\Adapter\Contract\Adapter2 as Contract;
use Valkyrja\Auth\Config;
use Valkyrja\Auth\Entity\Contract\User;

use const PASSWORD_DEFAULT;

/**
 * Class Adapter.
 *
 * @author Melech Mizrachi
 */
abstract class Adapter2 implements Contract
{
    /**
     * Adapter constructor.
     *
     * @param class-string<User> $user
     */
    public function __construct(
        protected string $user,
        protected Config|array $config
    ) {
    }

    /**
     * @inheritDoc
     */
    protected function verifyPassword(string $password, string $attemptedPassword): bool
    {
        return password_verify($attemptedPassword, $password);
    }

    /**
     * Determine if a password attempt verifies against a user's existing password.
     *
     * @param User   $user
     * @param string $attemptedPassword
     *
     * @return bool
     */
    protected function verifyUserPassword(User $user, string $attemptedPassword): bool
    {
        return $this->verifyPassword($user->{$user::getPasswordField()}, $attemptedPassword);
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
