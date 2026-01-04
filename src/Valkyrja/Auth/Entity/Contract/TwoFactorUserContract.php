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

interface TwoFactorUserContract extends UserContract
{
    /**
     * Get the two factor code field.
     */
    public static function getTwoFactorCodeField(): string;

    /**
     * Get the date the two factor code was generated field.
     */
    public static function getDateTwoFactorCodeGeneratedField(): string;
}
