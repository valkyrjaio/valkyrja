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

namespace Valkyrja\Auth\Data\Contract;

use Valkyrja\Auth\Entity\Contract\User;

/**
 * Interface AuthenticationRetrieval.
 *
 * @author Melech Mizrachi
 */
interface AuthenticationRetrieval
{
    /**
     * Get the fields to use to retrieve a user by.
     * - Assumed to be in the order of fields as they are in User::getAuthenticationFields().
     *
     * @param class-string<User> $user
     *
     * @return array<string, string|int>
     */
    public function getRetrievalFields(string $user): array;
}
