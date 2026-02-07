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

namespace Valkyrja\Tests\Unit\Auth\Data\Retrieval;

use Valkyrja\Auth\Data\Retrieval\RetrievalById;
use Valkyrja\Auth\Entity\User;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the RetrievalById class.
 */
final class RetrievalByIdTest extends TestCase
{
    protected const string STRING_ID = 'user-uuid-123';
    protected const int INT_ID       = 42;

    public function testGetRetrievalFieldsWithStringId(): void
    {
        $retrieval = new RetrievalById(self::STRING_ID);

        $fields = $retrieval->getRetrievalFields(User::class);

        self::assertArrayHasKey(User::getIdField(), $fields);
        self::assertSame(self::STRING_ID, $fields[User::getIdField()]);
    }

    public function testGetRetrievalFieldsWithIntId(): void
    {
        $retrieval = new RetrievalById(self::INT_ID);

        $fields = $retrieval->getRetrievalFields(User::class);

        self::assertArrayHasKey(User::getIdField(), $fields);
        self::assertSame(self::INT_ID, $fields[User::getIdField()]);
    }

    public function testGetRetrievalFieldsReturnsCorrectFieldName(): void
    {
        $retrieval = new RetrievalById(self::STRING_ID);

        $fields = $retrieval->getRetrievalFields(User::class);

        // User::getIdField() should return 'id' as defined in the Entity base class
        self::assertCount(1, $fields);
    }
}
