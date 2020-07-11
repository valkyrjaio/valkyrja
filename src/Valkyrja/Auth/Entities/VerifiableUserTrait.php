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
 * Trait VerifiableUserTrait.
 *
 * @author Melech Mizrachi
 */
trait VerifiableUserTrait
{
    /**
     * The verified field.
     *
     * @var string
     */
    protected static string $verifiedField = UserField::VERIFIED;

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
