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
use Valkyrja\Orm\Data\Value;
use Valkyrja\Orm\Data\Where;
use Valkyrja\Orm\Manager\Contract\ManagerContract;
use Valkyrja\Orm\Repository\Contract\RepositoryContract;

/**
 * @template U of UserContract
 *
 * @implements StoreContract<U>
 */
class OrmStore implements StoreContract
{
    public function __construct(
        protected ManagerContract $orm,
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function hasRetrievable(RetrievalContract $retrieval, string $user): bool
    {
        return $this->internalRetrieval($retrieval, $user) !== null;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function retrieve(RetrievalContract $retrieval, string $user): UserContract
    {
        return $this->internalRetrieval($retrieval, $user)
            ?? throw new InvalidRetrievableUserException('A user could not be retrieved with the given criteria');
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function create(UserContract $user): void
    {
        $repository = $this->orm->createRepository($user::class);

        $repository->create($user);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function update(UserContract $user): void
    {
        $repository = $this->orm->createRepository($user::class);

        $repository->update($user);
    }

    /**
     * Retrieve a user with given criteria.
     *
     * @param RetrievalContract $retrieval The retrieval criteria
     * @param class-string<U>   $user      The user class
     *
     * @return U|null
     */
    protected function internalRetrieval(RetrievalContract $retrieval, string $user): UserContract|null
    {
        /** @var RepositoryContract<U> $repository */
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
}
