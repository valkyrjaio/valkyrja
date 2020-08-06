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
 * Trait MailableUserTrait.
 *
 * @author Melech Mizrachi
 */
trait MailableUserTrait
{
    /**
     * The email field.
     *
     * @var string
     */
    protected static string $emailField = UserField::EMAIL;

    /**
     * Get the email field.
     *
     * @return string
     */
    public static function getEmailField(): string
    {
        return static::$emailField;
    }

    /**
     * Get the email field value.
     *
     * @return string
     */
    public function getEmailFieldValue(): string
    {
        return $this->{static::$emailField};
    }

    /**
     * Set the email field value.
     *
     * @param string $email The email
     *
     * @return void
     */
    public function setEmailFieldValue(string $email): void
    {
        $this->{static::$emailField} = $email;
    }
}
