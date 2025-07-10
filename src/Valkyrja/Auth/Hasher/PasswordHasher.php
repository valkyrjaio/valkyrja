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

namespace Valkyrja\Auth\Hasher;

use Valkyrja\Auth\Hasher\Contract\PasswordHasher as Contract;

use const PASSWORD_DEFAULT;

/**
 * Class PasswordHasher.
 *
 * @author Melech Mizrachi
 */
class PasswordHasher implements Contract
{
    /**
     * Hash a given password.
     *
     * @param non-empty-string $password The password to hash
     *
     * @return non-empty-string
     */
    public function hashPassword(string $password): string
    {
        /** @var non-empty-string $hashedPassword */
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        return $hashedPassword;
    }

    /**
     * Compare an plain text password with a given hashed password.
     *
     * @param string $password       The plain text password
     * @param string $hashedPassword The hashed password
     *
     * @return bool
     */
    public function confirmPassword(string $password, string $hashedPassword): bool
    {
        return password_verify($password, $hashedPassword);
    }
}
