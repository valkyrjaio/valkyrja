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
use Valkyrja\Auth\Data\Retrieval\RetrievalById;
use Valkyrja\Auth\Entity\Contract\User;
use Valkyrja\Auth\Exception\InvalidUserException;
use Valkyrja\Auth\Store\Contract\Store as Contract;

/**
 * Class InMemoryStore.
 *
 * @author Melech Mizrachi
 *
 * @template U of User
 *
 * @implements Contract<U>
 */
class InMemoryStore implements Contract
{
    /**
     * InMemoryStore construct.
     *
     * @param U[] $users The users
     */
    public function __construct(
        protected array $users = [],
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function retrieve(Retrieval $retrieval, string $user): User|null
    {
        $retrievalFields = $retrieval->getRetrievalFields($user);

        return $this->getUserViaRetrievalFields($retrievalFields);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function create(User $user): void
    {
        $this->users[] = clone $user;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function update(User $user): void
    {
        $existingUser = $this->retrieve(new RetrievalById($user->getIdValue()), $user::class);

        if ($existingUser === null) {
            throw new InvalidUserException('User does not exist.');
        }

        $existingUser->updateProperties($user->asStorableChangedArray());
    }

    /**
     * Filter users via retrieval fields.
     *
     * @param array<non-empty-string, int|non-empty-string|bool|null> $retrievalFields
     * @param U                                                       $user
     *
     * @return bool
     */
    protected function filterUsers(array $retrievalFields, User $user): bool
    {
        $match = null;

        foreach ($retrievalFields as $field => $retrievalField) {
            /** @var mixed $value */
            $value = $user->__get($field);

            if ($match === null) {
                $match = $value === $retrievalField;
            } else {
                $match = $match && $value === $retrievalField;
            }
        }

        return $match ?? false;
    }

    /**
     * Get user via retrieval fields.
     *
     * @param array<non-empty-string, int|non-empty-string|bool|null> $retrievalFields
     *
     * @return U|null
     */
    protected function getUserViaRetrievalFields(array $retrievalFields): User|null
    {
        $users = $this->users;

        $filteredUsers = array_filter(
            $users,
            /**
             * @param U $user
             *
             * @return bool
             */
            fn (User $user): bool => $this->filterUsers($retrievalFields, $user)
        );

        return $filteredUsers[0] ?? null;
    }
}
