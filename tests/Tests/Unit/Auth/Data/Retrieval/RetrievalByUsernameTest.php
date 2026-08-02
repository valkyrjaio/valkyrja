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

use Valkyrja\Auth\Data\Retrieval\RetrievalByUsername;
use Valkyrja\Auth\Entity\User;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the RetrievalByUsername class.
 */
final class RetrievalByUsernameTest extends TestCase
{
    protected const string USERNAME = 'testuser';

    public function testGetRetrievalFields(): void
    {
        $retrieval = new RetrievalByUsername(self::USERNAME);

        $fields = $retrieval->getRetrievalFields(User::class);

        self::assertArrayHasKey(User::getUsernameField(), $fields);
        self::assertSame(self::USERNAME, $fields[User::getUsernameField()]);
    }

    public function testGetRetrievalFieldsReturnsCorrectFieldName(): void
    {
        $retrieval = new RetrievalByUsername(self::USERNAME);

        $fields = $retrieval->getRetrievalFields(User::class);

        self::assertCount(1, $fields);
        self::assertArrayHasKey('username', $fields);
    }
}
