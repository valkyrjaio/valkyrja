<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Auth\Store;

use Override;
use Valkyrja\Auth\Data\Retrieval\Contract\RetrievalContract;
use Valkyrja\Auth\Entity\Contract\UserContract;
use Valkyrja\Auth\Store\Contract\StoreContract;
use Valkyrja\Auth\Throwable\Exception\AuthInvalidRetrievableUserException;

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
        throw new AuthInvalidRetrievableUserException('A user could not be retrieved with the given criteria');
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function create(UserContract $user): void
    {
        throw new AuthInvalidRetrievableUserException('A user could not be retrieved with the given criteria');
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function update(UserContract $user): void
    {
        throw new AuthInvalidRetrievableUserException('A user could not be retrieved with the given criteria');
    }
}
