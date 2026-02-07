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

    public function testRetrieveAlwaysReturnsNull(): void
    {
        $retrieval = new RetrievalByUsername('testuser');

        $result = $this->store->retrieve($retrieval, User::class);

        self::assertNull($result);
    }

    public function testCreateDoesNotThrow(): void
    {
        $user           = new User();
        $user->id       = 'test-id';
        $user->username = 'testuser';

        // Should not throw - just a no-op
        $this->store->create($user);

        // Verify user is not actually stored
        $retrieval = new RetrievalByUsername('testuser');
        self::assertNull($this->store->retrieve($retrieval, User::class));
    }

    public function testUpdateDoesNotThrow(): void
    {
        $user           = new User();
        $user->id       = 'test-id';
        $user->username = 'testuser';

        // Should not throw - just a no-op
        $this->store->update($user);

        // Verify user is not actually stored
        $retrieval = new RetrievalByUsername('testuser');
        self::assertNull($this->store->retrieve($retrieval, User::class));
    }
}
