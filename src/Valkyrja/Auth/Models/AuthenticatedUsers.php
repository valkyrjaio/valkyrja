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
use Valkyrja\Auth\User;
use Valkyrja\Support\Model\Classes\Model;

/**
 * Class Collection.
 *
 * @author   Melech Mizrachi
 * @template T
 */
class AuthenticatedUsers extends Model implements Contract
{
    /**
     * @inheritDoc
     */
    protected static array $propertyCastings = [
        'users' => [\Valkyrja\Auth\Entities\User::class],
    ];

    /**
     * The current user's id.
     *
     * @var string|null
     */
    protected ?string $currentId = null;

    /**
     * The users.
     *
     * @var User[]|array<int|string, T>
     */
    protected array $users = [];

    /**
     * Determine whether there is a current user in the collection.
     *
     * @return bool
     */
    public function hasCurrent(): bool
    {
        return isset($this->currentId);
    }

    /**
     * Get the current user.
     *
     * @return User|T|null
     */
    public function getCurrent(): ?User
    {
        return $this->users[$this->currentId] ?? null;
    }

    /**
     * Set the current user.
     *
     * @param User|T $user The user
     *
     * @return static
     */
    public function setCurrent(User $user): self
    {
        $this->currentId = $user->__get($user::getIdField());

        return $this->add($user);
    }

    /**
     * Add a user to the collection.
     *
     * @param User|T $user The user
     *
     * @return static
     */
    public function add(User $user): self
    {
        $this->users[$user->__get($user::getIdField())] = $user;

        return $this;
    }

    /**
     * Remove a user from the collection.
     *
     * @param User|T $user The user
     *
     * @return static
     */
    public function remove(User $user): self
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
     * Get all the users in the collection
     *
     * @return User[]|array<int|string, T>
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
