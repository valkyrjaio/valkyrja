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

use Valkyrja\Auth\Data\Attempt\ResetPasswordAttempt;
use Valkyrja\Auth\Data\Retrieval\RetrievalByResetToken;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the ResetPasswordAttempt class.
 */
final class ResetPasswordAttemptTest extends TestCase
{
    protected const string RESET_TOKEN  = 'abc123def456';
    protected const string NEW_PASSWORD = 'NewSecureP@ssw0rd!';

    public function testGetRetrieval(): void
    {
        $retrieval = new RetrievalByResetToken(self::RESET_TOKEN);
        $attempt   = new ResetPasswordAttempt($retrieval, self::NEW_PASSWORD);

        self::assertSame($retrieval, $attempt->getRetrieval());
    }

    public function testGetPassword(): void
    {
        $retrieval = new RetrievalByResetToken(self::RESET_TOKEN);
        $attempt   = new ResetPasswordAttempt($retrieval, self::NEW_PASSWORD);

        self::assertSame(self::NEW_PASSWORD, $attempt->getPassword());
    }
}
