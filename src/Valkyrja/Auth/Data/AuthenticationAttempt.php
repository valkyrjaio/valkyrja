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

use Valkyrja\Auth\Data\Contract\AuthenticationAttempt as Contract;

/**
 * Class AuthenticationIdRetrieval.
 *
 * @author Melech Mizrachi
 */
class AuthenticationAttempt extends AuthenticationRetrieval implements Contract
{
    public function __construct(
        string $username,
        protected string $password,
    ) {
        parent::__construct($username);
    }

    /**
     * @inheritDoc
     */
    public function getPassword(): string
    {
        return $this->password;
    }
}
