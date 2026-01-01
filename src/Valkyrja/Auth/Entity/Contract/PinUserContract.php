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

/**
 * Interface PinUserContract.
 *
 * @author Melech Mizrachi
 */
interface PinUserContract extends UserContract
{
    /**
     * Get the pin field.
     *
     * @return string
     */
    public static function getPinField(): string;

    /**
     * Get the date pin was modified field.
     *
     * @return string
     */
    public static function getDatePinModifiedField(): string;
}
