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

namespace Valkyrja\Auth\Authenticator\Abstract;

use Override;
use Valkyrja\Auth\Authenticator\Contract\AuthenticatorContract as Contract;
use Valkyrja\Auth\Data\Attempt\Contract\AuthenticationAttemptContract;
use Valkyrja\Auth\Data\AuthenticatedUsers as AuthenticatedUsersData;
use Valkyrja\Auth\Data\Contract\AuthenticatedUsersContract;
use Valkyrja\Auth\Data\Retrieval\RetrievalById;
use Valkyrja\Auth\Entity\Contract\UserContract;
use Valkyrja\Auth\Hasher\Contract\PasswordHasherContract;
use Valkyrja\Auth\Store\Contract\StoreContract;
use Valkyrja\Auth\Throwable\Exception\InvalidAuthenticationException;

/**
 * Abstract Class Authenticator.
 *
 * @template U of UserContract
 *
 * @implements Contract<U>
 */
abstract class Authenticator implements Contract
{
    /** @var UserContract|null */
    protected UserContract|null $current = null;
    /** @var UserContract|null */
    protected UserContract|null $impersonated = null;

    /**
     * @param StoreContract<U> $store  The store
     * @param class-string<U>  $entity The user entity
     */
    public function __construct(
        protected StoreContract $store,
        protected PasswordHasherContract $hasher,
        protected string $entity,
        protected AuthenticatedUsersContract $authenticatedUsers = new AuthenticatedUsersData(),
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function isAuthenticated(): bool
    {
        return $this->authenticatedUsers->hasCurrent()
            || $this->authenticatedUsers->isImpersonating();
    }

    /**
     * Get the current authenticated user if one exists.
     *
     * @return UserContract|null
     */
    #[Override]
    public function getAuthenticated(): UserContract|null
    {
        $id = $this->authenticatedUsers->getCurrent();

        if ($id === null) {
            return null;
        }

        return $this->current ??= $this->store->retrieve(
            retrieval: new RetrievalById($id),
            user: $this->entity
        );
    }

    /**
     * Get the current impersonated user if one exists.
     *
     * @return UserContract|null
     */
    #[Override]
    public function getImpersonated(): UserContract|null
    {
        $id = $this->authenticatedUsers->getImpersonated();

        if ($id === null) {
            return null;
        }

        return $this->impersonated ??= $this->store->retrieve(
            retrieval: new RetrievalById($id),
            user: $this->entity
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getAuthenticatedUsers(): AuthenticatedUsersContract
    {
        return $this->authenticatedUsers;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function setAuthenticatedUsers(AuthenticatedUsersContract $authenticatedUsers): static
    {
        $this->authenticatedUsers = $authenticatedUsers;

        return $this;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function authenticate(AuthenticationAttemptContract $attempt): UserContract
    {
        $user = $this->store->retrieve($attempt->getRetrieval(), $this->entity);

        if ($user === null) {
            throw new InvalidAuthenticationException('User not found');
        }

        if (! $this->hasher->confirmPassword($attempt->getPassword(), $user->getPasswordValue())) {
            throw new InvalidAuthenticationException('Incorrect password');
        }

        $this->authenticatedUsers->setCurrent($user->getIdValue());

        $this->current = $user;

        return $user;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function unauthenticate(int|string $id): static
    {
        $this->authenticatedUsers->remove($id);

        if ($this->current?->getIdValue() === $id) {
            $this->current = null;
        }

        if ($this->impersonated?->getIdValue() === $id) {
            $this->impersonated = null;
        }

        return $this;
    }
}
