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

namespace Valkyrja\Auth\Entities;

use Valkyrja\Auth\Constants\UserField;

/**
 * Trait LockableUserTrait.
 *
 * @author Melech Mizrachi
 */
trait LockableUserTrait
{
    /**
     * Get the max number login attempts before locking.
     *
     * @return int
     */
    public static function getMaxLoginAttempts(): int
    {
        return 3;
    }

    /**
     * Get the login attempts field.
     *
     * @return string
     */
    public static function getLoginAttemptsField(): string
    {
        return UserField::LOGIN_ATTEMPTS;
    }

    /**
     * Get the locked field.
     *
     * @return string
     */
    public static function getLockedField(): string
    {
        return UserField::LOCKED;
    }

    /**
     * Get the login attempts field value.
     *
     * @return int
     */
    public function getLoginAttemptsFieldValue(): int
    {
        return $this->{static::getLoginAttemptsField()};
    }

    /**
     * Set the login attempts field value.
     *
     * @param int $loginAttempts
     *
     * @return void
     */
    public function setLoginAttemptsFieldValue(int $loginAttempts): void
    {
        $this->{static::getLoginAttemptsField()} = $loginAttempts;
    }

    /**
     * Get the locked field value.
     *
     * @return bool
     */
    public function getLockedFieldValue(): bool
    {
        return $this->{static::getLockedField()};
    }

    /**
     * Set the locked field value.
     *
     * @param bool $locked
     *
     * @return void
     */
    public function setLockedFieldValue(bool $locked): void
    {
        $this->{static::getLockedField()} = $locked;
    }
}
