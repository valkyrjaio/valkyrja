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

namespace Valkyrja\Auth\Adapter;

use Exception;
use Valkyrja\Auth\Adapter\Contract\ORMAdapter as Contract;
use Valkyrja\Auth\Config;
use Valkyrja\Auth\Entity\Contract\User;
use Valkyrja\Auth\Exception\InvalidRegistrationException;
use Valkyrja\Exception\InvalidArgumentException;
use Valkyrja\Orm\Contract\Orm;
use Valkyrja\Orm\QueryBuilder\Contract\QueryBuilder;
use Valkyrja\Orm\Repository\Contract\Repository;

use function is_bool;
use function is_float;
use function is_int;
use function is_string;

/**
 * Class Adapter.
 *
 * @author Melech Mizrachi
 */
class ORMAdapter extends Adapter implements Contract
{
    /**
     * Adapter constructor.
     *
     * @param Orm                         $orm    The orm
     * @param Config|array<string, mixed> $config The config
     */
    public function __construct(
        protected Orm $orm,
        Config|array $config
    ) {
        parent::__construct($config);
    }

    /**
     * @inheritDoc
     */
    public function authenticate(User $user): bool
    {
        $dbUser = $this->retrieve($user);

        // If there is a user and the password matches
        if ($dbUser && $this->verifyPassword($dbUser, $user->getPasswordValue())) {
            // Update the user model with all the properties from the database
            $user->updateProperties($dbUser->asStorableArray());

            return true;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function retrieve(User $user): User|null
    {
        $loginFields = $user::getAuthenticationFields();
        $find        = $this->getUserRepository($user)->find();

        // Iterate through the login fields
        foreach ($loginFields as $loginField) {
            $userField = $user->__get($loginField);

            if ($userField !== null
                && ! is_bool($userField)
                && ! is_string($userField)
                && ! is_int($userField)
                && ! is_float($userField)
                && ! $userField instanceof QueryBuilder
            ) {
                throw new InvalidArgumentException('Login fields should be QueryBuilder|array|string|float|int|bool|null');
            }

            // Set a where clause for each field
            $find->where($loginField, null, $userField);
        }

        /** @var User|null $result */
        $result = $find->getOneOrNull();

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function retrieveByResetToken(User $user, string $resetToken): User|null
    {
        $resetTokenField = $user::getResetTokenField();

        $userField = $user->__get($resetTokenField);

        if ($userField !== null
            && ! is_bool($userField)
            && ! is_string($userField)
            && ! is_int($userField)
            && ! is_float($userField)
            && ! $userField instanceof QueryBuilder
        ) {
            throw new InvalidArgumentException('Login fields should be QueryBuilder|array|string|float|int|bool|null');
        }

        /** @var User|null $result */
        $result = $this->getUserRepository($user)
                       ->find()
                       ->where($resetTokenField, null, $userField)
                       ->getOneOrNull();

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function retrieveById(User $user): User
    {
        /** @var User $result */
        $result = $this->getUserRepository($user)
                       ->findOne($user->getIdValue())
                       ->getOneOrFail();

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function save(User $user): void
    {
        // Get the ORM repository
        $repository = $this->getUserRepository($user);

        $repository->save($user, false);
        $repository->persist();
    }

    /**
     * @inheritDoc
     */
    public function create(User $user): bool
    {
        $repository    = $this->getUserRepository($user);
        $passwordField = $user::getPasswordField();

        try {
            if ($this->retrieve($user)) {
                return false;
            }

            $user->__set($passwordField, $this->hashPassword($user->getPasswordValue()));

            $this->orm->ensureTransaction();
            $repository->create($user);
            $repository->persist();

            return true;
        } catch (Exception $exception) {
            throw new InvalidRegistrationException($exception->getMessage());
        }
    }

    /**
     * @inheritDoc
     */
    public function getOrm(): Orm
    {
        return $this->orm;
    }

    /**
     * Get an ORM repository for the user.
     *
     * @param User $user The user
     *
     * @return Repository
     */
    protected function getUserRepository(User $user): Repository
    {
        return $this->orm->getRepositoryFromClass($user);
    }
}
