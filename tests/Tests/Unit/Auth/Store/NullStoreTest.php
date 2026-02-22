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
use Valkyrja\Auth\Entity\User;
use Valkyrja\Auth\Store\NullStore;
use Valkyrja\Auth\Throwable\Exception\InvalidRetrievableUserException;
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
        $this->expectException(InvalidRetrievableUserException::class);
        $this->expectExceptionMessage('A user could not be retrieved with the given criteria');

        $retrieval = new RetrievalByUsername('testuser');

        $this->store->retrieve($retrieval, User::class);
    }

    public function testCreateDoesNotThrow(): void
    {
        $this->expectException(InvalidRetrievableUserException::class);
        $this->expectExceptionMessage('A user could not be retrieved with the given criteria');

        $user           = new User();
        $user->id       = 'test-id';
        $user->username = 'testuser';

        $this->store->create($user);
    }

    public function testUpdateDoesNotThrow(): void
    {
        $this->expectException(InvalidRetrievableUserException::class);
        $this->expectExceptionMessage('A user could not be retrieved with the given criteria');

        $user           = new User();
        $user->id       = 'test-id';
        $user->username = 'testuser';

        $this->store->update($user);
    }
}
