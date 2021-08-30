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
 * Interface Policy.
 *
 * @author Melech Mizrachi
 */
interface Policy
{
    /**
     * Before authorization check.
     *
     * @return bool|null
     */
    public function before(): ?bool;

    /**
     * After authorization check.
     *
     * @return bool|null
     */
    public function after(): ?bool;

    /**
     * Check if the authenticated user is authorized.
     *
     * @param string $action The action to check if authorized for
     *
     * @return bool
     */
    public function isAuthorized(string $action): bool;
}
