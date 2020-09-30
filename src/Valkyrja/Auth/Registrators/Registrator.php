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

namespace Valkyrja\Auth\Registrators;

use Exception;
use Valkyrja\Auth\Exceptions\InvalidRegistrationException;
use Valkyrja\Auth\Registrator as Contract;
use Valkyrja\Auth\User;
use Valkyrja\ORM\ORM;

use function password_hash;

use const PASSWORD_DEFAULT;

/**
 * Class Registrator.
 *
 * @author Melech Mizrachi
 */
class Registrator implements Contract
{
    /**
     * The ORM.
     *
     * @var ORM
     */
    protected ORM $orm;

    /**
     * Registrator constructor.
     *
     * @param ORM $orm
     */
    public function __construct(ORM $orm)
    {
        $this->orm = $orm;
    }

    /**
     * Register a new user.
     *
     * @param User $user
     *
     * @throws InvalidRegistrationException
     *
     * @return bool
     */
    public function register(User $user): bool
    {
        $repository    = $this->orm->getRepositoryFromClass($user);
        $passwordField = $user::getPasswordField();

        try {
            $user->__set($passwordField, $this->hashPassword($user->__get($passwordField)));

            $this->orm->ensureTransaction();
            $repository->create($user, true);
            $repository->persist();

            return true;
        } catch (Exception $exception) {
            throw new InvalidRegistrationException($exception->getMessage());
        }
    }

    /**
     * Determine if a user is registered.
     *
     * @param User $user
     *
     * @return bool
     */
    public function isRegistered(User $user): bool
    {
        $repository  = $this->orm->getRepositoryFromClass($user);
        $loginFields = $user::getLoginFields();
        $find        = $repository->find();

        // Iterate through the login fields
        foreach ($loginFields as $loginField) {
            // Find a user with any of the login fields
            $find->orWhere($loginField, null, $user->__get($loginField));
        }

        // If a user is found a user is registered with one of the login fields
        if ($find->getOneOrNull()) {
            return true;
        }

        return false;
    }

    /**
     * Hash a plain password.
     *
     * @param string $password
     *
     * @return string
     */
    protected function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
}
