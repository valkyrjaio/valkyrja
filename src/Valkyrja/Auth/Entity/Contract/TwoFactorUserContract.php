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
