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
 * Interface AntiPhishCodeUser.
 *
 * @author Melech Mizrachi
 */
interface AntiPhishCodeUser extends User
{
    /**
     * Get the anti-phishing code field.
     *
     * @return string
     */
    public static function getAntiPhishCodeField(): string;

    /**
     * Get the date when the anti-phishing code was modified field.
     *
     * @return string
     */
    public static function getDateAntiPhishCodeModifiedField(): string;
}
