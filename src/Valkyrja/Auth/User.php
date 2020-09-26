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

use Valkyrja\ORM\Entity;

/**
 * Interface User.
 *
 * @author Melech Mizrachi
 */
interface User extends Entity
{
    /**
     * Get the auth repository.
     *
     * @return string|null
     */
    public static function getAuthRepository(): ?string;

    /**
     * Get the session id.
     *
     * @return string
     */
    public static function getSessionId(): string;

    /**
     * Get the username field.
     *
     * @return string
     */
    public static function getUsernameField(): string;

    /**
     * Get the hashed password field.
     *
     * @return string
     */
    public static function getPasswordField(): string;

    /**
     * Get the reset token field.
     *
     * @return string
     */
    public static function getResetTokenField(): string;

    /**
     * Get user as an array for storing in a token.
     *
     * @return array
     */
    public function __tokenized(): array;
}
