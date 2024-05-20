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

namespace Valkyrja\Auth\Models;

use Valkyrja\Auth\AuthenticatedUsers as Contract;
use Valkyrja\Auth\Entities\User as UserClass;
use Valkyrja\Auth\User;
use Valkyrja\Type\Model\CastableModel;
use Valkyrja\Type\Model\Data\ArrayCast;

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
     * @var string|null
     */
    protected string|null $currentId = null;

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
        $this->currentId = $user->__get($user::getIdField());

        return $this->add($user);
    }

    /**
     * @inheritDoc
     *
     * @param User $user The user
     */
    public function isAuthenticated(User $user): bool
    {
        $id = $user->__get($user::getIdField());

        if ($this->currentId === $id) {
            return true;
        }

        foreach ($this->users as $authedUser) {
            if ($authedUser->__get($authedUser::getIdField()) === $id) {
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
        $this->users[$user->__get($user::getIdField())] = $user;

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
        $id = $user->__get($user::getIdField());

        unset($this->users[$user->__get($user::getIdField())]);

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
        $users = [];

        foreach ($this->users as $key => $userFromCollection) {
            $users[$key] = $userFromCollection->asArray();
        }

        return [
            'currentId' => $this->currentId,
            'users'     => $users,
        ];
    }
}
