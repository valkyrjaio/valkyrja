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

namespace Valkyrja\Auth\Data\Attempt;

use Override;
use Valkyrja\Auth\Data\Attempt\Contract\ResetPasswordAttemptContract;
use Valkyrja\Auth\Data\Retrieval\Contract\RetrievalContract;
use Valkyrja\Auth\Data\Retrieval\RetrievalByResetToken;

class ResetPasswordAttempt implements ResetPasswordAttemptContract
{
    public function __construct(
        protected RetrievalByResetToken $retrieval,
        protected string $password,
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getRetrieval(): RetrievalContract
    {
        return $this->retrieval;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getPassword(): string
    {
        return $this->password;
    }
}
