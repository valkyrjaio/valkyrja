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

namespace Valkyrja\Auth\Policy;

use Valkyrja\Auth\Entity\Contract\User;
use Valkyrja\Auth\Policy\Contract\Policy as Contract;
use Valkyrja\Auth\Repository\Contract\Repository;

use function is_bool;
use function method_exists;

/**
 * Abstract Class Policy.
 *
 * @author Melech Mizrachi
 */
abstract class Policy implements Contract
{
    /**
     * The user.
     *
     * @var User
     */
    protected User $user;

    /**
     * Policy constructor.
     *
     * @param Repository $repository The repository
     */
    public function __construct(
        protected Repository $repository
    ) {
        $this->user = $repository->getUser();
    }

    /**
     * @inheritDoc
     */
    public function before(string &$action): ?bool
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function after(bool $isAuthorized, string $action): ?bool
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function isAuthorized(string $action): bool
    {
        if ($beforeAuthorized = $this->before($action)) {
            return $beforeAuthorized;
        }

        $isAuthorized = $this->checkIsAuthorized($action);

        return $this->after($isAuthorized, $action)
            ?? $isAuthorized;
    }

    /**
     * Check if the action is authorized.
     *
     * @param string $action The action to check if authorized for
     *
     * @return bool
     */
    protected function checkIsAuthorized(string $action): bool
    {
        return method_exists($this, $action)
            && is_bool($isAuthorized = $this->$action())
            && $isAuthorized;
    }
}
