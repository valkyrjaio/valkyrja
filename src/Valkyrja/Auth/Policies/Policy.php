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

namespace Valkyrja\Auth\Gates;

use Valkyrja\Auth\Policy as Contract;
use Valkyrja\Auth\Repository;
use Valkyrja\Auth\User;

/**
 * Abstract Class Policy.
 *
 * @author Melech Mizrachi
 */
abstract class Policy implements Contract
{
    /**
     * The repository.
     *
     * @var Repository
     */
    protected Repository $repository;

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
    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
        $this->user       = $repository->getUser();
    }

    /**
     * Before authorization check.
     *
     * @return bool|null
     */
    public function before(): ?bool
    {
        return null;
    }

    /**
     * After authorization check.
     *
     * @return bool|null
     */
    public function after(): ?bool
    {
        return null;
    }

    /**
     * Check if the authenticated user is authorized.
     *
     * @param string $action The action to check if authorized for
     *
     * @return bool
     */
    public function isAuthorized(string $action): bool
    {
        return $this->before()
            ?? $this->after()
            ?? (method_exists($this, $action) ? $this->$action() : false);
    }
}
