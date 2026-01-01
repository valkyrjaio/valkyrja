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

use Valkyrja\Auth\Data\Retrieval\Contract\RetrievalContract;

/**
 * Interface ForgotPasswordAttemptContract.
 */
interface ForgotPasswordAttemptContract
{
    /**
     * Get the authentication retrieval.
     *
     * @return RetrievalContract
     */
    public function getRetrieval(): RetrievalContract;
}
