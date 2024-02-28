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

namespace Valkyrja\Auth\Models;

use Valkyrja\Auth\AuthenticationRetrieval as Contract;
use Valkyrja\Auth\User;

/**
 * Class AuthenticationIdRetrieval.
 *
 * @author Melech Mizrachi
 */
class AuthenticationIdRetrieval implements Contract
{
    public function __construct(
        protected string|int $id,
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
            $user::getIdField() => $this->id,
        ];
    }
}
