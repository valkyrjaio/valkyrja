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

namespace Valkyrja\Auth\Policy;

use Valkyrja\Auth\Entity\Contract\PermissibleUser;

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
    protected function checkIsAuthorized(string $action): bool
    {
        return $this->user instanceof PermissibleUser
            ? $this->user->isAllowed($action)
            : false;
    }
}
