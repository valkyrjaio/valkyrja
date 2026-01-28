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

namespace Valkyrja\Tests\Unit\Auth\Data\Attempt;

use Valkyrja\Auth\Data\Attempt\ForgotPasswordAttempt;
use Valkyrja\Auth\Data\Retrieval\RetrievalByUsername;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the ForgotPasswordAttempt class.
 */
class ForgotPasswordAttemptTest extends TestCase
{
    protected const string USERNAME = 'testuser';

    public function testGetRetrieval(): void
    {
        $retrieval = new RetrievalByUsername(self::USERNAME);
        $attempt   = new ForgotPasswordAttempt($retrieval);

        self::assertSame($retrieval, $attempt->getRetrieval());
    }
}
