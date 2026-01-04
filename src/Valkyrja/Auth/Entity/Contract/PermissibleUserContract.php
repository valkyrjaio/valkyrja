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

interface PermissibleUserContract extends UserContract
{
    /**
     * Get whether the user is allowed.
     *
     * @param string $permission The permission
     */
    public function isAllowed(string $permission): bool;

    /**
     * Get whether the user is denied.
     *
     * @param string $permission The permission
     */
    public function isDenied(string $permission): bool;
}
