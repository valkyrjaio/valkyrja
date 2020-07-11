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

namespace Valkyrja\Auth;

/**
 * Interface LockableUser.
 *
 * @author Melech Mizrachi
 */
interface LockableUser extends User
{
    /**
     * Get the max number login attempts before locking.
     *
     * @return int
     */
    public static function getMaxLoginAttempts(): int;

    /**
     * Get the login attempts field.
     *
     * @return string
     */
    public static function getLoginAttemptsField(): string;

    /**
     * Get the locked field.
     *
     * @return string
     */
    public static function getLockedField(): string;

    /**
     * Get the login attempts field value.
     *
     * @return int
     */
    public function getLoginAttemptsFieldValue(): int;

    /**
     * Set the login attempts field value.
     *
     * @param int $loginAttempts
     *
     * @return void
     */
    public function setLoginAttemptsFieldValue(int $loginAttempts): void;

    /**
     * Get the locked field value.
     *
     * @return bool
     */
    public function getLockedFieldValue(): bool;

    /**
     * Set the locked field value.
     *
     * @param bool $locked
     *
     * @return void
     */
    public function setLockedFieldValue(bool $locked): void;
}
