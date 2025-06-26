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

use Valkyrja\Auth\AuthenticationAttempt;
use Valkyrja\Auth\AuthenticationRetrieval;
use Valkyrja\Auth\User;

/**
 * Class NullAdapter.
 *
 * @author Melech Mizrachi
 */
abstract class NullAdapter2 extends Adapter2
{
    /**
     * @inheritDoc
     */
    public function authenticate(AuthenticationAttempt $attempt): User|null
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function retrieve(AuthenticationRetrieval $retrieval): User|null
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function save(User $user): void
    {
    }
}
