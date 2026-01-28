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

namespace Valkyrja\Tests\Unit\Auth\Data;

use Valkyrja\Auth\Data\AuthenticatedUsers;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the AuthenticatedUsers class.
 */
class AuthenticatedUsersTest extends TestCase
{
    protected const string USER_ID_1 = 'user-1';
    protected const string USER_ID_2 = 'user-2';
    protected const int USER_ID_3    = 3;

    public function testConstructorWithNoArguments(): void
    {
        $users = new AuthenticatedUsers();

        self::assertFalse($users->hasCurrent());
        self::assertNull($users->getCurrent());
        self::assertFalse($users->isImpersonating());
        self::assertNull($users->getImpersonated());
        self::assertEmpty($users->all());
    }

    public function testConstructorWithCurrentId(): void
    {
        $users = new AuthenticatedUsers(self::USER_ID_1);

        self::assertTrue($users->hasCurrent());
        self::assertSame(self::USER_ID_1, $users->getCurrent());
        self::assertFalse($users->isImpersonating());
        self::assertEmpty($users->all());
    }

    public function testConstructorWithCurrentAndImpersonatedId(): void
    {
        $users = new AuthenticatedUsers(self::USER_ID_1, self::USER_ID_2);

        self::assertTrue($users->hasCurrent());
        self::assertSame(self::USER_ID_1, $users->getCurrent());
        self::assertTrue($users->isImpersonating());
        self::assertSame(self::USER_ID_2, $users->getImpersonated());
    }

    public function testConstructorWithUsers(): void
    {
        $users = new AuthenticatedUsers(null, null, self::USER_ID_1, self::USER_ID_2, self::USER_ID_3);

        self::assertFalse($users->hasCurrent());
        self::assertCount(3, $users->all());
        self::assertTrue($users->isUserAuthenticated(self::USER_ID_1));
        self::assertTrue($users->isUserAuthenticated(self::USER_ID_2));
        self::assertTrue($users->isUserAuthenticated(self::USER_ID_3));
    }

    public function testSetCurrent(): void
    {
        $users = new AuthenticatedUsers();

        self::assertFalse($users->hasCurrent());

        $result = $users->setCurrent(self::USER_ID_1);

        self::assertSame($users, $result);
        self::assertTrue($users->hasCurrent());
        self::assertSame(self::USER_ID_1, $users->getCurrent());
        self::assertTrue($users->isUserAuthenticated(self::USER_ID_1));
    }

    public function testSetImpersonated(): void
    {
        $users = new AuthenticatedUsers(self::USER_ID_1);

        self::assertFalse($users->isImpersonating());

        $result = $users->setImpersonated(self::USER_ID_2);

        self::assertSame($users, $result);
        self::assertTrue($users->isImpersonating());
        self::assertSame(self::USER_ID_2, $users->getImpersonated());
        self::assertTrue($users->isUserAuthenticated(self::USER_ID_2));
    }

    public function testAdd(): void
    {
        $users = new AuthenticatedUsers();

        $result = $users->add(self::USER_ID_1);

        self::assertSame($users, $result);
        self::assertTrue($users->isUserAuthenticated(self::USER_ID_1));
        self::assertArrayHasKey(self::USER_ID_1, $users->all());
    }

    public function testAddMultipleUsers(): void
    {
        $users = new AuthenticatedUsers();

        $users->add(self::USER_ID_1);
        $users->add(self::USER_ID_2);
        $users->add(self::USER_ID_3);

        self::assertCount(3, $users->all());
        self::assertTrue($users->isUserAuthenticated(self::USER_ID_1));
        self::assertTrue($users->isUserAuthenticated(self::USER_ID_2));
        self::assertTrue($users->isUserAuthenticated(self::USER_ID_3));
    }

    public function testRemove(): void
    {
        $users = new AuthenticatedUsers(null, null, self::USER_ID_1, self::USER_ID_2);

        $result = $users->remove(self::USER_ID_1);

        self::assertSame($users, $result);
        self::assertFalse($users->isUserAuthenticated(self::USER_ID_1));
        self::assertTrue($users->isUserAuthenticated(self::USER_ID_2));
        self::assertCount(1, $users->all());
    }

    public function testRemoveCurrentUserReassignsCurrent(): void
    {
        $users = new AuthenticatedUsers(self::USER_ID_1, null, self::USER_ID_1, self::USER_ID_2);

        self::assertSame(self::USER_ID_1, $users->getCurrent());

        $users->remove(self::USER_ID_1);

        // After removing current, the first remaining user should become current
        self::assertTrue($users->hasCurrent());
        self::assertSame(self::USER_ID_2, $users->getCurrent());
    }

    public function testRemoveCurrentUserWithNoOtherUsers(): void
    {
        $users = new AuthenticatedUsers(self::USER_ID_1, null, self::USER_ID_1);

        $users->remove(self::USER_ID_1);

        self::assertFalse($users->hasCurrent());
        self::assertNull($users->getCurrent());
    }

    public function testRemoveImpersonatedUser(): void
    {
        $users = new AuthenticatedUsers(self::USER_ID_1, self::USER_ID_2, self::USER_ID_1, self::USER_ID_2);

        self::assertTrue($users->isImpersonating());

        $users->remove(self::USER_ID_2);

        self::assertFalse($users->isImpersonating());
        self::assertNull($users->getImpersonated());
    }

    public function testIsUserAuthenticatedWithCurrentId(): void
    {
        $users = new AuthenticatedUsers(self::USER_ID_1);

        self::assertTrue($users->isUserAuthenticated(self::USER_ID_1));
    }

    public function testIsUserAuthenticatedWithImpersonatedId(): void
    {
        $users = new AuthenticatedUsers(null, self::USER_ID_2);

        self::assertTrue($users->isUserAuthenticated(self::USER_ID_2));
    }

    public function testIsUserAuthenticatedWithAddedUser(): void
    {
        $users = new AuthenticatedUsers();
        $users->add(self::USER_ID_3);

        self::assertTrue($users->isUserAuthenticated(self::USER_ID_3));
    }

    public function testIsUserAuthenticatedReturnsFalseForUnknownUser(): void
    {
        $users = new AuthenticatedUsers(self::USER_ID_1);

        self::assertFalse($users->isUserAuthenticated('unknown-user'));
    }

    public function testAllReturnsAllUsers(): void
    {
        $users = new AuthenticatedUsers(null, null, self::USER_ID_1, self::USER_ID_2);

        $all = $users->all();

        self::assertCount(2, $all);
        self::assertArrayHasKey(self::USER_ID_1, $all);
        self::assertArrayHasKey(self::USER_ID_2, $all);
    }

    public function testIntegerUserId(): void
    {
        $users = new AuthenticatedUsers(self::USER_ID_3);

        self::assertTrue($users->hasCurrent());
        self::assertSame(self::USER_ID_3, $users->getCurrent());
        self::assertTrue($users->isUserAuthenticated(self::USER_ID_3));
    }
}
