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

namespace Valkyrja\Auth\Hasher\Contract;

/**
 * Interface PasswordHasherContract.
 *
 * @author Melech Mizrachi
 */
interface PasswordHasherContract
{
    /**
     * Hash a given password.
     *
     * @param non-empty-string $password The password to hash
     *
     * @return non-empty-string
     */
    public function hashPassword(string $password): string;

    /**
     * Compare an plain text password with a given hashed password.
     *
     * @param string $password       The plain text password
     * @param string $hashedPassword The hashed password
     *
     * @return bool
     */
    public function confirmPassword(string $password, string $hashedPassword): bool;
}
