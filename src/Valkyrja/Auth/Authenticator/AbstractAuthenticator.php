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

namespace Valkyrja\Auth\Authenticator;

use Override;
use Valkyrja\Auth\Authenticator\Contract\Authenticator as Contract;
use Valkyrja\Auth\Data;
use Valkyrja\Auth\Data\Attempt\Contract\AuthenticationAttempt;
use Valkyrja\Auth\Data\Contract\AuthenticatedUsers;
use Valkyrja\Auth\Data\Retrieval\RetrievalById;
use Valkyrja\Auth\Entity\Contract\User;
use Valkyrja\Auth\Exception\InvalidAuthenticationException;
use Valkyrja\Auth\Hasher\Contract\PasswordHasher;
use Valkyrja\Auth\Store\Contract\Store;

/**
 * Abstract Class AbstractAuthenticator.
 *
 * @author Melech Mizrachi
 *
 * @template U of User
 *
 * @implements Contract<U>
 */
abstract class AbstractAuthenticator implements Contract
{
    /** @var User|null */
    protected User|null $current = null;
    /** @var User|null */
    protected User|null $impersonated = null;

    /**
     * @param Store<U>        $store  The store
     * @param class-string<U> $entity The user entity
     */
    public function __construct(
        protected Store $store,
        protected PasswordHasher $hasher,
        protected string $entity,
        protected AuthenticatedUsers $authenticatedUsers = new Data\AuthenticatedUsers(),
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
     * @return User|null
     */
    #[Override]
    public function getAuthenticated(): User|null
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
     * @return User|null
     */
    #[Override]
    public function getImpersonated(): User|null
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
    public function getAuthenticatedUsers(): AuthenticatedUsers
    {
        return $this->authenticatedUsers;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function setAuthenticatedUsers(AuthenticatedUsers $authenticatedUsers): static
    {
        $this->authenticatedUsers = $authenticatedUsers;

        return $this;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function authenticate(AuthenticationAttempt $attempt): User
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
