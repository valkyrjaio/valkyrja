<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Auth\Data\Attempt;

use Valkyrja\Auth\Data\Attempt\AuthenticationAttempt;
use Valkyrja\Auth\Data\Retrieval\RetrievalByUsername;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the AuthenticationAttempt class.
 */
final class AuthenticationAttemptTest extends TestCase
{
    protected const string USERNAME = 'testuser';
    protected const string PASSWORD = 'SecureP@ssw0rd!';

    public function testGetRetrieval(): void
    {
        $retrieval = new RetrievalByUsername(self::USERNAME);
        $attempt   = new AuthenticationAttempt($retrieval, self::PASSWORD);

        self::assertSame($retrieval, $attempt->getRetrieval());
    }

    public function testGetPassword(): void
    {
        $retrieval = new RetrievalByUsername(self::USERNAME);
        $attempt   = new AuthenticationAttempt($retrieval, self::PASSWORD);

        self::assertSame(self::PASSWORD, $attempt->getPassword());
    }
}
