<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Auth\Data\Retrieval;

use Valkyrja\Auth\Data\Retrieval\RetrievalByIdAndUsername;
use Valkyrja\Auth\Entity\User;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the RetrievalByIdAndUsername class.
 */
final class RetrievalByIdAndUsernameTest extends TestCase
{
    protected const string STRING_ID = 'user-uuid-123';
    protected const int INT_ID       = 42;
    protected const string USERNAME  = 'testuser';

    public function testGetRetrievalFieldsWithStringId(): void
    {
        $retrieval = new RetrievalByIdAndUsername(self::STRING_ID, self::USERNAME);

        $fields = $retrieval->getRetrievalFields(User::class);

        self::assertCount(2, $fields);
        self::assertArrayHasKey(User::getIdField(), $fields);
        self::assertArrayHasKey(User::getUsernameField(), $fields);
        self::assertSame(self::STRING_ID, $fields[User::getIdField()]);
        self::assertSame(self::USERNAME, $fields[User::getUsernameField()]);
    }

    public function testGetRetrievalFieldsWithIntId(): void
    {
        $retrieval = new RetrievalByIdAndUsername(self::INT_ID, self::USERNAME);

        $fields = $retrieval->getRetrievalFields(User::class);

        self::assertCount(2, $fields);
        self::assertSame(self::INT_ID, $fields[User::getIdField()]);
        self::assertSame(self::USERNAME, $fields[User::getUsernameField()]);
    }
}
