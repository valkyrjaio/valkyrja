<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Auth\Entities;

use Valkyrja\Auth\Enums\Field;

/**
 * Trait LockableUserTrait.
 *
 * @author Melech Mizrachi
 */
trait LockableUserTrait
{
    /**
     * The max number of login attempts before locking.
     *
     * @var int
     */
    protected static int $maxLoginAttempts = 3;

    /**
     * The login attempts field.
     *
     * @var string
     */
    protected static string $loginAttemptsField = Field::LOGIN_ATTEMPTS;

    /**
     * The locked field.
     *
     * @var string
     */
    protected static string $lockedField = Field::LOCKED;

    /**
     * Get the max number login attempts before locking.
     *
     * @return int
     */
    public static function getMaxLoginAttempts(): int
    {
        return static::$maxLoginAttempts;
    }

    /**
     * Get the login attempts field.
     *
     * @return string
     */
    public static function getLoginAttemptsField(): string
    {
        return static::$loginAttemptsField;
    }

    /**
     * Get the locked field.
     *
     * @return string
     */
    public static function getLockedField(): string
    {
        return static::$lockedField;
    }

    /**
     * Get the login attempts field value.
     *
     * @return int
     */
    public function getLoginAttemptsFieldValue(): int
    {
        return $this->{static::$maxLoginAttempts};
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
        $this->{static::$maxLoginAttempts} = $loginAttempts;
    }

    /**
     * Get the locked field value.
     *
     * @return bool
     */
    public function getLockedFieldValue(): bool
    {
        return $this->{static::$lockedField};
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
        $this->{static::$lockedField} = $locked;
    }
}
