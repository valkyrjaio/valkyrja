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

namespace Valkyrja\Auth\Store;

use Valkyrja\Auth\Data\Retrieval\Contract\Retrieval;
use Valkyrja\Auth\Entity\Contract\User;
use Valkyrja\Auth\Store\Contract\Store as Contract;

/**
 * Class NullStore.
 *
 * @author Melech Mizrachi
 *
 * @template U of User
 *
 * @implements Contract<U>
 */
class NullStore implements Contract
{
    /**
     * @inheritDoc
     */
    public function retrieve(Retrieval $retrieval, string $user): User|null
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function create(User $user): void
    {
    }

    /**
     * @inheritDoc
     */
    public function update(User $user): void
    {
    }
}
