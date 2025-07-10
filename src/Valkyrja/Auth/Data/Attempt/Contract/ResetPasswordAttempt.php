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

namespace Valkyrja\Auth\Data\Attempt\Contract;

use Valkyrja\Auth\Data\Retrieval\Contract\Retrieval;

/**
 * Interface ResetPasswordAttempt.
 *
 * @author Melech Mizrachi
 */
interface ResetPasswordAttempt
{
    /**
     * Get the authentication retrieval.
     *
     * @return Retrieval
     */
    public function getRetrieval(): Retrieval;

    /**
     * Get the password.
     *
     * @return string
     */
    public function getPassword(): string;
}
