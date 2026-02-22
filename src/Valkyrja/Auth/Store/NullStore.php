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

use Override;
use Valkyrja\Auth\Data\Retrieval\Contract\RetrievalContract;
use Valkyrja\Auth\Entity\Contract\UserContract;
use Valkyrja\Auth\Store\Contract\StoreContract;
use Valkyrja\Auth\Throwable\Exception\InvalidRetrievableUserException;

/**
 * @template U of UserContract
 *
 * @implements StoreContract<U>
 */
class NullStore implements StoreContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function hasRetrievable(RetrievalContract $retrieval, string $user): bool
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function retrieve(RetrievalContract $retrieval, string $user): UserContract
    {
        throw new InvalidRetrievableUserException('A user could not be retrieved with the given criteria');
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function create(UserContract $user): void
    {
        throw new InvalidRetrievableUserException('A user could not be retrieved with the given criteria');
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function update(UserContract $user): void
    {
        throw new InvalidRetrievableUserException('A user could not be retrieved with the given criteria');
    }
}
