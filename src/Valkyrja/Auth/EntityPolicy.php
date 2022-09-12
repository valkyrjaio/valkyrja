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
 * Interface EntityPolicy.
 *
 * @author Melech Mizrachi
 */
interface EntityPolicy
{
    /**
     * Get the entity class name that's associated with this policy.
     *
     * @return string
     */
    public static function getEntityClassName(): string;
}
