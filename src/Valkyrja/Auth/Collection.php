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

namespace Valkyrja\Auth;

use Valkyrja\Support\Model\Model;

/**
 * Interface Collection.
 *
 * @author Melech Mizrachi
 */
interface Collection extends Model
{
    public function getCurrent(): User;

    public function setCurrent(User $user): void;

    public function addUser(User $user): void;

    public function getUsers(): User;

    public function setUsers(User ...$user): void;
}
