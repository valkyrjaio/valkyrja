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
use Valkyrja\Auth\Data\Attempt\Contract\ResetPasswordAttempt as Contract;
use Valkyrja\Auth\Data\Retrieval\Contract\Retrieval;
use Valkyrja\Auth\Data\Retrieval\RetrievalByResetToken;

/**
 * Class ResetPasswordAttempt.
 *
 * @author Melech Mizrachi
 */
class ResetPasswordAttempt implements Contract
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
    public function getRetrieval(): Retrieval
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
