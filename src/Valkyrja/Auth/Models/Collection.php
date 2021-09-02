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

namespace Valkyrja\Auth\Models;

use Valkyrja\Auth\Collection as Contract;
use Valkyrja\Auth\User;
use Valkyrja\Support\Model\Classes\Model;

/**
 * Class Collection.
 *
 * @author Melech Mizrachi
 */
class Collection extends Model implements Contract
{
    public function getCurrent(): User
    {
    }

    public function setCurrent(User $user): void
    {
    }

    public function addUser(User $user): void
    {
    }

    public function getUsers(): User
    {
    }

    public function setUsers(User ...$user): void
    {
    }
}
