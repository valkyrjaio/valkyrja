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

namespace Valkyrja\Auth\Adapters;

use Valkyrja\Auth\User;

/**
 * Class NullAdapter.
 *
 * @author Melech Mizrachi
 */
class NullAdapter extends Adapter
{
    /**
     * @inheritDoc
     */
    public function authenticate(User $user): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function retrieve(User $user): User|null
    {
        return $user;
    }

    /**
     * @inheritDoc
     */
    public function retrieveByResetToken(User $user, string $resetToken): User|null
    {
        return $user;
    }

    /**
     * @inheritDoc
     */
    public function retrieveById(User $user): User
    {
        return $user;
    }

    /**
     * @inheritDoc
     */
    public function save(User $user): void
    {
    }

    /**
     * @inheritDoc
     */
    public function create(User $user): bool
    {
        return true;
    }
}
