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
 * Interface Guard.
 *
 * @author Melech Mizrachi
 */
interface Guard
{
    /**
     * Check if the authenticated user is authorized and passes a gate test.
     *
     * @param string $guard
     *
     * @return bool
     */
    public function isAuthorized(string $guard): bool;
}
