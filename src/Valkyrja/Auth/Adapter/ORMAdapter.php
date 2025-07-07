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
use Valkyrja\Orm\Contract\Manager;
use Valkyrja\Orm\Data\Value;
use Valkyrja\Orm\Data\Where;
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
     * @param Manager $orm The orm
     */
    public function __construct(
        protected Manager $orm,
        Config $config
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
        $where       = [];

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
            $where[] = new Where(new Value(name: $loginField, value: $userField));
        }

        /** @var User|null $result */
        $result = $this->getUserRepository($user)->findBy(...$where);

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

        $where = new Where(new Value(name: $resetTokenField, value: $userField));

        /** @var User|null $result */
        $result = $this->getUserRepository($user)
                       ->findBy($where);

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function retrieveById(User $user): User
    {
        /** @var User $result */
        $result = $this->getUserRepository($user)
                       ->find($user->getIdValue());

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function save(User $user): void
    {
        // Get the ORM repository
        $repository = $this->getUserRepository($user);

        $this->orm->ensureTransaction();
        $repository->update($user);
        $this->orm->commit();
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
            $this->orm->commit();

            return true;
        } catch (Exception $exception) {
            throw new InvalidRegistrationException($exception->getMessage());
        }
    }

    /**
     * @inheritDoc
     */
    public function getOrm(): Manager
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
        return $this->orm->createRepository($user::class);
    }
}
