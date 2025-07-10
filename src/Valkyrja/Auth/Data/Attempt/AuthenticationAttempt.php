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
use Valkyrja\Auth\Data\Attempt\Contract\AuthenticationAttempt as Contract;
use Valkyrja\Auth\Data\Retrieval\Contract\Retrieval;

/**
 * Class AuthenticationAttempt.
 *
 * @author Melech Mizrachi
 */
class AuthenticationAttempt implements Contract
{
    public function __construct(
        protected Retrieval $retrieval,
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
