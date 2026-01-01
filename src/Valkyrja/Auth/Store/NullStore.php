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
use Valkyrja\Auth\Store\Contract\StoreContract as Contract;

/**
 * @template U of UserContract
 *
 * @implements Contract<U>
 */
class NullStore implements Contract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function retrieve(RetrievalContract $retrieval, string $user): UserContract|null
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function create(UserContract $user): void
    {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function update(UserContract $user): void
    {
    }
}
