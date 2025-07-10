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
use Valkyrja\Auth\Data\Retrieval\Contract\Retrieval;
use Valkyrja\Auth\Entity\Contract\User;
use Valkyrja\Auth\Store\Contract\Store as Contract;
use Valkyrja\Orm\Contract\Manager;
use Valkyrja\Orm\Data\Value;
use Valkyrja\Orm\Data\Where;
use Valkyrja\Orm\Repository\Contract\Repository;

/**
 * Class OrmStore.
 *
 * @author Melech Mizrachi
 *
 * @template U of User
 *
 * @implements Contract<U>
 */
class OrmStore implements Contract
{
    public function __construct(
        protected Manager $orm,
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function retrieve(Retrieval $retrieval, string $user): User|null
    {
        /** @var Repository<U> $repository */
        $repository = $this->orm->createRepository($user);

        $where = [];

        foreach ($retrieval->getRetrievalFields($user) as $field => $value) {
            $where[] = new Where(
                value: new Value(
                    name: $field,
                    value: $value
                )
            );
        }

        return $repository->findBy(...$where);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function create(User $user): void
    {
        $repository = $this->orm->createRepository($user::class);

        $repository->create($user);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function update(User $user): void
    {
        $repository = $this->orm->createRepository($user::class);

        $repository->update($user);
    }
}
