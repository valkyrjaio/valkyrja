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

namespace Valkyrja\Auth\Gate\Contract;

use Valkyrja\Auth\Policy\Contract\Policy;

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
     * @param string                    $action The action to check if authorized for
     * @param class-string<Policy>|null $policy [optional] The policy
     *
     * @return bool|null
     */
    public function before(string &$action, string|null &$policy = null): bool|null;

    /**
     * After authorization check.
     *
     * @param bool                      $isAuthorized Whether the action is authorized per the policy
     * @param string                    $action       The action to check if authorized for
     * @param class-string<Policy>|null $policy       [optional] The policy
     *
     * @return bool|null
     */
    public function after(bool $isAuthorized, string $action, string|null $policy = null): bool|null;

    /**
     * Check if the authenticated user is authorized.
     *
     * @param string                    $action The action to check if authorized for
     * @param class-string<Policy>|null $policy [optional] The policy
     *
     * @return bool
     */
    public function isAuthorized(string $action, string|null $policy = null): bool;
}
