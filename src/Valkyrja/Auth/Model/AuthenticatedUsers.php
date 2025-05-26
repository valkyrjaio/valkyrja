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

namespace Valkyrja\Auth\Model;

use Valkyrja\Auth\Entity\Contract\User;
use Valkyrja\Auth\Entity\User as UserClass;
use Valkyrja\Auth\Model\Contract\AuthenticatedUsers as Contract;
use Valkyrja\Type\Data\ArrayCast;
use Valkyrja\Type\Model\CastableModel;

/**
 * Class Collection.
 *
 * @author Melech Mizrachi
 *
 * @implements Contract<User>
 */
class AuthenticatedUsers extends CastableModel implements Contract
{
    /**
     * The current user's id.
     *
     * @var string|int|null
     */
    protected string|int|null $currentId = null;

    /**
     * The impersonated user's id.
     *
     * @var string|int|null
     */
    protected string|int|null $impersonatedId = null;

    /**
     * The users.
     *
     * @var array<int|string, User>
     */
    protected array $users = [];

    public static function getCastings(): array
    {
        return [
            'users' => new ArrayCast(UserClass::class),
        ];
    }

    /**
     * @inheritDoc
     */
    public function hasCurrent(): bool
    {
        return isset($this->currentId);
    }

    /**
     * @inheritDoc
     */
    public function getCurrent(): User|null
    {
        return $this->users[$this->currentId] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function setCurrent(User $user): static
    {
        $this->currentId = $user->getIdValue();

        return $this->add($user);
    }

    /**
     * @inheritDoc
     */
    public function isImpersonating(): bool
    {
        return isset($this->impersonatedId);
    }

    /**
     * @inheritDoc
     */
    public function getImpersonated(): User|null
    {
        return $this->users[$this->impersonatedId] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function setImpersonated(User $user): static
    {
        $this->impersonatedId = $user->getIdValue();

        return $this->add($user);
    }

    /**
     * @inheritDoc
     *
     * @param User $user The user
     */
    public function isAuthenticated(User $user): bool
    {
        $id = $user->getIdValue();

        if ($this->currentId === $id) {
            return true;
        }

        if ($this->impersonatedId === $id) {
            return true;
        }

        foreach ($this->users as $authedUser) {
            if ($authedUser->getIdValue() === $id) {
                return true;
            }
        }

        return false;
    }

    /**
     * @inheritDoc
     *
     * @param User $user The user
     *
     * @return static
     */
    public function add(User $user): static
    {
        $this->users[$user->getIdValue()] = $user;

        return $this;
    }

    /**
     * @inheritDoc
     *
     * @param User $user The user
     *
     * @return static
     */
    public function remove(User $user): static
    {
        $id = $user->getIdValue();

        unset($this->users[$id]);

        if ($this->currentId === $id) {
            $this->currentId = null;

            if (! empty($this->users)) {
                $this->setCurrent($this->users[array_key_first($this->users)]);
            }
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function all(): array
    {
        return $this->users;
    }

    /**
     * @inheritDoc
     */
    public function asArray(string ...$properties): array
    {
        $users = array_map(static fn ($userFromCollection) => $userFromCollection->asArray(), $this->users);

        return [
            'currentId' => $this->currentId,
            'users'     => $users,
        ];
    }

    /**
     * @inheritDoc
     */
    public function asStorableArray(string ...$properties): array
    {
        $users = array_map(static fn ($userFromCollection) => $userFromCollection->asStorableArray(), $this->users);

        return [
            'currentId' => $this->currentId,
            'users'     => $users,
        ];
    }
}
