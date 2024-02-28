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
use Valkyrja\Auth\Exceptions\InvalidUserException;
use Valkyrja\Auth\User;

/**
 * Class InMemoryAdapter.
 *
 * @author Melech Mizrachi
 */
class InMemoryAdapter extends Adapter2
{
    public const USER_DOES_NOT_EXIST_EXCEPTION_MESSAGE = 'User does not exist.';
    public const USER_DOES_EXIST_EXCEPTION_MESSAGE     = 'User already exists.';

    /**
     * The users.
     *
     * @var User[]
     */
    protected static array $users = [];

    /**
     * Filter users via retrieval fields.
     *
     * @param array $retrievalFields
     * @param User  $user
     *
     * @return bool
     */
    protected static function filterUsers(array $retrievalFields, User $user): bool
    {
        $match = null;

        foreach ($retrievalFields as $field => $retrievalField) {
            $value = $user->__get($field);

            if ($match === null) {
                $match = $value === $retrievalField;
            } else {
                $match = $match && $value === $retrievalField;
            }
        }

        return $match;
    }

    /**
     * @inheritDoc
     */
    public function authenticate(AuthenticationAttempt $attempt): User|null
    {
        $user = $this->retrieve($attempt);

        if ($user !== null && $this->verifyUserPassword($user, $attempt->getPassword())) {
            return $user;
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function retrieve(AuthenticationRetrieval $retrieval): User|null
    {
        $retrievalFields = $retrieval->getRetrievalFields($this->user);

        return $this->getUserViaRetrievalFields($retrievalFields);
    }

    /**
     * @inheritDoc
     */
    public function create(User $user): void
    {
        $retrievalFields = $this->getRetrievalFieldsFromUser($user);
        $existingUser    = $this->getUserViaRetrievalFields($retrievalFields);

        if ($existingUser !== null) {
            throw new InvalidUserException(self::USER_DOES_EXIST_EXCEPTION_MESSAGE);
        }

        static::$users[] = clone $user;
    }

    /**
     * @inheritDoc
     *
     * @throws InvalidUserException
     */
    public function save(User $user): void
    {
        $retrievalFields = $this->getRetrievalFieldsFromUser($user);
        $existingUser    = $this->getUserViaRetrievalFields($retrievalFields);

        if ($existingUser === null) {
            throw new InvalidUserException(static::USER_DOES_NOT_EXIST_EXCEPTION_MESSAGE);
        }

        $existingUser->updateProperties($user->asStorableChangedArray());
    }

    /**
     * Get the retrieval fields from a user object.
     *
     * @param User $user
     *
     * @return array<string, int|string>
     */
    protected function getRetrievalFieldsFromUser(User $user): array
    {
        $retrievalFields       = $user::getAuthenticationFields();
        $retrievalFieldsFilled = [];

        foreach ($retrievalFields as $retrievalField) {
            $retrievalFieldsFilled[$retrievalField] = $user->{$retrievalField};
        }

        return $retrievalFieldsFilled;
    }

    /**
     * Get user via retrieval fields.
     *
     * @param array $retrievalFields
     *
     * @return User|null
     */
    protected function getUserViaRetrievalFields(array $retrievalFields): User|null
    {
        $users = static::$users;

        $filteredUsers = array_filter(
            $users,
            static fn (User $user) => static::filterUsers($retrievalFields, $user)
        );

        return $filteredUsers[0] ?? null;
    }
}
