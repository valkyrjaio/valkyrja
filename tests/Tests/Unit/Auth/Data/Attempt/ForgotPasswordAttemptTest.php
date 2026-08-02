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

use Valkyrja\Auth\Data\Attempt\ForgotPasswordAttempt;
use Valkyrja\Auth\Data\Retrieval\RetrievalByUsername;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the ForgotPasswordAttempt class.
 */
final class ForgotPasswordAttemptTest extends TestCase
{
    protected const string USERNAME = 'testuser';

    public function testGetRetrieval(): void
    {
        $retrieval = new RetrievalByUsername(self::USERNAME);
        $attempt   = new ForgotPasswordAttempt($retrieval);

        self::assertSame($retrieval, $attempt->getRetrieval());
    }
}
