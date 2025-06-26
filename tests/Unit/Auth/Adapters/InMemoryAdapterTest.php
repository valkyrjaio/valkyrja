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

namespace Valkyrja\Tests\Unit\Auth\Adapters;

use Valkyrja\Auth\Adapter\InMemoryAdapter;
use Valkyrja\Auth\Data\AuthenticationAttempt;
use Valkyrja\Auth\Data\AuthenticationRetrieval;
use Valkyrja\Auth\Entity\Contract\User;
use Valkyrja\Auth\Entity\User as UserEntity;
use Valkyrja\Auth\Exception\InvalidUserException;
use Valkyrja\Tests\Unit\TestCase;

use const PASSWORD_DEFAULT;

/**
 * Test the InMemoryAdapter.
 */
class InMemoryAdapterTest extends TestCase
{
    protected const string USERNAME     = 'user1';
    protected const string BAD_USERNAME = 'bad_username';
    protected const string PASSWORD     = '!!wazzaup!!';
    protected const string RESET_TOKEN  = 'reset_token';

    protected InMemoryAdapter $adapter;
    protected UserEntity $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adapter = new InMemoryAdapter(UserEntity::class, []);

        $this->user           = new UserEntity();
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

        $this->adapter->create($this->user);

        $user = $this->retrieveUser();

        self::assertNotNull($user);
        self::assertSame($this->user->username, $user->username);
        self::assertSame($this->user->password, $user->password);
        self::assertSame($this->user->reset_token, $user->reset_token);
    }

    /**
     * Test attempting to create an existing user.
     */
    public function testExistingUserCreate(): void
    {
        $this->expectException(InvalidUserException::class);
        $this->expectExceptionMessage(InMemoryAdapter::USER_DOES_EXIST_EXCEPTION_MESSAGE);

        $this->adapter->create($this->user);
    }

    /**
     * Test saving a user.
     *
     * @throws InvalidUserException
     */
    public function testSave(): void
    {
        $user = $this->retrieveUser();

        self::assertNotNull($user);
        self::assertNull($user->reset_token);

        $updateUser              = clone $this->user;
        $updateUser->reset_token = self::RESET_TOKEN;

        $this->adapter->save($updateUser);

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
        $this->expectExceptionMessage(InMemoryAdapter::USER_DOES_NOT_EXIST_EXCEPTION_MESSAGE);

        $nonExistentUser           = new UserEntity();
        $nonExistentUser->username = self::BAD_USERNAME;

        $this->adapter->save($nonExistentUser);
    }

    /**
     * Test authentication with a successful password combination.
     */
    public function testAuthenticate(): void
    {
        $user = $this->adapter->authenticate(new AuthenticationAttempt(self::USERNAME, self::PASSWORD));

        self::assertNotNull($user);
        self::assertSame(self::USERNAME, $user->username);
        self::assertSame(self::RESET_TOKEN, $user->reset_token);
    }

    /**
     * Test authentication with a failed password.
     */
    public function testFailedAuthentication(): void
    {
        $user = $this->adapter->authenticate(new AuthenticationAttempt(self::USERNAME, 'bad_password'));

        self::assertNull($user);

        $user = $this->adapter->authenticate(new AuthenticationAttempt(self::BAD_USERNAME, self::PASSWORD));

        self::assertNull($user);
    }

    /**
     * Test retrieving a user.
     */
    public function testRetrieveUser(): void
    {
        $user = $this->adapter->retrieve($this->getAuthenticationRetrieval());

        self::assertNotNull($user);
        self::assertSame(self::USERNAME, $user->username);
        self::assertSame(self::RESET_TOKEN, $user->reset_token);
    }

    public function testFailedUserRetrieval(): void
    {
        $user = $this->adapter->retrieve(new AuthenticationRetrieval(self::BAD_USERNAME));

        self::assertNull($user);
    }

    /**
     * Retrieve an existing user.
     */
    protected function retrieveUser(): User|null
    {
        return $this->adapter->retrieve($this->getAuthenticationRetrieval());
    }

    /**
     * Get a successful AuthenticationRetrieval object.
     */
    protected function getAuthenticationRetrieval(): AuthenticationRetrieval
    {
        return new AuthenticationRetrieval(self::USERNAME);
    }
}
