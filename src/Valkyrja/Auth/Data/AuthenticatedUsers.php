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

namespace Valkyrja\Auth\Data;

use Override;
use Valkyrja\Auth\Data\Contract\AuthenticatedUsersContract as Contract;

use function in_array;

/**
 * Class AuthenticatedUsers.
 */
class AuthenticatedUsers implements Contract
{
    /**
     * The users.
     *
     * @var array<int|non-empty-string, non-empty-string|int>
     */
    protected array $users = [];

    /**
     * @param non-empty-string|int|null $currentId      The current user's id
     * @param non-empty-string|int|null $impersonatedId The impersonated user's id
     * @param non-empty-string|int      ...$users       The users
     */
    public function __construct(
        protected string|int|null $currentId = null,
        protected string|int|null $impersonatedId = null,
        string|int ...$users
    ) {
        foreach ($users as $user) {
            $this->add($user);
        }
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function hasCurrent(): bool
    {
        return $this->currentId !== null;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getCurrent(): string|int|null
    {
        return $this->currentId ?? null;
    }

    /**
     * @inheritDoc
     *
     * @param non-empty-string|int $id The user
     */
    #[Override]
    public function setCurrent(string|int $id): static
    {
        $this->currentId = $id;

        $this->add($id);

        return $this;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function isImpersonating(): bool
    {
        return $this->impersonatedId !== null;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getImpersonated(): string|int|null
    {
        return $this->impersonatedId ?? null;
    }

    /**
     * @inheritDoc
     *
     * @param non-empty-string|int $id The user
     */
    #[Override]
    public function setImpersonated(string|int $id): static
    {
        $this->impersonatedId = $id;

        $this->add($id);

        return $this;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function isUserAuthenticated(string|int $id): bool
    {
        if ($this->currentId === $id) {
            return true;
        }

        if ($this->impersonatedId === $id) {
            return true;
        }

        if (in_array($id, $this->users, true)) {
            return true;
        }

        return false;
    }

    /**
     * @inheritDoc
     *
     * @param non-empty-string|int $id The user
     */
    #[Override]
    public function add(string|int $id): static
    {
        $this->users[$id] = $id;

        return $this;
    }

    /**
     * @inheritDoc
     *
     * @param non-empty-string|int $id The id of the user to remove
     */
    #[Override]
    public function remove(string|int $id): static
    {
        unset($this->users[$id]);

        if ($this->currentId === $id) {
            $this->currentId = null;

            if (! empty($this->users)) {
                $this->setCurrent($this->users[array_key_first($this->users)]);
            }
        }

        if ($this->impersonatedId === $id) {
            $this->impersonatedId = null;
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function all(): array
    {
        return $this->users;
    }
}
