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
 * Interface VerifiableUser.
 *
 * @author Melech Mizrachi
 */
interface VerifiableUser extends User
{
    /**
     * Get the email field.
     *
     * @return string
     */
    public static function getEmailField(): string;

    /**
     * Get the verified field.
     *
     * @return string
     */
    public static function getVerifiedField(): string;

    /**
     * Get the email field value.
     *
     * @return string
     */
    public function getEmailFieldValue(): string;

    /**
     * Set the email field value.
     *
     * @param string $email The email
     *
     * @return void
     */
    public function setEmailFieldValue(string $email): void;

    /**
     * Get the verified field value.
     *
     * @return bool
     */
    public function getVerifiedFieldValue(): bool;

    /**
     * Set the verified field value.
     *
     * @param bool $verified Whether the user is verified
     *
     * @return void
     */
    public function setVerifiedFieldValue(bool $verified): void;
}
