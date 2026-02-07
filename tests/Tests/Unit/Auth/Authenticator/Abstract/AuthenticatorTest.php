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

namespace Valkyrja\Tests\Unit\Auth\Authenticator\Abstract;

use PHPUnit\Framework\MockObject\MockObject;
use Valkyrja\Auth\Data\Attempt\AuthenticationAttempt;
use Valkyrja\Auth\Data\AuthenticatedUsers;
use Valkyrja\Auth\Data\Retrieval\RetrievalByUsername;
use Valkyrja\Auth\Entity\User;
use Valkyrja\Auth\Hasher\Contract\PasswordHasherContract;
use Valkyrja\Auth\Store\Contract\StoreContract;
use Valkyrja\Auth\Throwable\Exception\InvalidAuthenticationException;
use Valkyrja\Tests\Classes\Auth\Authenticator\Abstract\AuthenticatorClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;

use const PASSWORD_DEFAULT;

/**
 * Test the abstract Authenticator class.
 */
final class AuthenticatorTest extends TestCase
{
    protected const string USER_ID        = 'user-123';
    protected const string USER_ID_2      = 'user-456';
    protected const string USERNAME       = 'testuser';
    protected const string PASSWORD       = 'SecureP@ssw0rd!';
    protected const string WRONG_PASSWORD = 'WrongPassword!';

    protected StoreContract&MockObject $store;
    protected PasswordHasherContract&MockObject $hasher;
    protected AuthenticatorClass $authenticator;
    protected User $user;

    protected function setUp(): void
    {
        $this->store  = $this->createMock(StoreContract::class);
        $this->hasher = $this->createMock(PasswordHasherContract::class);

        $this->authenticator = new AuthenticatorClass(
            store: $this->store,
            hasher: $this->hasher,
            entity: User::class,
        );

        $this->user           = new User();
        $this->user->id       = self::USER_ID;
        $this->user->username = self::USERNAME;
        $this->user->password = password_hash(self::PASSWORD, PASSWORD_DEFAULT);
    }

    public function testIsAuthenticatedReturnsFalseWhenNoUserAuthenticated(): void
    {
        $this->store->expects($this->never())->method(self::anything());
        $this->hasher->expects($this->never())->method(self::anything());

        self::assertFalse($this->authenticator->isAuthenticated());
    }

    public function testIsAuthenticatedReturnsTrueWhenUserAuthenticated(): void
    {
        $this->store->expects($this->once())
            ->method('retrieve')
            ->willReturn($this->user);
        $this->hasher->expects($this->once())
            ->method('confirmPassword')
            ->willReturn(true);

        $attempt = new AuthenticationAttempt(
            new RetrievalByUsername(self::USERNAME),
            self::PASSWORD
        );

        $this->authenticator->authenticate($attempt);

        self::assertTrue($this->authenticator->isAuthenticated());
    }

    public function testGetAuthenticatedReturnsNullWhenNoUserAuthenticated(): void
    {
        $this->store->expects($this->never())->method(self::anything());
        $this->hasher->expects($this->never())->method(self::anything());

        self::assertNull($this->authenticator->getAuthenticated());
    }

    public function testGetAuthenticatedReturnsUserAfterAuthentication(): void
    {
        $this->store->expects($this->once())
            ->method('retrieve')
            ->willReturn($this->user);
        $this->hasher->expects($this->once())
            ->method('confirmPassword')
            ->willReturn(true);

        $attempt = new AuthenticationAttempt(
            new RetrievalByUsername(self::USERNAME),
            self::PASSWORD
        );

        $this->authenticator->authenticate($attempt);

        self::assertSame($this->user, $this->authenticator->getAuthenticated());
    }

    public function testGetImpersonatedReturnsNullWhenNotImpersonating(): void
    {
        $this->store->expects($this->never())->method(self::anything());
        $this->hasher->expects($this->never())->method(self::anything());

        self::assertNull($this->authenticator->getImpersonated());
    }

    public function testGetImpersonatedReturnsUserWhenImpersonating(): void
    {
        $impersonatedUser           = new User();
        $impersonatedUser->id       = self::USER_ID_2;
        $impersonatedUser->username = 'impersonated';
        $impersonatedUser->password = 'password';

        // Set up an authenticated users container with impersonation
        $authenticatedUsers = new AuthenticatedUsers(self::USER_ID, self::USER_ID_2);
        $this->authenticator->setAuthenticatedUsers($authenticatedUsers);

        $this->store->expects($this->once())
            ->method('retrieve')
            ->willReturn($impersonatedUser);
        $this->hasher->expects($this->never())->method(self::anything());

        $result = $this->authenticator->getImpersonated();

        self::assertSame($impersonatedUser, $result);
    }

    public function testGetAuthenticatedUsers(): void
    {
        $this->store->expects($this->never())->method(self::anything());
        $this->hasher->expects($this->never())->method(self::anything());

        $authenticatedUsers = $this->authenticator->getAuthenticatedUsers();

        self::assertInstanceOf(AuthenticatedUsers::class, $authenticatedUsers);
    }

    public function testSetAuthenticatedUsers(): void
    {
        $this->store->expects($this->never())->method(self::anything());
        $this->hasher->expects($this->never())->method(self::anything());

        $newAuthenticatedUsers = new AuthenticatedUsers(self::USER_ID);

        $result = $this->authenticator->setAuthenticatedUsers($newAuthenticatedUsers);

        self::assertSame($this->authenticator, $result);
        self::assertSame($newAuthenticatedUsers, $this->authenticator->getAuthenticatedUsers());
    }

    public function testAuthenticateSuccess(): void
    {
        $this->store->expects($this->once())
            ->method('retrieve')
            ->willReturn($this->user);

        $this->hasher->expects($this->once())
            ->method('confirmPassword')
            ->with(self::PASSWORD, $this->user->getPasswordValue())
            ->willReturn(true);

        $attempt = new AuthenticationAttempt(
            new RetrievalByUsername(self::USERNAME),
            self::PASSWORD
        );

        $result = $this->authenticator->authenticate($attempt);

        self::assertSame($this->user, $result);
        self::assertTrue($this->authenticator->isAuthenticated());
    }

    public function testAuthenticateThrowsWhenUserNotFound(): void
    {
        $this->store->expects($this->once())
            ->method('retrieve')
            ->willReturn(null);
        $this->hasher->expects($this->never())->method(self::anything());

        $attempt = new AuthenticationAttempt(
            new RetrievalByUsername('nonexistent'),
            self::PASSWORD
        );

        $this->expectException(InvalidAuthenticationException::class);
        $this->expectExceptionMessage('User not found');

        $this->authenticator->authenticate($attempt);
    }

    public function testAuthenticateThrowsWhenPasswordIncorrect(): void
    {
        $this->store->expects($this->once())
            ->method('retrieve')
            ->willReturn($this->user);

        $this->hasher->expects($this->once())
            ->method('confirmPassword')
            ->willReturn(false);

        $attempt = new AuthenticationAttempt(
            new RetrievalByUsername(self::USERNAME),
            self::WRONG_PASSWORD
        );

        $this->expectException(InvalidAuthenticationException::class);
        $this->expectExceptionMessage('Incorrect password');

        $this->authenticator->authenticate($attempt);
    }

    public function testUnauthenticate(): void
    {
        // First authenticate a user
        $this->store->expects($this->once())
            ->method('retrieve')
            ->willReturn($this->user);
        $this->hasher->expects($this->once())
            ->method('confirmPassword')
            ->willReturn(true);

        $attempt = new AuthenticationAttempt(
            new RetrievalByUsername(self::USERNAME),
            self::PASSWORD
        );

        $this->authenticator->authenticate($attempt);
        self::assertTrue($this->authenticator->isAuthenticated());

        // Now unauthenticate
        $result = $this->authenticator->unauthenticate(self::USER_ID);

        self::assertSame($this->authenticator, $result);
        self::assertFalse($this->authenticator->isAuthenticated());
        self::assertNull($this->authenticator->getAuthenticated());
    }

    public function testUnauthenticateImpersonatedUser(): void
    {
        $impersonatedUser           = new User();
        $impersonatedUser->id       = self::USER_ID_2;
        $impersonatedUser->username = 'impersonated';
        $impersonatedUser->password = 'password';

        // Set up with impersonation
        $authenticatedUsers = new AuthenticatedUsers(self::USER_ID, self::USER_ID_2, self::USER_ID, self::USER_ID_2);
        $this->authenticator->setAuthenticatedUsers($authenticatedUsers);

        // Set up store to return the impersonated user first
        $this->store->expects($this->once())
            ->method('retrieve')
            ->willReturn($impersonatedUser);
        $this->hasher->expects($this->never())->method(self::anything());

        // Verify impersonation is active
        self::assertTrue($this->authenticator->getAuthenticatedUsers()->isImpersonating());

        // Get impersonated to populate the internal property
        $this->authenticator->getImpersonated();

        // Unauthenticate the impersonated user
        $this->authenticator->unauthenticate(self::USER_ID_2);

        self::assertFalse($this->authenticator->getAuthenticatedUsers()->isImpersonating());
    }

    public function testUnauthenticateNonCurrentUser(): void
    {
        $this->store->expects($this->never())->method(self::anything());
        $this->hasher->expects($this->never())->method(self::anything());

        // Set up multiple authenticated users
        $authenticatedUsers = new AuthenticatedUsers(self::USER_ID, null, self::USER_ID, self::USER_ID_2);
        $this->authenticator->setAuthenticatedUsers($authenticatedUsers);

        // Unauthenticate the non-current user
        $this->authenticator->unauthenticate(self::USER_ID_2);

        // Current user should still be authenticated
        self::assertTrue($this->authenticator->isAuthenticated());
        self::assertSame(self::USER_ID, $this->authenticator->getAuthenticatedUsers()->getCurrent());
    }

    public function testGetAuthenticatedCachesUser(): void
    {
        // Set up authenticated users with a current user
        $authenticatedUsers = new AuthenticatedUsers(self::USER_ID, null, self::USER_ID);
        $this->authenticator->setAuthenticatedUsers($authenticatedUsers);

        // Store should only be called once due to caching
        $this->store->expects($this->once())
            ->method('retrieve')
            ->willReturn($this->user);
        $this->hasher->expects($this->never())->method(self::anything());

        // Call getAuthenticated multiple times
        $result1 = $this->authenticator->getAuthenticated();
        $result2 = $this->authenticator->getAuthenticated();

        self::assertSame($result1, $result2);
        self::assertSame($this->user, $result1);
    }

    public function testGetImpersonatedCachesUser(): void
    {
        $impersonatedUser           = new User();
        $impersonatedUser->id       = self::USER_ID_2;
        $impersonatedUser->username = 'impersonated';
        $impersonatedUser->password = 'password';

        // Set up authenticated users with impersonation
        $authenticatedUsers = new AuthenticatedUsers(self::USER_ID, self::USER_ID_2);
        $this->authenticator->setAuthenticatedUsers($authenticatedUsers);

        // Store should only be called once due to caching
        $this->store->expects($this->once())
            ->method('retrieve')
            ->willReturn($impersonatedUser);
        $this->hasher->expects($this->never())->method(self::anything());

        // Call getImpersonated multiple times
        $result1 = $this->authenticator->getImpersonated();
        $result2 = $this->authenticator->getImpersonated();

        self::assertSame($result1, $result2);
        self::assertSame($impersonatedUser, $result1);
    }
}
