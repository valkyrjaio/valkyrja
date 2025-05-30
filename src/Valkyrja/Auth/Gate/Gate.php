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

namespace Valkyrja\Auth\Gate;

use Valkyrja\Auth\Contract\Auth;
use Valkyrja\Auth\Gate\Contract\Gate as Contract;
use Valkyrja\Auth\Policy\Contract\Policy;
use Valkyrja\Auth\Repository\Contract\Repository;

/**
 * Class Gate.
 *
 * @author Melech Mizrachi
 */
class Gate implements Contract
{
    /**
     * Gate constructor.
     *
     * @param Auth       $auth       The auth service
     * @param Repository $repository The repository
     */
    public function __construct(
        protected Auth $auth,
        protected Repository $repository
    ) {
    }

    /**
     * @inheritDoc
     */
    public function before(string &$action, string|null &$policy = null): bool|null
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function after(bool $isAuthorized, string $action, string|null $policy = null): bool|null
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function isAuthorized(string $action, string|null $policy = null): bool
    {
        if ($beforeAuthorized = $this->before($action, $policy)) {
            return $beforeAuthorized;
        }

        $isAuthorized = $this->checkIsAuthorized($action, $policy);

        return $this->after($isAuthorized, $action, $policy)
            ?? $isAuthorized;
    }

    /**
     * Check if the action/policy combo are authorized.
     *
     * @param string                    $action The action to check if authorized for
     * @param class-string<Policy>|null $policy [optional] The policy
     *
     * @return bool
     */
    protected function checkIsAuthorized(string $action, string|null $policy = null): bool
    {
        return $this->auth->getPolicy(
            $policy,
            $this->repository->getUser()::class,
            $this->repository->getAdapter()::class
        )
                          ->isAuthorized($action);
    }
}
