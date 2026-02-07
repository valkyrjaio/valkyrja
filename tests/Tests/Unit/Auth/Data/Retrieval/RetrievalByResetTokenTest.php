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
