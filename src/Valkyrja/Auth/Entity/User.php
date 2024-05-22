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

namespace Valkyrja\Auth\Entity;

use Valkyrja\Auth\Entity\Contract\User as Contract;
use Valkyrja\Orm\Entities\Entity;

/**
 * Entity User.
 *
 * @author Melech Mizrachi
 */
class User extends Entity implements Contract
{
    use UserFields;
    use UserTrait;

    /**
     * @inheritDoc
     */
    protected static string $tableName = 'users';
}
