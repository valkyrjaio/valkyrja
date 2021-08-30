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
 * Interface Gate.
 *
 * @author Melech Mizrachi
 */
interface Gate
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
     * @param string      $action The action to check if authorized for
     * @param string|null $policy [optional] The policy
     *
     * @return bool
     */
    public function isAuthorized(string $action, string $policy = null): bool;

    /**
     * Get a policy by name.
     *
     * @param string|null $name [optional] The policy name
     *
     * @return Policy
     */
    public function getPolicy(string $name = null): Policy;
}
