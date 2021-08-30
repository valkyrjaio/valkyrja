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

namespace Valkyrja\Auth\Gates;

use Valkyrja\Auth\PermissibleUser;

/**
 * Class UserPermissiblePolicy.
 *
 * @author Melech Mizrachi
 */
class UserPermissiblePolicy extends Policy
{
    /**
     * @inheritDoc
     */
    public function isAuthorized(string $action): bool
    {
        return $this->before()
            ?? $this->after()
            ?? ($this->user instanceof PermissibleUser ? $this->user->isAllowed($action) : false);
    }
}
