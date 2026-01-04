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

namespace Valkyrja\Tests\Unit\Auth\Store;

use Valkyrja\Auth\Data\Retrieval\RetrievalByUsername;
use Valkyrja\Auth\Entity\Contract\UserContract;
use Valkyrja\Auth\Entity\User as UserEntity;
use Valkyrja\Auth\Store\InMemoryStore;
use Valkyrja\Auth\Throwable\Exception\InvalidUserException;
use Valkyrja\Tests\Unit\TestCase;

use const PASSWORD_DEFAULT;

/**
 * Test the InMemoryStore.
 */
class InMemoryStoreTest extends TestCase
{
    protected const string USERNAME     = 'user1';
    protected const string BAD_USERNAME = 'bad_username';
    protected const string PASSWORD     = '!!wazzaup!!';
    protected const string RESET_TOKEN  = 'reset_token';

    protected InMemoryStore $store;
    protected UserEntity $user;

    protected function setUp(): void
    {
        $this->store = new InMemoryStore([]);

        $this->user           = new UserEntity();
        $this->user->id       = 'test';
        $this->user->username = self::USERNAME;
        $this->user->password = password_hash(self::PASSWORD, PASSWORD_DEFAULT);
    }

    /**
     * Test creating a new user.
     */
    public function testCreate(): void
    {
        $user = $this->retrieveUser();

        self::assertNull($user);

        $this->store->create($this->user);

        $user = $this->retrieveUser();

        self::assertNotNull($user);
        self::assertSame($this->user->username, $user->username);
        self::assertSame($this->user->password, $user->password);
        self::assertSame($this->user->reset_token, $user->reset_token);
    }

    /**
     * Test saving a user.
     *
     * @throws InvalidUserException
     */
    public function testSave(): void
    {
        $this->store->create($this->user);

        $user = $this->retrieveUser();

        self::assertNotNull($user);
        self::assertNull($user->reset_token);

        $updateUser              = clone $this->user;
        $updateUser->reset_token = self::RESET_TOKEN;

        $this->store->update($updateUser);

        $updatedUser = $this->retrieveUser();

        self::assertNotNull($updatedUser);
        self::assertSame(self::RESET_TOKEN, $updatedUser->reset_token);
    }

    /**
     * Test saving with a non-existent user.
     *
     * @throws InvalidUserException
     */
    public function testNonExistentUserSave(): void
    {
        $this->expectException(InvalidUserException::class);

        $nonExistentUser           = new UserEntity();
        $nonExistentUser->id       = 'test';
        $nonExistentUser->username = self::BAD_USERNAME;

        $this->store->update($nonExistentUser);
    }

    /**
     * Test retrieving a user.
     */
    public function testRetrieveUser(): void
    {
        $this->store->create($this->user);

        $updateUser              = clone $this->user;
        $updateUser->reset_token = self::RESET_TOKEN;

        $this->store->update($updateUser);

        $user = $this->store->retrieve($this->getAuthenticationRetrieval(), UserEntity::class);

        self::assertNotNull($user);
        self::assertSame(self::USERNAME, $user->username);
        self::assertSame(self::RESET_TOKEN, $user->reset_token);
    }

    public function testFailedUserRetrieval(): void
    {
        $user = $this->store->retrieve(new RetrievalByUsername(self::BAD_USERNAME), UserEntity::class);

        self::assertNull($user);
    }

    /**
     * Retrieve an existing user.
     */
    protected function retrieveUser(): UserContract|null
    {
        return $this->store->retrieve($this->getAuthenticationRetrieval(), UserEntity::class);
    }

    /**
     * Get a successful AuthenticationRetrieval object.
     */
    protected function getAuthenticationRetrieval(): RetrievalByUsername
    {
        return new RetrievalByUsername(self::USERNAME);
    }
}
