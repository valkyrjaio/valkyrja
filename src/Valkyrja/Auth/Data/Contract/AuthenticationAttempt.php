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

/**
 * Interface AuthenticationAttempt.
 *
 * @author Melech Mizrachi
 */
interface AuthenticationAttempt extends AuthenticationRetrieval
{
    /**
     * Get the password.
     *
     * @return string
     */
    public function getPassword(): string;
}
