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

interface PinUserContract extends UserContract
{
    /**
     * Get the pin field.
     */
    public static function getPinField(): string;

    /**
     * Get the date pin was modified field.
     */
    public static function getDatePinModifiedField(): string;
}
