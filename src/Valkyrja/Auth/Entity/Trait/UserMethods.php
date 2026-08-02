<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Auth\Entity\Trait;

use Valkyrja\Auth\Constant\UserField;
use Valkyrja\Auth\Throwable\Exception\AuthUnexpectedPasswordValueException;
use Valkyrja\Auth\Throwable\Exception\AuthUnexpectedUsernameValueException;

use function is_string;

trait UserMethods
{
    /**
     * @inheritDoc
     *
     * @return non-empty-string
     */
    public static function getUsernameField(): string
    {
        return UserField::USERNAME;
    }

    /**
     * @inheritDoc
     *
     * @return non-empty-string
     */
    public static function getPasswordField(): string
    {
        return UserField::PASSWORD;
    }

    /**
     * @inheritDoc
     *
     * @return non-empty-string
     */
    public static function getResetTokenField(): string
    {
        return UserField::RESET_TOKEN;
    }

    /**
     * @inheritDoc
     *
     * @return non-empty-string
     */
    public function getUsernameValue(): string
    {
        /** @var scalar|object|array<array-key, mixed>|resource|null $value */
        $value = $this->__get(static::getUsernameField());

        if (is_string($value) && $value !== '') {
            /** @var non-empty-string $value */
            return $value;
        }

        throw new AuthUnexpectedUsernameValueException('Username field value should be a string');
    }

    /**
     * @inheritDoc
     *
     * @return non-empty-string
     */
    public function getPasswordValue(): string
    {
        /** @var scalar|object|array<array-key, mixed>|resource|null $value */
        $value = $this->__get(static::getPasswordField());

        if (is_string($value) && $value !== '') {
            /** @var non-empty-string $value */
            return $value;
        }

        throw new AuthUnexpectedPasswordValueException('Password field value should be a string');
    }
}
