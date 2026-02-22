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

use Valkyrja\Auth\Data\Retrieval\RetrievalByIdAndUsername;
use Valkyrja\Auth\Data\Retrieval\RetrievalByUsername;
use Valkyrja\Auth\Entity\Contract\UserContract;
use Valkyrja\Auth\Entity\User;
use Valkyrja\Auth\Store\InMemoryStore;
use Valkyrja\Auth\Throwable\Exception\InvalidRetrievableUserException;
use Valkyrja\Tests\Unit\Abstract\TestCase;

use const PASSWORD_DEFAULT;

/**
 * Test the InMemoryStore.
 */
final class InMemoryStoreTest extends TestCase
{
    protected const string USERNAME     = 'user1';
    protected const string BAD_USERNAME = 'bad_username';
    protected const string PASSWORD     = '!!wazzaup!!';
    protected const string RESET_TOKEN  = 'reset_token';

    protected InMemoryStore $store;
    protected User $user;

    protected function setUp(): void
    {
        $this->store = new InMemoryStore([]);

        $this->user           = new User();
        $this->user->id       = 'test';
        $this->user->username = self::USERNAME;
        $this->user->password = password_hash(self::PASSWORD, PASSWORD_DEFAULT);
    }

    /**
     * Test creating a new user.
     */
    public function testCreate(): void
    {
        self::assertFalse($this->hasRetrieveUser());

        $this->store->create($this->user);

        $user = $this->retrieveUser();

        self::assertTrue($this->hasRetrieveUser());
        self::assertNotNull($user);
        self::assertSame($this->user->username, $user->username);
        self::assertSame($this->user->password, $user->password);
        self::assertSame($this->user->reset_token, $user->reset_token);
    }

    /**
     * Test saving a user.
     */
    public function testSave(): void
    {
        $this->store->create($this->user);

        $user = $this->retrieveUser();

        self::assertTrue($this->hasRetrieveUser());
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
     */
    public function testNonExistentUserSave(): void
    {
        $this->expectException(InvalidRetrievableUserException::class);
        $this->expectExceptionMessage('A user could not be retrieved with the given criteria');

        $nonExistentUser           = new User();
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

        $user = $this->store->retrieve($this->getAuthenticationRetrieval(), User::class);

        self::assertTrue($this->hasRetrieveUser());
        self::assertNotNull($user);
        self::assertSame(self::USERNAME, $user->username);
        self::assertSame(self::RESET_TOKEN, $user->reset_token);
    }

    public function testFailedUserRetrieval(): void
    {
        $this->expectException(InvalidRetrievableUserException::class);
        $this->expectExceptionMessage('A user could not be retrieved with the given criteria');

        self::assertFalse($this->hasRetrieveUser());
        $this->store->retrieve(new RetrievalByUsername(self::BAD_USERNAME), User::class);
    }

    /**
     * Test retrieving a user with multiple retrieval fields (covers line 92).
     * This tests the else branch in filterUsers() when there are multiple fields.
     */
    public function testRetrieveUserWithMultipleFields(): void
    {
        $this->store->create($this->user);

        // Use RetrievalByIdAndUsername which provides both id and username fields
        $retrieval = new RetrievalByIdAndUsername('test', self::USERNAME);

        $user = $this->store->retrieve($retrieval, User::class);

        self::assertTrue($this->hasRetrieveUser());
        self::assertNotNull($user);
        self::assertSame('test', $user->id);
        self::assertSame(self::USERNAME, $user->username);
    }

    /**
     * Test retrieving with multiple fields where one field doesn't match.
     * This tests the else branch in filterUsers() returning false.
     */
    public function testRetrieveUserWithMultipleFieldsNoMatch(): void
    {
        $this->store->create($this->user);

        // Use correct id but wrong username - should not match
        $retrieval = new RetrievalByIdAndUsername('test', 'wrong_username');

        self::assertFalse($this->store->hasRetrievable($retrieval, User::class));
    }

    /**
     * Retrieve an existing user.
     */
    protected function retrieveUser(): UserContract
    {
        return $this->store->retrieve($this->getAuthenticationRetrieval(), User::class);
    }

    /**
     * Determine if a user is retrievable.
     */
    protected function hasRetrieveUser(): bool
    {
        return $this->store->hasRetrievable($this->getAuthenticationRetrieval(), User::class);
    }

    /**
     * Get a successful AuthenticationRetrieval object.
     */
    protected function getAuthenticationRetrieval(): RetrievalByUsername
    {
        return new RetrievalByUsername(self::USERNAME);
    }
}
