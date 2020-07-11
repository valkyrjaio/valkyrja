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

use Valkyrja\Auth\Constants\Field;

/**
 * Trait VerifiableUserTrait.
 *
 * @author Melech Mizrachi
 */
trait VerifiableUserTrait
{
    /**
     * The email field.
     *
     * @var string
     */
    protected static string $emailField = Field::EMAIL;

    /**
     * The verified field.
     *
     * @var string
     */
    protected static string $verifiedField = Field::VERIFIED;

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
     * Get the verified field.
     *
     * @return string
     */
    public static function getVerifiedField(): string
    {
        return static::$verifiedField;
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

    /**
     * Get the verified field value.
     *
     * @return bool
     */
    public function getVerifiedFieldValue(): bool
    {
        return $this->{static::$verifiedField};
    }

    /**
     * Set the verified field value.
     *
     * @param bool $verified Whether the user is verified
     *
     * @return void
     */
    public function setVerifiedFieldValue(bool $verified): void
    {
        $this->{static::$verifiedField} = $verified;
    }
}
