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

namespace Valkyrja\Tests\Unit\Auth\Authenticator;

use PHPUnit\Framework\MockObject\MockObject;
use stdClass;
use Valkyrja\Auth\Constant\SessionItemId;
use Valkyrja\Auth\Data\AuthenticatedUsers;
use Valkyrja\Auth\Data\Contract\AuthenticatedUsersContract;
use Valkyrja\Auth\Entity\User;
use Valkyrja\Auth\Hasher\Contract\PasswordHasherContract;
use Valkyrja\Auth\Store\Contract\StoreContract;
use Valkyrja\Session\Manager\Contract\SessionContract;
use Valkyrja\Tests\Classes\Auth\Authenticator\SessionAuthenticatorTestWrapper;
use Valkyrja\Tests\Unit\Abstract\TestCase;

use function serialize;

/**
 * Test the SessionAuthenticator class.
 */
class SessionAuthenticatorTest extends TestCase
{
    protected const string USER_ID = 'user-123';

    protected SessionContract&MockObject $session;
    protected StoreContract $store;
    protected PasswordHasherContract $hasher;

    protected function setUp(): void
    {
        $this->session = $this->createMock(SessionContract::class);
        // Use createStub for mocks that don't need expectations
        $this->store   = $this->createStub(StoreContract::class);
        $this->hasher  = $this->createStub(PasswordHasherContract::class);
    }

    /**
     * Test getAuthenticatedUsersFromSession returns valid AuthenticatedUsersContract (lines 69-78).
     */
    public function testGetAuthenticatedUsersFromSessionReturnsValidContract(): void
    {
        $authenticatedUsers = new AuthenticatedUsers(self::USER_ID, null, self::USER_ID);
        $serialized         = serialize($authenticatedUsers);

        $this->session->expects($this->atLeastOnce())
            ->method('get')
            ->with(SessionItemId::AUTHENTICATED_USERS)
            ->willReturn($serialized);

        $authenticator = new SessionAuthenticatorTestWrapper(
            session: $this->session,
            store: $this->store,
            hasher: $this->hasher,
            entity: User::class,
        );

        $result = $authenticator->testGetAuthenticatedUsersFromSession();

        self::assertInstanceOf(AuthenticatedUsersContract::class, $result);
        self::assertSame(self::USER_ID, $result->getCurrent());
    }

    /**
     * Test getAuthenticatedUsersFromSession returns null when unserialized data is not AuthenticatedUsersContract (lines 74-76).
     */
    public function testGetAuthenticatedUsersFromSessionReturnsNullForInvalidObject(): void
    {
        // Serialize an object that is not AuthenticatedUsersContract
        $invalidObject = new stdClass();
        $serialized    = serialize($invalidObject);

        $this->session->expects($this->atLeastOnce())
            ->method('get')
            ->with(SessionItemId::AUTHENTICATED_USERS)
            ->willReturn($serialized);

        $authenticator = new SessionAuthenticatorTestWrapper(
            session: $this->session,
            store: $this->store,
            hasher: $this->hasher,
            entity: User::class,
        );

        $result = $authenticator->testGetAuthenticatedUsersFromSession();

        self::assertNull($result);
    }

    /**
     * Test getAuthenticatedUsersFromSession returns null when session returns non-string (lines 64-66).
     */
    public function testGetAuthenticatedUsersFromSessionReturnsNullForNonString(): void
    {
        $this->session->expects($this->atLeastOnce())
            ->method('get')
            ->with(SessionItemId::AUTHENTICATED_USERS)
            ->willReturn(null);

        $authenticator = new SessionAuthenticatorTestWrapper(
            session: $this->session,
            store: $this->store,
            hasher: $this->hasher,
            entity: User::class,
        );

        $result = $authenticator->testGetAuthenticatedUsersFromSession();

        self::assertNull($result);
    }

    /**
     * Test getAuthenticatedUsersFromSession returns null when unserialize produces invalid data (lines 74-76).
     */
    public function testGetAuthenticatedUsersFromSessionReturnsNullForInvalidSerializedData(): void
    {
        // Serialize an array instead of an object
        $serialized = serialize(['not' => 'an object']);

        $this->session->expects($this->atLeastOnce())
            ->method('get')
            ->with(SessionItemId::AUTHENTICATED_USERS)
            ->willReturn($serialized);

        $authenticator = new SessionAuthenticatorTestWrapper(
            session: $this->session,
            store: $this->store,
            hasher: $this->hasher,
            entity: User::class,
        );

        $result = $authenticator->testGetAuthenticatedUsersFromSession();

        self::assertNull($result);
    }

    /**
     * Test constructor uses AuthenticatedUsers from session when available.
     */
    public function testConstructorUsesAuthenticatedUsersFromSession(): void
    {
        $authenticatedUsers = new AuthenticatedUsers(self::USER_ID, null, self::USER_ID);
        $serialized         = serialize($authenticatedUsers);

        $this->session->expects($this->atLeastOnce())
            ->method('get')
            ->with(SessionItemId::AUTHENTICATED_USERS)
            ->willReturn($serialized);

        $authenticator = new SessionAuthenticatorTestWrapper(
            session: $this->session,
            store: $this->store,
            hasher: $this->hasher,
            entity: User::class,
        );

        self::assertTrue($authenticator->isAuthenticated());
        self::assertSame(self::USER_ID, $authenticator->getAuthenticatedUsers()->getCurrent());
    }

    /**
     * Test constructor uses provided AuthenticatedUsers when given.
     */
    public function testConstructorUsesProvidedAuthenticatedUsers(): void
    {
        $providedUsers = new AuthenticatedUsers('provided-user-id', null, 'provided-user-id');

        // Session returns different user but should be ignored
        $this->session->expects($this->never())
            ->method('get');

        $authenticator = new SessionAuthenticatorTestWrapper(
            session: $this->session,
            store: $this->store,
            hasher: $this->hasher,
            entity: User::class,
            authenticatedUsers: $providedUsers,
        );

        self::assertSame('provided-user-id', $authenticator->getAuthenticatedUsers()->getCurrent());
    }

    /**
     * Test constructor creates new AuthenticatedUsers when session is empty.
     */
    public function testConstructorCreatesNewAuthenticatedUsersWhenSessionEmpty(): void
    {
        $this->session->expects($this->atLeastOnce())
            ->method('get')
            ->with(SessionItemId::AUTHENTICATED_USERS)
            ->willReturn(null);

        $authenticator = new SessionAuthenticatorTestWrapper(
            session: $this->session,
            store: $this->store,
            hasher: $this->hasher,
            entity: User::class,
        );

        self::assertFalse($authenticator->isAuthenticated());
        self::assertInstanceOf(AuthenticatedUsersContract::class, $authenticator->getAuthenticatedUsers());
    }

    /**
     * Test custom session item ID is used.
     */
    public function testCustomSessionItemId(): void
    {
        $customSessionId    = 'custom.auth.users';
        $authenticatedUsers = new AuthenticatedUsers(self::USER_ID, null, self::USER_ID);
        $serialized         = serialize($authenticatedUsers);

        $this->session->expects($this->atLeastOnce())
            ->method('get')
            ->with($customSessionId)
            ->willReturn($serialized);

        $authenticator = new SessionAuthenticatorTestWrapper(
            session: $this->session,
            store: $this->store,
            hasher: $this->hasher,
            entity: User::class,
            sessionItemId: $customSessionId,
        );

        self::assertTrue($authenticator->isAuthenticated());
    }
}
