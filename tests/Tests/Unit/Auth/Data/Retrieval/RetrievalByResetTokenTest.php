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

use Valkyrja\Auth\Data\Retrieval\RetrievalByResetToken;
use Valkyrja\Auth\Entity\User;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the RetrievalByResetToken class.
 */
final class RetrievalByResetTokenTest extends TestCase
{
    protected const string RESET_TOKEN = 'abc123def456';

    public function testGetRetrievalFields(): void
    {
        $retrieval = new RetrievalByResetToken(self::RESET_TOKEN);

        $fields = $retrieval->getRetrievalFields(User::class);

        self::assertArrayHasKey(User::getResetTokenField(), $fields);
        self::assertSame(self::RESET_TOKEN, $fields[User::getResetTokenField()]);
    }

    public function testGetRetrievalFieldsReturnsCorrectFieldName(): void
    {
        $retrieval = new RetrievalByResetToken(self::RESET_TOKEN);

        $fields = $retrieval->getRetrievalFields(User::class);

        self::assertCount(1, $fields);
        self::assertArrayHasKey('reset_token', $fields);
    }
}
