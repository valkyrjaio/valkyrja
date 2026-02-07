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
