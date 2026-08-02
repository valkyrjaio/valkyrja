<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Auth\Store;

use Valkyrja\Auth\Data\Retrieval\RetrievalByUsername;
use Valkyrja\Auth\Entity\User;
use Valkyrja\Auth\Store\NullStore;
use Valkyrja\Auth\Throwable\Exception\AuthInvalidRetrievableUserException;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the NullStore class.
 */
final class NullStoreTest extends TestCase
{
    protected NullStore $store;

    protected function setUp(): void
    {
        $this->store = new NullStore();
    }

    public function testHasRetrievableUser(): void
    {
        $retrieval = new RetrievalByUsername('testuser');

        self::assertFalse($this->store->hasRetrievable($retrieval, User::class));
    }

    public function testRetrieveAlwaysReturnsNull(): void
    {
        $this->expectException(AuthInvalidRetrievableUserException::class);
        $this->expectExceptionMessage('A user could not be retrieved with the given criteria');

        $retrieval = new RetrievalByUsername('testuser');

        $this->store->retrieve($retrieval, User::class);
    }

    public function testCreateDoesNotThrow(): void
    {
        $this->expectException(AuthInvalidRetrievableUserException::class);
        $this->expectExceptionMessage('A user could not be retrieved with the given criteria');

        $user           = new User();
        $user->id       = 'test-id';
        $user->username = 'testuser';

        $this->store->create($user);
    }

    public function testUpdateDoesNotThrow(): void
    {
        $this->expectException(AuthInvalidRetrievableUserException::class);
        $this->expectExceptionMessage('A user could not be retrieved with the given criteria');

        $user           = new User();
        $user->id       = 'test-id';
        $user->username = 'testuser';

        $this->store->update($user);
    }
}
