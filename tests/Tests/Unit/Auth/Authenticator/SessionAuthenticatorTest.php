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
use Valkyrja\Auth\Authenticator\SessionAuthenticator;
use Valkyrja\Auth\Constant\SessionItemId;
use Valkyrja\Auth\Data\AuthenticatedUsers;
use Valkyrja\Auth\Entity\User;
use Valkyrja\Auth\Hasher\Contract\PasswordHasherContract;
use Valkyrja\Auth\Store\Contract\StoreContract;
use Valkyrja\Auth\Throwable\Exception\InvalidAuthenticatedUsersSessionValueException;
use Valkyrja\Auth\Throwable\Exception\InvalidUnserializedAuthenticatedUsersException;
use Valkyrja\Session\Manager\Contract\SessionContract;
use Valkyrja\Tests\Unit\Abstract\TestCase;

use function serialize;

/**
 * Test the SessionAuthenticator class.
 */
final class SessionAuthenticatorTest extends TestCase
{
    protected const string USER_ID = 'user-123';

    protected SessionContract&MockObject $session;
    protected StoreContract $store;
    protected PasswordHasherContract $hasher;

    protected function setUp(): void
    {
        $this->session = $this->createMock(SessionContract::class);
        // Use createStub for mocks that don't need expectations
        $this->store   = self::createStub(StoreContract::class);
        $this->hasher  = self::createStub(PasswordHasherContract::class);
    }

    /**
     * Test getAuthenticatedUsersFromSession returns valid AuthenticatedUsersContract (lines 69-78).
     */
    public function testGetAuthenticatedUsersFromSessionReturnsValidContract(): void
    {
        $authenticatedUsers = new AuthenticatedUsers(self::USER_ID, null, self::USER_ID);
        $serialized         = serialize($authenticatedUsers);

        $this->session->expects($this->once())
            ->method('has')
            ->with(SessionItemId::AUTHENTICATED_USERS)
            ->willReturn(true);

        $this->session->expects($this->once())
            ->method('get')
            ->with(SessionItemId::AUTHENTICATED_USERS)
            ->willReturn($serialized);

        $authenticator = new SessionAuthenticator(
            session: $this->session,
            store: $this->store,
            hasher: $this->hasher,
            entity: User::class,
        );

        $result = $authenticator->getAuthenticatedUsers();

        self::assertSame(self::USER_ID, $result->getCurrent());
    }

    /**
     * Test getAuthenticatedUsersFromSession throws when unserialized data is not AuthenticatedUsersContract.
     */
    public function testGetAuthenticatedUsersFromSessionThrowsForInvalidObject(): void
    {
        $this->expectException(InvalidUnserializedAuthenticatedUsersException::class);
        $this->expectExceptionMessage('Session contains invalid authenticated users');

        // Serialize an object that is not AuthenticatedUsersContract
        $invalidObject = new stdClass();
        $serialized    = serialize($invalidObject);

        $this->session->expects($this->once())
            ->method('has')
            ->with(SessionItemId::AUTHENTICATED_USERS)
            ->willReturn(true);

        $this->session->expects($this->once())
            ->method('get')
            ->with(SessionItemId::AUTHENTICATED_USERS)
            ->willReturn($serialized);

        new SessionAuthenticator(
            session: $this->session,
            store: $this->store,
            hasher: $this->hasher,
            entity: User::class,
        );
    }

    /**
     * Test getAuthenticatedUsersFromSession returns an empty data model when session does not exist.
     */
    public function testGetAuthenticatedUsersFromSessionReturnsEmptyDataModelForNonExistentValue(): void
    {
        $this->session->expects($this->once())
            ->method('has')
            ->with(SessionItemId::AUTHENTICATED_USERS)
            ->willReturn(false);

        $this->session->expects($this->never())
            ->method('get');

        $authenticator = new SessionAuthenticator(
            session: $this->session,
            store: $this->store,
            hasher: $this->hasher,
            entity: User::class,
        );

        $result = $authenticator->getAuthenticatedUsers();

        self::assertFalse($authenticator->isAuthenticated());
        self::assertFalse($result->hasCurrent());
        self::assertFalse($result->isImpersonating());
    }

    /**
     * Test getAuthenticatedUsersFromSession throws when session returns non-string (lines 64-66).
     */
    public function testGetAuthenticatedUsersFromSessionThrowsForNonString(): void
    {
        $this->expectException(InvalidAuthenticatedUsersSessionValueException::class);
        $this->expectExceptionMessage('Session contains invalid authenticated users');

        $this->session->expects($this->once())
            ->method('has')
            ->with(SessionItemId::AUTHENTICATED_USERS)
            ->willReturn(true);

        $this->session->expects($this->once())
            ->method('get')
            ->with(SessionItemId::AUTHENTICATED_USERS)
            ->willReturn(null);

        $authenticator = new SessionAuthenticator(
            session: $this->session,
            store: $this->store,
            hasher: $this->hasher,
            entity: User::class,
        );

        $result = $authenticator->getAuthenticatedUsers();
    }

    /**
     * Test getAuthenticatedUsersFromSession throws when unserialize produces invalid data.
     */
    public function testGetAuthenticatedUsersFromSessionThrowsForInvalidSerializedData(): void
    {
        $this->expectException(InvalidUnserializedAuthenticatedUsersException::class);
        $this->expectExceptionMessage('Session contains invalid authenticated users');

        // Serialize an array instead of an object
        $serialized = serialize(['not' => 'an object']);

        $this->session->expects($this->once())
            ->method('has')
            ->with(SessionItemId::AUTHENTICATED_USERS)
            ->willReturn(true);

        $this->session->expects($this->once())
            ->method('get')
            ->with(SessionItemId::AUTHENTICATED_USERS)
            ->willReturn($serialized);

        $this->session->expects($this->once())
            ->method('remove')
            ->with(SessionItemId::AUTHENTICATED_USERS);

        new SessionAuthenticator(
            session: $this->session,
            store: $this->store,
            hasher: $this->hasher,
            entity: User::class,
        );
    }

    /**
     * Test constructor uses AuthenticatedUsers from session when available.
     */
    public function testConstructorUsesAuthenticatedUsersFromSession(): void
    {
        $authenticatedUsers = new AuthenticatedUsers(self::USER_ID, null, self::USER_ID);
        $serialized         = serialize($authenticatedUsers);

        $this->session->expects($this->once())
            ->method('has')
            ->with(SessionItemId::AUTHENTICATED_USERS)
            ->willReturn(true);

        $this->session->expects($this->once())
            ->method('get')
            ->with(SessionItemId::AUTHENTICATED_USERS)
            ->willReturn($serialized);

        $authenticator = new SessionAuthenticator(
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
            ->method('has');

        $this->session->expects($this->never())
            ->method('get');

        $authenticator = new SessionAuthenticator(
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
        $this->session->expects($this->once())
            ->method('has')
            ->with(SessionItemId::AUTHENTICATED_USERS)
            ->willReturn(false);

        $this->session->expects($this->never())
            ->method('get');

        $authenticator = new SessionAuthenticator(
            session: $this->session,
            store: $this->store,
            hasher: $this->hasher,
            entity: User::class,
        );

        self::assertFalse($authenticator->isAuthenticated());
    }

    /**
     * Test custom session item ID is used.
     */
    public function testCustomSessionItemId(): void
    {
        $customSessionId    = 'custom.auth.users';
        $authenticatedUsers = new AuthenticatedUsers(self::USER_ID, null, self::USER_ID);
        $serialized         = serialize($authenticatedUsers);

        $this->session->expects($this->once())
            ->method('has')
            ->with($customSessionId)
            ->willReturn(true);

        $this->session->expects($this->once())
            ->method('get')
            ->with($customSessionId)
            ->willReturn($serialized);

        $authenticator = new SessionAuthenticator(
            session: $this->session,
            store: $this->store,
            hasher: $this->hasher,
            entity: User::class,
            sessionItemId: $customSessionId,
        );

        self::assertTrue($authenticator->isAuthenticated());
    }
}
