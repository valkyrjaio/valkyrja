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

use Valkyrja\Auth\Data\Attempt\LockAttempt;
use Valkyrja\Auth\Data\Retrieval\RetrievalById;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the LockAttempt class.
 */
final class LockAttemptTest extends TestCase
{
    protected const string USER_ID = 'user-123';

    public function testGetRetrieval(): void
    {
        $retrieval = new RetrievalById(self::USER_ID);
        $attempt   = new LockAttempt($retrieval);

        self::assertSame($retrieval, $attempt->getRetrieval());
    }
}
