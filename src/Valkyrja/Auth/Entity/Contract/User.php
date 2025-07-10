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

namespace Valkyrja\Auth\Entity\Contract;

use Valkyrja\Orm\Entity\Contract\Entity;

/**
 * Interface User.
 *
 * @author Melech Mizrachi
 */
interface User extends Entity
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
