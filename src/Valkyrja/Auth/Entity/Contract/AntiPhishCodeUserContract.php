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

interface AntiPhishCodeUserContract extends UserContract
{
    /**
     * Get the anti-phishing code field.
     */
    public static function getAntiPhishCodeField(): string;

    /**
     * Get the date when the anti-phishing code was modified field.
     */
    public static function getDateAntiPhishCodeModifiedField(): string;
}
