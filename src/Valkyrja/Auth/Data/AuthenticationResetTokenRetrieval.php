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

namespace Valkyrja\Auth\Data;

use Valkyrja\Auth\Data\Contract\AuthenticationRetrieval as Contract;
use Valkyrja\Auth\Entity\Contract\User;

/**
 * Class AuthenticationResetTokenRetrieval.
 *
 * @author Melech Mizrachi
 */
class AuthenticationResetTokenRetrieval implements Contract
{
    public function __construct(
        protected string $resetToken,
    ) {
    }

    /**
     * @inheritDoc
     *
     * @param class-string<User> $user
     */
    public function getRetrievalFields(string $user): array
    {
        return [
            $user::getResetTokenField() => $this->resetToken,
        ];
    }
}
