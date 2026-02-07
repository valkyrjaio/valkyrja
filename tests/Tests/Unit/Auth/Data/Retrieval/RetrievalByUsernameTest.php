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
