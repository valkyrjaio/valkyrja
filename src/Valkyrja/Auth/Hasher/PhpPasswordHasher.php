<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Auth\Hasher;

use Override;
use Valkyrja\Auth\Hasher\Contract\PasswordHasherContract;

use function password_hash;
use function password_verify;

use const PASSWORD_DEFAULT;

class PhpPasswordHasher implements PasswordHasherContract
{
    /**
     * Hash a given password.
     *
     * @param non-empty-string $password The password to hash
     *
     * @return non-empty-string
     */
    #[Override]
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
     */
    #[Override]
    public function confirmPassword(string $password, string $hashedPassword): bool
    {
        return password_verify($password, $hashedPassword);
    }
}
