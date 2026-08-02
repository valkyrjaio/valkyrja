<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Auth\Entity\Contract;

use Valkyrja\Orm\Entity\Contract\EntityContract;

interface UserContract extends EntityContract
{
    /**
     * Get the username field.
     *
     * @return non-empty-string
     */
    public static function getUsernameField(): string;

    /**
     * Get the hashed password field.
     *
     * @return non-empty-string
     */
    public static function getPasswordField(): string;

    /**
     * Get the reset token field.
     *
     * @return non-empty-string
     */
    public static function getResetTokenField(): string;

    /**
     * Get the username value.
     *
     * @return non-empty-string
     */
    public function getUsernameValue(): string;

    /**
     * Get the password value.
     *
     * @return non-empty-string
     */
    public function getPasswordValue(): string;
}
